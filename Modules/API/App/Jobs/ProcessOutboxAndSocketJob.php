<?php

namespace Modules\API\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Trait\SmsCountTrait;

class ProcessOutboxAndSocketJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  use SmsCountTrait;
  use AggregatorTrait;
  public $tries = 3;
  public $maxExceptions = 2;
  public $timeout = 120;
  public $backoff = [30, 60, 120];

  protected $message;
  protected $data;
  protected $user;

  public function __construct(Message $message, array $data, $user)
  {
    $this->message = $message->withoutRelations();
    $this->data = $data;
    $this->user = $user;
  }

  public function handle()
  {
    try {
      // Set timezone
      date_default_timezone_set('Asia/Dhaka');

      // Prepare outbox data
      $outboxPayloadArray = $this->prepareOutboxPayload();

      // Save to outbox in bulk
      if (!Outbox::insert($outboxPayloadArray)) {
        throw new \Exception('Failed to insert outbox records');
      }

      // Send to socket or aggregator
      $this->dispatchToDeliverySystem();

      Log::channel('sms')->info('Successfully processed message', [
        'message_id' => $this->message->orderid,
        'recipient_count' => count($outboxPayloadArray)
      ]);

    } catch (\Exception $e) {
      Log::channel('sms')->error('ProcessOutboxAndSocketJob failed', [
        'message_id' => $this->message->orderid ?? null,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      // Update message status if needed
      $this->markMessageAsFailed($e->getMessage());

      throw $e; // This will trigger job retries
    }
  }

  protected function prepareOutboxPayload(): array
  {
    $explodeRecipients = explode(",", $this->message->recipient);
    $priority = $this->getPriority($explodeRecipients);
    $numbers = $this->filterValidNumbers($explodeRecipients);
    $countSmsInfo = $this->countSms($this->data['message_text']);
    $totalMessage = count($numbers) * (int)$countSmsInfo->count;

    $outboxPayloadArray = [];
    foreach ($explodeRecipients as $destmn) {
      $outboxPayloadArray[] = [
        "srcmn" => $this->message->senderID,
        "mask" => $this->message->senderID,
        "destmn" => trim($destmn),
        "message" => $this->message->message,
        "country_code" => null,
        "operator_prefix" => $this->getPrefix($destmn),
        "status" => 'Queue',
        "write_time" => now()->format('Y-m-d H:i:s'),
        "sent_time" => null,
        "ton" => 5,
        "npi" => 1,
        "message_type" => 'text',
        "is_unicode" => false,
        "smscount" => $totalMessage,
        "esm_class" => '',
        "data_coding" => $this->data['dataCoding'] ?? '',
        "reference_id" => $this->message->id,
        "last_updated" => now()->format('Y-m-d H:i:s'),
        "schedule_time" => null,
        "retry_count" => 0,
        "user_id" => $this->user->id,
        "remarks" => '',
        "uuid" => hex2bin(str_replace('-', '', Str::uuid()->toString())),
        "priority" => $priority,
        "blocked_status" => null,
        "created_at" => now()->format('Y-m-d H:i:s'),
        "updated_at" => now()->format('Y-m-d H:i:s'),
        "error_code" => null,
        "error_message" => null,
        "sms_cost" => (double)($this->user->smsRate->nonmasking_rate ?? 0),
        "sms_uniq_id" => getenv('MESSAGE_PREFIX'). " " . date('Ymdhis') . '-' . trim($destmn) . '-' . '0000000001',
      ];
    }

    return $outboxPayloadArray;
  }

  protected function dispatchToDeliverySystem()
  {
    $numbers = $this->filterValidNumbers(explode(",", $this->message->recipient));

    if (getenv('APP_TYPE') === 'Aggregator') {
      $this->sendToAggregator($numbers);
    } else {
      $this->sendToSocket($numbers);
    }
  }

  protected function sendToAggregator(array $numbers)
  {
    $payload = [
      'msisdnList' => $numbers,
      'message' => $this->message->message,
      'campaign_id' => $this->message->campaign_id,
      'transactionType' => count($numbers) > 1 ? 'P' : 'T'
    ];

    $this->sendMessageToInfozilion($payload);

    Log::channel('sms')->info('Sent to aggregator', [
      'message_id' => $this->message->orderid,
      'recipient_count' => count($numbers)
    ]);
  }

  protected function sendToSocket(array $numbers)
  {
    $countSmsInfo = $this->countSms($this->message->message);
    $encoding = 0; // Assuming default encoding

    foreach ($numbers as $number) {
      $socketData = [
        'messageId' => $this->message->orderid . '-' . $number,
        'senderId' => $this->message->senderID,
        'userId' => $this->user->id,
        'recepient' => $number,
        'type' => 'single',
        'encoding' => $encoding,
        "parts" => (array)$countSmsInfo->parts,
        "has_gsm_extended" => 0,
        "no_of_sms" => (int)$countSmsInfo->count
      ];

      // $this->socketService->sendData(json_encode($socketData) . "\n");
      Log::channel('sms')->debug('Socket payload prepared', $socketData);
    }
  }

  protected function filterValidNumbers(array $recipients): array
  {
    $pattern = "/(^(\+8801|8801|01|008801))[1|3-9]{1}(\d){8}$/";
    return array_filter($recipients, function($number) use ($pattern) {
      return preg_match($pattern, trim($number));
    });
  }

  protected function getPrefix(string $number): string
  {
    // Extract operator prefix logic here
    return substr(trim($number), 0, 5); // Example implementation
  }

  protected function getPriority(array $numbers): int
  {
    // Your priority calculation logic
    return count($numbers) > 100 ? 1 : 2; // Example implementation
  }

  protected function markMessageAsFailed(string $error)
  {
    try {
      $this->message->update([
        'status' => 'Failed',
        'error_message' => $error
      ]);
    } catch (\Exception $e) {
      Log::channel('sms')->error('Failed to update message status', [
        'message_id' => $this->message->orderid,
        'error' => $e->getMessage()
      ]);
    }
  }

  public function failed(\Throwable $exception)
  {
    Log::channel('sms')->critical('Job failed after all retries', [
      'message_id' => $this->message->orderid ?? null,
      'error' => $exception->getMessage(),
      'trace' => $exception->getTraceAsString()
    ]);

    $this->markMessageAsFailed('Job failed: ' . $exception->getMessage());
  }
}

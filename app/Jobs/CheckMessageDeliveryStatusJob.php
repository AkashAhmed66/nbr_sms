<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Modules\Messages\App\Models\Message;

class CheckMessageDeliveryStatusJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected array $messages;

  public function __construct(array $messages)
  {
    $this->messages = $messages;
  }

  public function handle(): void
  {
    Log::channel('getDLRLog')->info("Processing job with " . count($this->messages) . " messages.");

    foreach ($this->messages as $msg) {
      try {
        // Throttle to 100 API calls per second
        $this->throttleApiRate();

        $msisdnChunks = array_chunk($msg['msisdnList'], 1);

        foreach ($msisdnChunks as $chunk) {
          $payload = [
            "username" => env('AGGREGATOR_USERNAME'),
            "password" => env('AGGREGATOR_PASSWORD'),
            "billMsisdn" => $msg['cli'],
            "apiKey" => env('AGGREGATOR_API_KEY'),
            "msisdnList" => $chunk,
            "serverReference" => $msg['orderId'],
          ];

          Log::channel('getDLRLog')->info('Aggregator DLR API Payload : ', $payload);

          try {
            $response = Http::timeout(10)
              ->retry(3, 2000)
              ->post(env('AGGREGATOR_CHECK_DELIVERY_API_URL'), $payload);

            $responseData = $response->json();

            Log::channel('getDLRLog')->info('Aggregator DLR API Response', [
              'status' => $response->status(),
              'body' => $response->body(),
            ]);

            $this->updateMessageAndOutbox($msg['id'], $responseData);

          } catch (\Exception $e) {
            Log::channel('getDLRLog')->error('API request failed', [
              'error' => $e->getMessage(),
              'payload' => $payload,
            ]);
          }
        }

      } catch (\Exception $e) {
        Log::channel('getDLRLog')->error("Error processing Message {$msg['id']}: " . $e->getMessage());
      }
    }
  }

  private function throttleApiRate(): void
  {
    $second = now()->timestamp;
    $count = Redis::incr("api_calls:$second");

    if ($count == 1) {
      Redis::expire("api_calls:$second", 2);
    }

    if ($count > 100) {
      usleep(10000); // 10ms wait if exceeded
    }
  }

  private function updateMessageAndOutbox(int $id, ?array $responseData): void
  {
    $sendMessage = Message::find($id);
    if (!$sendMessage) {
      Log::channel('getDLRLog')->error("Message with ID {$id} not found.");
      return;
    }

    $sendMessage->fill([
      'serverTxnId' => $responseData['serverTxnId'] ?? $sendMessage->serverTxnId,
      'serverResponseCode' => $responseData['serverResponseCode'] ?? null,
      'serverResponseMessage' => $responseData['serverResponseMessage'] ?? null,
      'a2pDeliveryStatus' => $responseData['a2pDeliveryStatus'] ?? null,
      'a2pSendSmsBusinessCode' => $responseData['a2pSendSmsBusinessCode'] ?? null,
      'deliveryStatus' => $responseData['deliveryStatus'] ?? null,
      'dndMsisdn' => $responseData['dndMsisdn'] ?? null,
      'invalidMsisdn' => $responseData['invalidMsisdn'] ?? null,
      'ansSendSmsHttpStatus' => $responseData['ansSendSmsHttpStatus'] ?? null,
      'ansSendSmsBusinessCode' => $responseData['ansSendSmsBusinessCode'] ?? null,
      'mnoResponseCode' => $responseData['mnoResponseCode'] ?? null,
      'mnoResponseMessage' => $responseData['mnoResponseMessage'] ?? null,
    ])->save();

    if (!empty($responseData['deliveryStatus'])) {
      $updates = [];

      foreach ($responseData['deliveryStatus'] as $phoneStatus) {
        if (is_string($phoneStatus) && strpos($phoneStatus, "-") !== false) {
          [$msisdn, $status] = explode("-", $phoneStatus, 2);
          if (!empty($status)) {
            $updates[substr($msisdn, -10)] = $status;
          }
        }
      }

      if (!empty($updates)) {
        $caseSql = "CASE RIGHT(destmn, 10) ";
        foreach ($updates as $last10 => $status) {
          $caseSql .= "WHEN '{$last10}' THEN '{$status}' ";
        }
        $caseSql .= "ELSE dlr_status END";

        DB::table('outbox')
          ->where('reference_id', $sendMessage->id)
          ->whereIn(DB::raw("RIGHT(destmn, 10)"), array_keys($updates))
          ->update(['dlr_status' => DB::raw($caseSql)]);

        Log::channel('getDLRLog')->info(
          "Bulk updated Outbox for Msg {$sendMessage->id} with " . count($updates) . " rows."
        );
      }
    }
  }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendMessageToInfozillionJob;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Trait\ReveApiTrait;

class SendScheduledMessagesCommand extends Command
{
  use ReveApiTrait;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'messages:send-scheduled';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send scheduled messages that are pending and due';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $now = now();

    $scheduledMessages = Message::with('outboxMessage')
      ->where('schedule_message_status', 'pending')
      ->where('scheduleDateTime', '<=', $now)
      ->get();
    
    Log::channel('sms')->info("Processing " . count($scheduledMessages) . " scheduled messages.");

    foreach ($scheduledMessages as $message) {
      $numbers = $message->outboxMessage->pluck('destmn')->toArray();


      $numbers = array_map(function ($number) {
        $number = preg_replace('/\D+/', '', $number); // Remove non-digit characters

        if (str_starts_with($number, '+88')) {
          $number = substr($number, 1); // Remove '+'
        } elseif (!str_starts_with($number, '88')) {
          $number = '88' . $number; // Add '88' if not present
        }

        return $number;
      }, $numbers);



      try {
        Log::channel('sms')->info("Sending message IDs {$message->id}: {$message->message}");

        $infozillion_array = array('msisdnList' => $numbers, 'message' => $message->message, 'campaign_id' => $message->campaign_id);
        $infozillion_array['transactionType'] = count($numbers) > 1 ? 'P' : 'T';
        $infozillion_array['cli'] = $message->senderID ?? env('AGGREGATOR_CLI');
        $infozillion_array['isunicode'] = $message->is_unicode == '1' ? '1' : '0';

        if (env('IS_API_BASED')) {
          $this->sendMessageToReveApi($message->id);
        } else {
          SendMessageToInfozillionJob::dispatch($infozillion_array, $message->id);
        }
        $message->update(['schedule_message_status' => 'sent']);

      } catch (\Exception $e) {
        Log::channel('sms')->error("Failed to send message ID {$message->id}: {$e->getMessage()}");
        $message->update(['schedule_message_status' => 'failed']);
      }
    }

    $this->info('Scheduled messages processed. ' . count($scheduledMessages) . ' messages sent.');
  }
}

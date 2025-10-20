<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Models\Outbox;
use Modules\Messages\App\Trait\SmsCountTrait;

class RetrySocket extends Command
{
  use SmsCountTrait;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'socket:retry';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';
  private $socket;
  private $connected = false;
  private $host;
  private $port;

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->host = env('SOCKET_SERVER_HOST', '127.0.0.1');
    $this->port = env('SOCKET_SERVER_PORT', 4000);

    $this->connect();


    while (true) {

      //query from outbox where retry_count 0 and time duration from created_at is more than 5 minutes.
      Log::channel('socketretry')->info("Retrying messages from outbox starting at: " . now());
      $outboxMessagesForRetry1 = Outbox::where('retry_count', 0)
        ->whereBetween('created_at', [
          now()->subHours(24),   // 24 hours ago
          now()->subMinutes(5)   // 5 minutes ago
        ])
        //->where('created_at', '<', now()->subMinutes(5))
        ->whereNotIn('dlr_status_code', [200])
        ->get();

      Log::channel('socketretry')->info("Found " . $outboxMessagesForRetry1->count() . " messages for retry with retry_count 0");

      //query from outbox where retry_count 1 and time duration from created_at is more than 15 minutes.
      Log::channel('socketretry')->info("Retrying messages with retry_count 1 starting at: " . now());
      $outboxMessagesForRetry2 = Outbox::where('retry_count', 1)
        ->whereBetween('created_at', [
          now()->subHours(24),   // 24 hours ago
          now()->subMinutes(15)  // 15 minutes ago
        ])
        //->where('created_at', '<', now()->subMinutes(15))
        ->whereNotIn('dlr_status_code', [200])
        ->get();

      Log::channel('socketretry')->info("Found " . $outboxMessagesForRetry2->count() . " messages for retry with retry_count 1");

      //query from outbox where retry_count 2 and time duration from created_at is more than 30 minutes.
      Log::channel('socketretry')->info("Retrying messages with retry_count 2 starting at: " . now());
      $outboxMessagesForRetry3 = Outbox::where('retry_count', 2)
        ->whereBetween('created_at', [
          now()->subHours(24),  // 24 hours ago
          now()->subHour()      // 1 hour ago
        ])
        //->where('created_at', '<', now()->subMinutes(60))
        ->whereNotIn('dlr_status_code', [200])
        ->get();

      Log::channel('socketretry')->info("Found " . $outboxMessagesForRetry3->count() . " messages for retry with retry_count 2");


      //merge all the outbox messages
      $outboxMessages = $outboxMessagesForRetry1->merge($outboxMessagesForRetry2)
        ->merge($outboxMessagesForRetry3);


      foreach ($outboxMessages as $retryMessage) {

        $countSmsInfo = $this->countSms($retryMessage->message);
        if ($retryMessage->is_unicode == 1)
          $encoding = 8;
        else
          $encoding = 0;

        $socketData = [
          'messageId' => (string) $retryMessage->id,
          'senderId' => $retryMessage->srcmn,
          'userId' => $retryMessage->user_id,
          'recepient' => $retryMessage->destmn,
          'type' => $retryMessage->type == "1" ? 'group' : 'single',
          'encoding' => $encoding,
          "parts" => (array) $countSmsInfo->parts,
          "has_gsm_extended" => 0,
          "no_of_sms" => (int) $countSmsInfo->count,
          "rn_code" => $retryMessage->rn_code,
          "retry_count" => $retryMessage->retry_count + 1
        ];

        $dataString = json_encode($socketData) . "\n";

        try {
          $sent = socket_write($this->socket, $dataString, strlen($dataString));
        } catch (\Exception $e) {
          $this->info("Broken pipe");
          $sent = false;
        }


        if ($sent === false) {
          Log::channel('socketretry')->error("Socket write failed:");
          $this->info("Retry Message failed to : " . $retryMessage->destmn);
          $this->connect();
          break;
        } else {
          $this->info("Retry Message sent to : " . $retryMessage->destmn);
          Log::channel('socketretry')->info("Retry Message sent to : " . $retryMessage->destmn);

          //update outbox message retry count
          $retryMessage->retry_count += 1;
          $retryMessage->save();
        }
      }
      usleep(100); // Wait for 100 microseconds before checking again
    }
  }

  private function connect()
  {
    $this->info("Retry Connecting with host: $this->host and port: $this->port");
    Log::channel('socketretry')->info("Connecting with host: $this->host and port: $this->port");
    $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($this->socket === false) {
      Log::channel('socketretry')->error("Socket create failed: ");
    }

    try {
      if (socket_connect($this->socket, $this->host, $this->port) === false) {
        Log::channel('socketretry')->error("Socket connect failed: ");
      } else {
        $this->connected = true;
        $this->info("Socket connected");
        Log::channel('socketretry')->info("Socket connected successfully");
      }
    } catch (\Exception $e) {
      $this->info("Socket connection exception: ");
      Log::channel('socketretry')->error("Socket connection exception: ");
    }


    if (!$this->connected) {
      Log::channel('socketretry')->error("Failed to connect to socket server at $this->host:$this->port");
      sleep(5); // Wait before retrying
      $this->connect();
    }
  }
}

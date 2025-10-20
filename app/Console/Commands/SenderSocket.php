<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Models\Outbox;
use Modules\Messages\App\Trait\SmsCountTrait;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

class SenderSocket extends Command
{
  use SmsCountTrait;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'socket:sender';

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

      $unsentMessages = Outbox::where('dlr_status_code', -1)->get();

      foreach ($unsentMessages as $unsentMessage) {

        $countSmsInfo = $this->countSms($unsentMessage->message);
        if ($unsentMessage->is_unicode == 1) $encoding = 8;
        else $encoding = 0;

        $socketData = [
          'messageId' => (string)$unsentMessage->id,
          'senderId' => $unsentMessage->srcmn,
          'userId' => $unsentMessage->user_id,
          'recepient' => $unsentMessage->destmn,
          'type' => $unsentMessage->type == "1" ? 'group' :'single',
          'encoding' => $encoding,
          "parts" => (array)$countSmsInfo->parts,
          "has_gsm_extended" => 0,
          "no_of_sms" => (int)$countSmsInfo->count,
          "rn_code" => $unsentMessage->rn_code,
          "retry_count" => 0
        ];

        $dataString = json_encode($socketData) . "\n";
        try {

          //USER BALANCE DEDUCTION
          $user = $unsentMessage->user;
          $totalCost = doubleval(@$user->smsRate->nonmasking_rate * intval($countSmsInfo->count));
          $user->available_balance -= $totalCost;
          $user->save();

          //REDIS UPDATE by users table data
          $key = "user:{$user->username}";
          $json = Redis::get($key);
          $userData = $json ? json_decode($json, true) : [];
          $userData['available_balance'] = $user->available_balance;
          Redis::set($key, json_encode($userData));


          //SOCKET SEND
          $sent = socket_write($this->socket, $dataString, strlen($dataString));
        }catch (\Exception $e){
          $this->info("Broken pipe");
          $sent = false;
        }


        if ($sent === false) {
          Log::channel('socketsender')->error("Socket write failed:");
          $this->info("Message failed to : " . $unsentMessage->destmn);
          $this->connect();
          break;
        } else {
          $this->info("Message sent to : " . $unsentMessage->destmn);
          Log::channel('socketsender')->info("socketsender log");
          Log::channel('socketsender')->info("Message sent to : " . $unsentMessage->destmn);
          Log::channel('socketsender')->info($dataString);

          //update outbox message status
          $unsentMessage->dlr_status_code = 1;
          $unsentMessage->engine_dlr_request_datetime = Carbon::now();
          $unsentMessage->save();
        }
      }
      usleep(100); // Wait for 100 microseconds before checking again
    }
  }

  private function connect()
  {
    $this->info("Connecting with host: $this->host and port: $this->port");
    Log::channel('socketsender')->info("Connecting with host: $this->host and port: $this->port");
    $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($this->socket === false) {
      Log::channel('socketsender')->error("Socket create failed: ");
    }

    try {
      if (socket_connect($this->socket, $this->host, $this->port) === false) {
        Log::channel('socketsender')->error("Socket connect failed: ");
      } else {
        $this->connected = true;
        $this->info("Socket connected");
        Log::channel('socketsender')->info("Socket connected successfully");
      }
    } catch (\Exception $e) {
      $this->info("Socket connection exception: ");
      Log::channel('socketsender')->error("Socket connection exception: ");
    }


    if (!$this->connected) {
      Log::channel('socketsender')->error("Failed to connect to socket server at $this->host:$this->port");
      sleep(5); // Wait before retrying
      $this->connect();
    }
  }
}

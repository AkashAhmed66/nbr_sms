<?php

namespace App\Console\Commands;

use App\Jobs\IncommingMessageSaveApiJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\ProcessRetryRequest;
use App\Jobs\ProcessMessageStatusUpdate;
use App\Jobs\ProcessIncomingMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Models\Inbox;
use Modules\Messages\App\Models\Outbox;
use Modules\Messages\App\Models\OutboxHistory;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Users\App\Models\User;

class SocketServerBackup20250805 extends Command
{
  use SmsCountTrait;

  protected $signature = 'socket:listen';
  protected $description = 'Start a socket server that listens on port 8080';

  public function handle()
  {
    $host = env('SOCKET_SERVER_LISTENING_HOST', '0.0.0.0');
    $port = env('SOCKET_SERVER_LISTENING_PORT', 4000);

    // Create a TCP/IP socket
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
      $this->error("Socket creation failed: " . socket_strerror(socket_last_error()));
      return;
    }

    // Bind the socket to the IP and port
    if (socket_bind($socket, $host, $port) === false) {
      $this->error("Socket binding failed: " . socket_strerror(socket_last_error($socket)));
      socket_close($socket);
      return;
    }

    // Listen for incoming connections
    if (socket_listen($socket, 5) === false) {
      $this->error("Socket listening failed: " . socket_strerror(socket_last_error($socket)));
      socket_close($socket);
      return;
    }

    $this->info("Server listening on {$host}:{$port}");

    while (true) {
      $clientSocket = @socket_accept($socket);
      if ($clientSocket === false) {
        $this->error("Socket accept failed: " . socket_strerror(socket_last_error($socket)));
      }

      // Loop to keep the server running and accepting connections
      while (true) {
        try {
          $message = trim(socket_read($clientSocket, 1024, PHP_NORMAL_READ));
          if (empty($message)) {
            break;
          }

          //$message = "SMS_STATUS|messageId|status|userId";
          $this->info("Received message: {$message}");
          $this->updateMessage($message);
        } catch (\Exception $exception) {
          break;
        }
      }
      socket_close($clientSocket);
    }
  }

  public function updateMessage($message)
  {
    try {
      $messageParts = explode("|", $message);
      $command = $messageParts[0];

      match ($command) {
        'SMS_STATUS' => $this->handleStatusUpdate($messageParts),
        //'RETRY' => $this->handleRetry($messageParts),
        'INCOMING' => $this->handleIncomming($messageParts),
        default => $this->error("Invalid message format: {$message}"),
      };
    } catch (\Exception $e) {
      $this->error("Unexpected error in updateMessage: " . $e->getMessage());
    }
  }

  public function handleStatusUpdate(array $messageParts): void
  {
    $messageId = $messageParts[1];
    $statusCode = (int)$messageParts[2];
    $now = Carbon::now();

    $status = $this->getMessageStatusByCode($statusCode);
    $dlrStatusData = $this->getDlrStatusInfo($statusCode);

    $outboxSingleMessage = Outbox::find($messageId);
    if (!$outboxSingleMessage) {
      Log::channel('socketlisten')->info("Outbox message not found");
      return;
    }

    $user = User::find($outboxSingleMessage->user_id);
    $smsRate = (@$user->smsRate->nonmasking_rate ?? 0) * $outboxSingleMessage->smscount;

    $infozillionStatusCode = null;
    $infozillionStatusMeaning = null;
    $infozillionDlrRequestDateTime = Carbon::now();
    $infozillionDlrResponseDateTime = null;

    if (!in_array($statusCode, [1001, 1002])) {
      $infozillionDlrCallbackResponse = $this->infozillionDlrCallback($user, $outboxSingleMessage, $statusCode);

      if ($infozillionDlrCallbackResponse) {
        $data = json_decode($infozillionDlrCallbackResponse, true);
        $infozillionStatusCode = $data['statusInfo']['statusCode'] ?? null;
        $infozillionStatusMeaning = $data['statusInfo']['errordescription'] ?? null;
        $infozillionDlrResponseDateTime = Carbon::now();
      }
    }

    try {
      $updateData = [
        'status' => $status,
        'sent_time' => $now,
        'sms_cost' => $smsRate,
        'error_code' => 0,
        'error_message' => 'No error',
        'updated_at' => $now,
        'dlr_status_code' => $dlrStatusData['code'] == 0 ? 200 : $dlrStatusData['code'],
        'dlr_status' => $dlrStatusData['status'],
        'dlr_status_meaning' => $dlrStatusData['meaning'],
        'infozillion_dlr_status_code' => $infozillionStatusCode,
        'infozillion_dlr_status_meaning' => $infozillionStatusMeaning,
        'infozillion_dlr_request_datetime' => $infozillionDlrRequestDateTime,
        'infozillion_dlr_response_datetime' => $infozillionDlrResponseDateTime,
        'engine_dlr_response_datetime' => $now,
      ];

      Outbox::where('id', $messageId)->update($updateData);
      //OutboxHistory::where('id', $messageId)->update($updateData);

      /*if ($statusCode === 0) {
        $user->available_balance -= $smsRate;
        $user->updated_at = $now;
        $user->save();
      }*/

      $this->info("Message status updated: {$outboxSingleMessage->destmn} | {$dlrStatusData['status']} | {$dlrStatusData['code']}");

      Log::channel('socketlisten')->info("Message update completed: {$outboxSingleMessage->destmn} | {$messageId} | {$statusCode}");
    } catch (\Exception $e) {
      Log::channel('socketlisten')->error("DB update error: " . $e->getMessage());
    }
  }

  private function getMessageStatusByCode($code): string
  {
    return match ($code) {
      0 => 'Delivered',
      1001 => 'Sent',
      1002 => 'Queue',
      default => 'Failed',
    };
  }

  private function getDlrStatusInfo($code): array
  {
    $map = [
      0 => ['status' => 'Delivered', 'meaning' => 'Delivered in Handset'],
      6 => ['status' => 'Absent subscriber for SM', 'meaning' => 'Subscriber handset is not logged onto the network...'],
      32 => ['status' => 'Undelivered', 'meaning' => 'No memory capacity on handset...'],
      31 => ['status' => 'Subscriber Busy', 'meaning' => 'MSC is busy handling an existing transaction...'],
      5 => ['status' => 'Unidentified subscriber', 'meaning' => 'MT number is unknown in the MT network’s MSC'],
      13 => ['status' => 'Barred subscriber', 'meaning' => 'A Barred Number is a number that cannot receive SMS...'],
      9 => ['status' => 'Illegal subscriber', 'meaning' => 'Sender ID Blocked by operators for Illegal SMS Traffic'],
      36 => ['status' => 'SMS Failed', 'meaning' => 'Sender ID Blocked by operators for Illegal SMS Traffic'],
      34 => ['status' => 'System failure', 'meaning' => 'Rejection due to SS7 protocol or network failure'],
      8 => ['status' => 'SMS Failed', 'meaning' => 'Network failure in SMSC Link'],
      400 => ['status' => 'SMSC Timeout-abort', 'meaning' => 'SMSC timeout (Network problem)'],
      456 => ['status' => 'SMSC Timeout-abort', 'meaning' => 'SMSC timeout (Network problem)'],
      8001 => ['status' => 'SRI Response not found', 'meaning' => 'SRI Response not found'],
      9001 => ['status' => 'FSM Response not found', 'meaning' => 'FSM Response not found'],
      1 => ['status' => 'Message sent to Engine', 'meaning' => 'Message sent to Engine'],
      1001 => ['status' => 'Message received at Engine', 'meaning' => 'Message received at Engine'],
      1002 => ['status' => 'Message sent to Queue', 'meaning' => 'Message sent to Queue'],
      9005 => ['status' => 'Wrong encoding', 'meaning' => 'Wrong encoding'],
    ];

    return [
      'code' => $code,
      'status' => $map[$code]['status'] ?? 'SMS Failed',
      'meaning' => $map[$code]['meaning'] ?? 'Network failure in SMSC Link',
    ];
  }

  private function infozillionDlrCallback($user, $messageInfo, $statusCode)
  {
    $status = $statusCode == 0 ? 'Delivered' : 'Undelivered';
    $dlrResult = '';

    for ($key = 0; $key < $messageInfo->smscount; $key++) {
      $parts = explode('-', $messageInfo->sms_uniq_id);
      $last_part = end($parts);
      $incremented = str_pad((int)$last_part + $key, strlen($last_part), '0', STR_PAD_LEFT);
      $parts[count($parts) - 1] = $incremented;
      $new_str = implode('-', $parts);


      Log::channel('socketlisten')->info('DLR_REQUEST_IP: ' . gethostbyname(gethostname()) . ' DLR_USERNAME ' . env('DLR_USERNAME') . ' DLR_PASSWORD: ' . env('DLR_PASSWORD') . ' DLR_URL: ' . env('DLR_URL'));

      try {
        $data = [
          "username" => env('DLR_USERNAME'),
          "password" => env('DLR_PASSWORD'),
          "messageId" => $new_str,
          "status" => $status,
          "errorCode" => "0",
          "mobile" => $messageInfo->destmn,
          "shortMessage" => $messageInfo->message,
          "submitDate" => $messageInfo->write_time,
          "doneDate" => date('Y-m-d H:i:s'),
        ];

        $options = [
          'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
            'ignore_errors' => true
          ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents(env('DLR_URL'), false, $context);
        Log::channel('socketlisten')->info("DLR Response: " . $result);
        $dlrResult = $result ?: '';
      } catch (\Exception $e) {
        Log::channel('socketlisten')->info("DLR Error: " . now() . ' - ' . $e->getMessage());
      }
    }

    return $dlrResult;
  }


  public function handleRetry($messageParts)
  {
    DB::table('retry_request_number')->insert([
      'mobile' => $messageParts[1],
      'retry_at' => now(),
    ]);
  }


  public function handleIncomming($messageParts)
  {
    try {
      $payload = [
        'sender'          => $messageParts[1],
        'operator_prefix' => $messageParts[1] ? $this->getPrefix($messageParts[1]) : '',
        'receiver'        => preg_match('/^4700/', $messageParts[2])
          ? preg_replace('/^4700/', '88', $messageParts[2], 1)
          : $messageParts[2],
        'message'        => $messageParts[3] ?? '',
        'smscount'       => $messageParts[4] ?? 1,
        'part_no'        => $messageParts[5] ?? 1,
        'total_parts'    => $messageParts[6] ?? 1,
        'reference_no'   => $messageParts[7] ?? 0,
        'red'            => $messageParts[8] ?? 0,
      ];

      Inbox::create($payload);

      IncommingMessageSaveApiJob::dispatch($payload);

    } catch (\Exception $e) {
      Log::channel('socketlisten')->error("Error saving inbox message: " . $e->getMessage());
    }
  }


}

<?php

namespace Modules\Messages\App\Trait;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Models\Outbox;
use Illuminate\Support\Facades\DB;
use App\Services\SocketServiceBackup;
use Modules\Messages\App\Trait\SmsCountTrait;

trait RetryRequestHandlerTrait
{
  use SmsCountTrait;

  public function handleRetryRequest($mobile = null)
  {
    Log::channel('sms')->info("HandleRetryRequest:".$mobile);

    if (!empty($mobile)) {
      $outboxMessages = Outbox::whereNotIn('dlr_status_code', [0, 1001, 1002])
        ->where('destmn', $mobile)
        ->where('created_at', '>', '2025-06-21 00:00:00')
        ->get();
    } else {
      $outboxMessages = Outbox::whereNotIn('dlr_status_code', [0, 1001, 1002])
        ->where('created_at', '>', '2025-06-21 00:00:00')
        ->get();
    }

    if (empty($outboxMessages)) {
      return Log::channel('sms')->info("No messages to retry.");
    }

    $retryHistory = [];
    foreach ($outboxMessages as $outboxMessage) {
      $countSmsInfo = $this->countSms($outboxMessage->message);
      if ($outboxMessage->is_unicode) $encoding = 8;
      else $encoding = 0;

      $socketData = [
        'messageId' => (string)$outboxMessage->id,
        'senderId' => $outboxMessage->srcmn,
        'userId' => $outboxMessage->user_id,
        'recepient' => $outboxMessage->destmn,
        'type' => 'single',
        'encoding' => $encoding,
        "parts" => (array)$countSmsInfo->parts,
        "has_gsm_extended" => 0,
        "no_of_sms" => (int)$countSmsInfo->count
      ];

      $dataString = json_encode($socketData) . "\n";
      app(SocketServiceBackup::class)->sendData($dataString);

      $retryHistory[] = [
        'outbox_id' => $outboxMessage->id,
        'mobile' => $outboxMessage->destmn,
        'user_id' => $outboxMessage->user_id,
        'current_dlr_code' => $outboxMessage->dlr_status_code,
        'current_dlr_status' => $outboxMessage->dlr_status,
        'retry_at' => now(),
      ];

      Log::channel('sms')->info("Sent to socket:");
      Log::channel('sms')->info($dataString);
    }

    //save retry history
    if (count($retryHistory) > 0) {
      DB::table('message_retry_history')->insert($retryHistory);
      Log::channel('sms')->info("Retry history saved successfully.");
    }

  }
}

<?php

namespace Modules\Messages\App\Trait;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Models\Outbox;
use Modules\Messages\App\Models\SMSRecord;
use Modules\Users\App\Models\User;

trait ReveApiTrait
{
  public function sendMessageToReveApi($sendMessageId)
  {
    Log::channel('sms')->info("Send Message To Reve Api:");
    $outboxMessages = Outbox::where('reference_id', $sendMessageId)->get();
    foreach ($outboxMessages as $outboxMessage) {
      try {
        $response = Http::get(env('SMS_API_URL'), [
          'apikey' => $outboxMessage->user->user_reve_api_key, //env('SMS_API_KEY'),
          'secretkey' => $outboxMessage->user->user_reve_secret_key, // env('SMS_SECRET_KEY'),
          'content' => json_encode([
            [
              'callerID' => $outboxMessage->mask,
              'toUser' => $outboxMessage->destmn,
              'messageContent' => $outboxMessage->message,
            ]
          ]),
        ]);
      } catch (\Exception $e) {
        //dd($e->getMessage());
        Log::channel('sms')->info($e->getMessage());
      }

      if ($response->successful()) {
        Log::channel('sms')->info("Reve Api Response:");
        Log::channel('sms')->info($response->json());
        try {
          $responsedata = $response->json();
          $messageIds = explode(',', $responsedata['Message_ID']);
          $messageId = $messageIds[0];

          $payload = [
            'user_id' => $outboxMessage->user_id,
            'outbox_id' => $outboxMessage->id,
            'status' => $responsedata['Status'],
            'text' => $responsedata['Text'],
            'message_id' => $messageId,
            'created_at' => now(),
            'updated_at' => now(),
          ];

          SMSRecord::insert($payload);
        } catch (\Exception $e) {
          //dd($e->getMessage());
          Log::channel('sms')->info($e->getMessage());
        }
      }
    }

    return true;
  }
}

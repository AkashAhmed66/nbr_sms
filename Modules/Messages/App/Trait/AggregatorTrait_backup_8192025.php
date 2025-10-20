<?php

namespace Modules\Messages\App\Trait;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Jobs\CheckMessageDeliveryStatusJob;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;

trait AggregatorTrait
{
  public function sendMessageToInfozilion(array $data, $sendMessageId)
  {
    Log::channel('sms')->info("Send Message To InfoZillion:");
    Log::channel('sms')->info("InfoZillion Data:");
    Log::channel('sms')->info(json_encode($data));

    

    if(strlen($data['cli']) && strlen($data['cli']) == 13){
      $apiUrl = env('AGGREGATOR_SMS_SEND_API_URL');
    }else{
      $apiUrl = env('AGGREGATOR_SMS_SEND_MASKING_API_URL');
    }

    $payload = [
      "username" => env('AGGREGATOR_USERNAME'),
      "password" => env('AGGREGATOR_PASSWORD'),
      "billMsisdn" => $data['cli'] ?? env('AGGREGATOR_BILL_MSISDN'),
      "usernameSecondary" => env('AGGREGATOR_SECONDARY_USERNAME'),
      "passwordSecondary" => env('AGGREGATOR_SECONDARY_PASSWORD'),
      "billMsisdnSecondary" => env('AGGREGATOR_SECONDARY_BILL_MSISDN'),
      "apiKey" => env('AGGREGATOR_API_KEY'),
      "cli" => $data['cli'] ?? env('AGGREGATOR_CLI'),
      "msisdnList" => $data['msisdnList'],
      "transactionType" => $data['transactionType'],
      "messageType" => $data['isunicode'] == '1' ? '3' : '1',
      "isLongSMS" => false,
      "message" => $data['message'],
      "campaignId" => $data['campaign_id'],
    ];

    Log::channel('sms')->info(json_encode($payload));

    try {
      $response = Http::post($apiUrl, $payload);
      Log::channel('sms')->info("Infozillion Response:");
      Log::channel('sms')->info($response);
      if ($response->successful()) {

        $responseData = $response->json();
        $orderId = $responseData['serverTxnId'];

        CheckMessageDeliveryStatusJob::dispatch($data, $orderId, $sendMessageId);

        return true;
      } else {
        return false;
      }
    } catch (\Exception $e) {
      //throw new \Exception($e->getMessage());
      return false;
    }
  }

  //Check Delivery
  public function checkDelivery($data, $orderId, $sendMessageId)
  {
    $apiUrl = env('AGGREGATOR_CHECK_DELIVERY_API_URL');
    $payload = [
      "username" => env('AGGREGATOR_USERNAME'),
      "password" => env('AGGREGATOR_PASSWORD'),
      "billMsisdn" => $data['cli'] ?? env('AGGREGATOR_BILL_MSISDN'),
      "usernameSecondary" => env('AGGREGATOR_SECONDARY_USERNAME'),
      "passwordSecondary" => env('AGGREGATOR_SECONDARY_PASSWORD'),
      "billMsisdnSecondary" => env('AGGREGATOR_SECONDARY_BILL_MSISDN'),
      "apiKey" => env('AGGREGATOR_API_KEY'),
      "msisdnList" => $data['msisdnList'],
      "serverReference" => $orderId
    ];

    try {
      $response = Http::post($apiUrl, $payload);

      if ($response->successful()) {
        $responseData = $response->json();

        Log::channel('sms')->info('Delivery data received: ' . json_encode($responseData));

        $sendMessage = Message::find($sendMessageId);

        if (!$sendMessage) {
          Log::channel('sms')->error("Message with ID {$sendMessageId} not found.");
          return false;
        }

        // Safely assign data (in case keys are missing)
        $sendMessage->serverTxnId = $responseData['serverTxnId'] ?? null;
        $sendMessage->serverResponseCode = $responseData['serverResponseCode'] ?? null;
        $sendMessage->serverResponseMessage = $responseData['serverResponseMessage'] ?? null;
        $sendMessage->a2pDeliveryStatus = $responseData['a2pDeliveryStatus'] ?? null;
        $sendMessage->a2pSendSmsBusinessCode = $responseData['a2pSendSmsBusinessCode'] ?? null;
        $sendMessage->deliveryStatus = $responseData['deliveryStatus'] ?? null;
        $sendMessage->dndMsisdn = $responseData['dndMsisdn'] ?? null;
        $sendMessage->invalidMsisdn = $responseData['invalidMsisdn'] ?? null;
        $sendMessage->ansSendSmsHttpStatus = $responseData['ansSendSmsHttpStatus'] ?? null;
        $sendMessage->ansSendSmsBusinessCode = $responseData['ansSendSmsBusinessCode'] ?? null;
        $sendMessage->mnoResponseCode = $responseData['mnoResponseCode'] ?? null;
        $sendMessage->mnoResponseMessage = $responseData['mnoResponseMessage'] ?? null;

        $sendMessage->save();

        // this is the change for the error
        if($responseData){
          foreach($responseData as $phoneStatus){
            if(is_string($phoneStatus)  && $phoneStatus != "") {
              $phoneStatusArray = explode("-", $phoneStatus);
              Outbox::where('reference_id', $sendMessage->id)
                    ->where('destmn', $phoneStatusArray[0])
                    ->update(['dlr_status' => $phoneStatusArray[1] ?? '']);
            }
          }
        }
        return true;
      } else {
        Log::channel('sms')->warning("Delivery status check failed. HTTP Code: {$response->status()} for Message ID: {$sendMessageId}");
        return false;
      }
    } catch (\Exception $e) {
      Log::channel('sms')->error("Exception occurred while checking delivery status: " . $e->getMessage());
      return false;
    }
  }

  //Check ANS Balance
  public function checkAnsBalance()
  {
    $apiUrl = env('AGGREGATOR_CHECK_ANS_BALANCE_API_URL');
    $payload = [
      "username" => env('AGGREGATOR_USERNAME'),
      "password" => env('AGGREGATOR_PASSWORD'),
      "mno" => env('AGGREGATOR_MNO'),
      "apiKey" => env('AGGREGATOR_API_KEY')
    ];

    try {
      $response = Http::post($apiUrl, $payload);
      if ($response->successful()) {
        return $response->json();
      } else {
        return false;
      }
    } catch (\Exception $e) {
      //throw new \Exception($e->getMessage());
      return false;
    }
  }

  //Check CP Balance
  public function checkCpBalance()
  {
    $apiUrl = env('AGGREGATOR_CHECK_CP_BALANCE_API_URL');
    $payload = [
      "apiKey" => env('AGGREGATOR_API_KEY')
    ];

    try {
      $response = Http::post($apiUrl, $payload);
      if ($response->successful()) {
        return $response->json();
      } else {
        return false;
      }
    } catch (\Exception $e) {
      //throw new \Exception($e->getMessage());
      return false;
    }
  }

  //Check CLI
  public function checkCli($cli)
  {
    $apiUrl = env('AGGREGATOR_CLI_API_URL');
    $payload = [
      "username" => env('AGGREGATOR_USERNAME'),
      "password" => env('AGGREGATOR_PASSWORD'),
      "cli" => $cli,
      "apiKey" => env('AGGREGATOR_API_KEY')
    ];

    try {
      $response = Http::post($apiUrl, $payload);
      if ($response->successful()) {
        return $response->json();
      } else {
        return false;
      }
    } catch (\Exception $e) {
      //throw new \Exception($e->getMessage());
      return false;
    }
  }
}

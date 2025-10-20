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

    if (!empty($data['cli']) && strlen($data['cli']) === 13) {
      $apiUrl = env('AGGREGATOR_SMS_SEND_API_URL');

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


      Log::channel('sms')->info("Infozillion nonmasking URL and Payload:");
      Log::channel('sms')->info(json_encode($apiUrl));
      Log::channel('sms')->info(json_encode($payload));

      try {
        $response = Http::post($apiUrl, $payload);
        Log::channel('sms')->info(message: "Infozillion nonmasking Response:");
        Log::channel('sms')->info($response);
        if ($response->successful()) {

          $responseData = $response->json();
          $orderId = $responseData['serverTxnId'];

          $this->messageTableUpdateByInfozillionResponse($responseData, $sendMessageId);

          // CheckMessageDeliveryStatusJob::dispatch($data, $orderId, $sendMessageId);

          return true;
        } else {
          return false;
        }
      } catch (\Exception $e) {
        //throw new \Exception($e->getMessage());
        Log::channel('sms')->error("Exception occurred while sending nonmasking message: " . $e->getMessage());
        return false;
      }

    } else {
      $apiUrl = env('AGGREGATOR_SMS_SEND_MASKING_API_URL');
      $map = [
        'gp' => ['88017', '88013', '017', '013'],
        'bl' => ['88019', '88014', '019', '014'],
        'rb' => ['88018', '88016', '018', '016'],
        'tt' => ['88015', '015'],
      ];

      foreach ($map as $key => $mp) {
        $numbers = [];
        foreach ($mp as $prefix) {
          $filtered = array_filter($data['msisdnList'], function ($number) use ($prefix) {
            return strpos($number, $prefix) === 0;
          });
          $numbers = array_merge($numbers, $filtered);
        }


        $billMsisdn = null;
        $operator_password = null;
        $operator_username = null;

        if ($key == 'gp') {
          $billMsisdn = env('AGGREGATOR_GRAMEENPHONE_BILL_MSISDN');
          $operator_username = env('GRAMEENPHONE_API_USERNAME');
          $operator_password = env('GRAMEENPHONE_API_PASSWORD');
        } else if ($key == 'bl') {
          $billMsisdn = env('AGGREGATOR_BANGLALINK_BILL_MSISDN');
          $operator_username = env('BANGLALINK_API_USERNAME');
          $operator_password = env('BANGLALINK_API_PASSWORD');
        } else if ($key == 'rb') {
          $billMsisdn = env('AGGREGATOR_ROBI_BILL_MSISDN');
          $operator_username = env('ROBI_API_USERNAME');
          $operator_password = env('ROBI_API_PASSWORD');
        } else if ($key == 'tt') {
          $billMsisdn = env('AGGREGATOR_TELETALK_BILL_MSISDN');
          $operator_username = env('TELETALK_API_USERNAME');
          $operator_password = env('TELETALK_API_PASSWORD');
        }

        $payload = [
          "username" => $operator_username,
          "password" => $operator_password,
          "billMsisdn" => $billMsisdn,
          "usernameSecondary" => env('AGGREGATOR_SECONDARY_USERNAME'),
          "passwordSecondary" => env('AGGREGATOR_SECONDARY_PASSWORD'),
          "billMsisdnSecondary" => env('AGGREGATOR_SECONDARY_BILL_MSISDN'),
          "apiKey" => env('AGGREGATOR_API_KEY'),
          "cli" => $data['cli'] ?? env('AGGREGATOR_CLI'),
          "msisdnList" => $numbers,
          "transactionType" => $data['transactionType'],
          "messageType" => $data['isunicode'] == '1' ? '3' : '1',
          "isLongSMS" => false,
          "message" => $data['message'],
          "campaignId" => $data['campaign_id'],
        ];


        if (!empty($numbers)) {
          Log::channel('sms')->info('payload sending to infozilion for masking: ' . json_encode($payload));
          Log::channel('sms')->info(json_encode($apiUrl));
          try {
            $response = Http::post($apiUrl, $payload);
            Log::channel('sms')->info("Infozillion masking Response:");
            Log::channel('sms')->info($response);

            $responseData = $response->json();
            $orderId = $responseData['serverTxnId'];


            $this->messageTableUpdateByInfozillionResponse($responseData, $sendMessageId);

            // CheckMessageDeliveryStatusJob::dispatch($data, $orderId, $sendMessageId);
          } catch (\Exception $e) {
            //throw new \Exception($e->getMessage());
            // return false;
            Log::channel('sms')->error("Exception occurred while sending masking message: " . $e->getMessage());
          }
        }
      }
    }

  }

  //Check Delivery
  public function checkDelivery($data, $orderId, $sendMessageId)
  {
    Log::channel('getDLRLog')->info("Start Check Delivery to InfoZillion:");

    $apiUrl = env('AGGREGATOR_CHECK_DELIVERY_API_URL');
    $payload = [
      "username" => env('AGGREGATOR_USERNAME'),
      "password" => env('AGGREGATOR_PASSWORD'),
      "billMsisdn" => $data['cli'] ?? env('AGGREGATOR_CLI'),
      "usernameSecondary" => env('AGGREGATOR_SECONDARY_USERNAME'),
      "passwordSecondary" => env('AGGREGATOR_SECONDARY_PASSWORD'),
      "billMsisdnSecondary" => env('AGGREGATOR_SECONDARY_BILL_MSISDN'),
      "apiKey" => env('AGGREGATOR_API_KEY'),
      "msisdnList" => $data['msisdnList'],
      "serverReference" => $orderId
    ];

    Log::channel('getDLRLog')->info(message: 'Check DLR sending data to infozillion payload: ' . json_encode($payload));

    try {
      $response = Http::post($apiUrl, $payload);

      Log::channel('getDLRLog')->info("Infozillion check delivery callback Response:");
      Log::channel('getDLRLog')->info($response);

      if ($response->successful()) {
        $responseData = $response->json();

        $this->messageTableUpdateByInfozillionResponse($responseData, $sendMessageId);

      } else {
        Log::channel('getDLRLog')->warning("Delivery status check failed. HTTP Code: {$response->status()} for Message ID: {$sendMessageId}");
        return false;
      }
    } catch (\Exception $e) {
      Log::channel('getDLRLog')->error("Exception occurred while checking delivery status: " . $e->getMessage());
      return false;
    }
  }




  public function messageTableUpdateByInfozillionResponse($responseData, $sendMessageId)
  {

    $sendMessage = Message::find($sendMessageId);

    if (!$sendMessage) {
      Log::channel('getDLRLog')->error("Message with ID {$sendMessageId} not found.");
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


    Log::channel('getDLRLog')->info("After update sentmessage table");


    // this is the change for the error
    if (!empty($responseData['deliveryStatus'])) {

      Log::channel('getDLRLog')->info("DLR response: " . json_encode($responseData['deliveryStatus']));

      foreach ($responseData['deliveryStatus'] as $phoneStatus) {
        if (is_string($phoneStatus) && strpos($phoneStatus, "-") !== false) {
          [$msisdn, $status] = explode("-", $phoneStatus, 2);

          try {
            if (empty($status) || $status == '') {
              $sendMessage->aggregator_dlr_status_updated_from_infozilion = 0;
              $sendMessage->save();
            } else {
              Outbox::where('reference_id', $sendMessage->id)
                ->where('destmn', $msisdn)
                ->update(['dlr_status' => $status]);

              Log::channel('getDLRLog')->info("DLR Updated for Message ID {$sendMessage->id} | {$msisdn} => {$status}");
            }
          } catch (\Exception $e) {
            $sendMessage->aggregator_dlr_status_updated_from_infozilion = 0;
            $sendMessage->save();
            Log::channel('getDLRLog')->error("Error updating Outbox for Message ID {$sendMessage->id} | {$msisdn}: " . $e->getMessage());
          }
        }
      }
    } else {
      $sendMessage->aggregator_dlr_status_updated_from_infozilion = 0;
      $sendMessage->save();
    }

    return true;
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

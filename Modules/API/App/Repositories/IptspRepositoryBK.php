<?php

namespace Modules\API\App\Repositories;

use App\Jobs\InfozillionApiResponseJob;
use App\Services\SocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\API\App\Http\Requests\CheckBalanceRequest;
use Modules\API\App\Trait\ResponseAPI;
use Modules\Messages\App\Models\BalanceTransactionRecord;
use Modules\Messages\App\Models\InfozillionMessage;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Smsconfig\App\Models\BlacklistedKeyword;
use Modules\Smsconfig\App\Models\SenderId;
use Modules\Users\App\Models\User;
use mysql_xdevapi\Exception;

class IptspRepositoryBK implements IptspRepositoryInterface
{
  use ResponseAPI;
  use SmsCountTrait;

  public $whiteListIP = "192.168.89.6";

  public function __construct()
  {
    //$this->socketService = $socketService;
  }

  public function sendMessage(Request $request, $type)
  {
    $startTime = microtime(true);
    //Log::channel('sms')->info("Infozzilion Requesting for: ");
    //Log::channel('sms')->info($request->all());

    //PAYLOAD SAVE TO DATABASE
    $payload['username'] = $request->username;
    $payload['password'] = $request->password;
    $payload['msisdn'] = $request->msisdn;
    $payload['cli'] = $request->cli;
    $payload['message'] = $request->message;
    $payload['clienttransid'] = $request->clienttransid;
    $payload['rn_code'] = $request->rn_code;
    $payload['type'] = $request->type;
    $payload['longSMS'] = $request->longSMS;
    $payload['isLongSMS'] = $request->isLongSMS;
    $payload['dataCoding'] = $request->dataCoding;
    $payload['isUnicode'] = $request->isUnicode;
    $payload['unicode'] = $request->unicode;
    $payload['isFlash'] = $request->isFlash;
    $payload['flash'] = $request->flash;
    $payload['status'] = null;
    $payload['status_desc'] = null;

    try {
      $infozillionMessage = InfozillionMessage::create($payload);
    } catch (\Exception $exception) {
      Log::channel('sms')->error("Error while saving infozillion message: " . $exception->getMessage());
    }


    $isPromotional = $type == 'promo' ? '1' : '0';

    date_default_timezone_set('Asia/Dhaka');

    $requestParamData = json_decode($request->getContent(), true);

    /* if($request->ip() !== $this->whiteListIP)
       return $this->apiResponse(
         $this->IPBlacklistCode,
         $this->IPBlacklistMessage,
         $request->clienttransid,
         [],
         403
       );*/


    //Parameter missing response
    if (
      !$request->has('username') ||
      !$request->has('password') ||
      !$request->has('msisdn') ||
      !$request->has('cli') ||
      !$request->has('message') ||
      !$request->has('clienttransid') ||
      !$request->has('rn_code')
    ) {

      //update infozillion message status
      $infozillionMessage->status = $this->parameterMissingCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->parameterMissingCode,
        $this->parameterMissingMessage,
        $request->clienttransid,
        [],
        400
      );
    }

    if (!preg_match('/^[a-zA-Z0-9-]+$/', $request->clienttransid)) {

      //update infozillion message status
      $infozillionMessage->status = $this->invalidTransactionIdCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->invalidTransactionIdCode,
        $this->invalidTransactionIdMessage,
        $request->clienttransid,
        [],
        400
      );
    }

    if ($isPromotional == 1) $messageLimit = 999;
    else $messageLimit = 1;

    $msisdnList = explode(',', $request->msisdn);
    // MSISDN Limit Exceeded  response

    if (count($msisdnList) > $messageLimit) {

      //update infozillion message status
      $infozillionMessage->status = $this->MSISDNLimitExceededCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->MSISDNLimitExceededCode,
        $this->MSISDNLimitExceededMessage,
        $request->clienttransid,
        [],
        400
      );
    }

    $messageParam = $requestParamData['message'] ?? null;

    if ($messageParam == "" || preg_match('/^\s+$/', $messageParam)) {

      //update infozillion message status
      $infozillionMessage->status = $this->messagebodyInvalidCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->messagebodyInvalidCode,
        $this->messagebodyInvalidMessage,
        $request->clienttransid,
        [],
        400
      );
    }

    //Message lengths exceed response
    if (($request->isUnicode && strlen($request->message) > 67 * 15) || strlen($request->message) > 154 * 15) {

      //update infozillion message status
      $infozillionMessage->status = $this->messagelengthsExceedCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->messagelengthsExceedCode,
        $this->messagelengthsExceedMessage,
        $request->clienttransid,
        [],
        400
      );
    }

    //TPS Limit Exceeded response
    /*    if ($user->tps > 10) {
          return $this->apiResponse(
            $this->TPSLimitExceededCode,
            $this->TPSLimitExceededMessage,
            $request->clienttransid,
            [],
            400
          );
        }*/

    //Number Barred response


    //$user = $this->checkUser($request);
    $user = User::with('wallet')->where('username', $request->username)->first();

    if (!$user) {

      //update infozillion message status
      $infozillionMessage->status = $this->invalidUsernameCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->invalidUsernameCode,
        $this->invalidUsernameMessage,
        $request->clienttransid,
        [],
        403
      );
    }

    if (!Hash::check($request->password, $user->password)) {

      //update infozillion message status
      $infozillionMessage->status = $this->invalidPasswordCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->invalidPasswordCode,
        $this->invalidPasswordMessage,
        $request->clienttransid,
        [],
        403
      );
    }

    //Account Barred response
    if ($user->status == 'INACTIVE') {


      //update infozillion message status
      $infozillionMessage->status = $this->accountBarredCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->accountBarredCode,
        $this->accountBarredMessage,
        $request->clienttransid,
        [],
        403
      );
    }

    if ($user->api_status == 'INACTIVE') {

      //update infozillion message status
      $infozillionMessage->status = $this->APIisNotAllowedForUserCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->APIisNotAllowedForUserCode,
        $this->APIisNotAllowedForUserMessage,
        $request->clienttransid,
        [],
        403
      );
    }


    //CLI/Masking Invalid response
    $validCli = SenderId::where('senderID', $request->cli)->first()?->senderID;
    if (!$validCli) {


      //update infozillion message status
      $infozillionMessage->status = $this->APIisNotAllowedForUserCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->CLIMaskingInvalidCode,
        $this->CLIMaskingInvalidMessage,
        $request->clienttransid,
        [],
        400
      );
    }

    //Invalid MSISDN response
    foreach ($msisdnList as $msisdn) {
      if (!preg_match('/^(8801)[0-9]{9}$/', $msisdn)) {


        //update infozillion message status
        $infozillionMessage->status = $this->invalidMSISDNCode;
        $infozillionMessage->save();

        return $this->apiResponse(
          $this->invalidMSISDNCode,
          $this->invalidMSISDNMessage,
          $request->clienttransid,
          [],
          400
        );
      }
    }


    //DND User respons
    // Check if any of the msisdn numbers exist in the dnds table
    $dndNumbers = DB::table('dnds')->whereIn('phone', $msisdnList)->pluck('phone')->toArray();

    if (!empty($dndNumbers)) {


      //update infozillion message status
      $infozillionMessage->status = $this->DNDUserCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->DNDUserCode,
        $this->DNDUserMessage,
        $request->clienttransid,
        [],
        403
      );
    }


    //No Live Campaign response
    /*    if ($request->campaign_name == 'INACTIVE') {
          return $this->apiResponse(
            $this->noLiveCampaignCode,
            $this->noLiveCampaignMessage,
            $request->clienttransid,
            [],
            403
          );
        }*/

    //Messagebody Invalid response
    /* $keywords = BlacklistedKeyword::where('user_id', $user->id)->value('keywords');

     if ($keywords && $this->compareSentences($request->message, $keywords)) {
       return $this->apiResponse(
         $this->messagebodyInvalidCode,
         $this->messagebodyInvalidMessage,
         $request->clienttransid,
         [],
         400
       );
     }*/


    //Allowed campaigns limit exceeded response
    /*    if ($user->campaigns > 10) {
          return $this->apiResponse(
            $this->alloweDcampaignsLimitExceededCode,
            $this->alloweDcampaignsLimitExceededMessage,
            $request->clienttransid,
            [],
            400
          );
        }*/

    //Allowed SMS quota is completed response
    /*    if ($user->sms_quota == 0) {
          return $this->apiResponse(
            $this->allowedSMSQuotaIsCompletedCode,
            $this->allowedSMSQuotaIsCompletedMessage,
            $request->clienttransid,
            [],
            400
          );
        }*/


    //CHECK USER BALANCE IS SUFFICIENT
    $totalMessage = $this->countSms($request->message)->count;
    $recipientList = explode(',', $request->msisdn);
    $totalRecipient = count($recipientList);
    $totalCost = doubleval(@$user->smsRate->nonmasking_rate * intval($totalMessage * $totalRecipient));
    if (($totalCost) > doubleval(@$user->available_balance)) {


      //update infozillion message status
      $infozillionMessage->status = $this->insufficientBalanceCode;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->insufficientBalanceCode,
        $this->insufficientBalanceMessage,
        $request->clienttransid,
        []
      );
    }

    //Duplicate Transaction ID response
    /*$duplicateTransactionId = Message::where('client_transaction_id', $request->clienttransid)->first();
    if ($duplicateTransactionId) {


      //update infozillion message status
      $infozillionMessage->status = $this->duplicateTransactionIDCode;
      $infozillionMessage->save();


      return $this->apiResponse(
        $this->duplicateTransactionIDCode,
        $this->duplicateTransactionIDMessage,
        $request->clienttransid,
        [],
        400
      );
    }*/


    InfozillionApiResponseJob::dispatch($request->all(), $user, $isPromotional);

    try {
      $infozillionMessage->status = $this->successCode;
      $infozillionMessage->smsc_to_infozillion_response_time = now();
      $infozillionMessage->save();

      $messageIDs = [];
      foreach ($recipientList as $key => $destmn) {
        $messageDateTime = date('YmdHis');
        $prefix = getenv('MESSAGE_PREFIX');
        for ($i = 1; $i <= $totalMessage; $i++) {
          $messageIDs[trim($destmn)][] = $prefix . ' ' .
            $messageDateTime . '-' .
            trim($destmn) . '-' . sprintf('%010d', $i);
        }
      }

      $endTime = microtime(true);

      Log::channel('sms')->info("Infozillion API Response Time: " . ($endTime - $startTime) . "micro seconds");

      return $this->apiResponse(
        $this->successCode,
        $this->successMessage,
        $request->clienttransid,
        [],
        200,
        $messageIDs
      );
    } catch (\Exception $e) {
      $infozillionMessage->status = $this->internalServerErrorCode;
      $infozillionMessage->smsc_to_infozillion_response_time = now();
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->internalServerErrorCode,
        $this->internalServerErrorMessage,
        $request->clienttransid,
        [],
        400
      );
    }


    //$messageIDs = $this->send($request, $user, $isPromotional);

/*    if ($messageIDs != null) {

      //update infozillion message status
      $infozillionMessage->status = $this->successCode;
      $infozillionMessage->status_desc = $this->successMessage;
      $infozillionMessage->save();

      try {

        $messageTable['smsc_to_infozillion_response_status'] = 'success';
        $messageTable['smsc_to_infozillion_response_time'] = now();

        Log::channel('sms')->info("smsc to infozillion success response time: " . $messageTable['smsc_to_infozillion_response_time']);

        //update Message model
        Message::where('id', $messageIDs['message_id'])
          ->update($messageTable);

        return $this->apiResponse(
          $this->successCode,
          $this->successMessage,
          $request->clienttransid,
          [],
          200,
          $messageIDs['message_formated_ids']
        //$messageIDs
        );
      } catch (\Exception $e) {
        //update infozillion message status
        $infozillionMessage->status = $this->internalServerErrorCode;
        $infozillionMessage->status_desc = $this->internalServerErrorMessage;
        $infozillionMessage->save();


        $messageTable['smsc_to_infozillion_response_status'] = 'failed';
        $messageTable['smsc_to_infozillion_response_time'] = now();

        Log::channel('sms')->info("smsc to infozillion failed response time: " . $messageTable['smsc_to_infozillion_response_time']);

        //update Message model
        Message::where('id', $messageIDs['message_id'])
          ->update($messageTable);

        return $this->apiResponse(
          $this->internalServerErrorCode,
          $this->internalServerErrorMessage,
          $request->clienttransid,
          [],
          400
        );
      }


    } else {

      //update infozillion message status
      $infozillionMessage->status = $this->internalServerErrorCode;
      $infozillionMessage->status_desc = $this->internalServerErrorMessage;
      $infozillionMessage->save();

      return $this->apiResponse(
        $this->internalServerErrorCode,
        $this->internalServerErrorMessage,
        $request->clienttransid,
        [],
        400
      );
    }*/
  }

  function send(Request $request, User $user, $isPromotional)
  {
    Log::channel('sms')->info("Send Message: ");
    try {
      DB::beginTransaction();
      $totalMessage = $this->countSms($request->message)->count;
      $recipientList = explode(',', $request->msisdn);
      $totalRecipient = count($recipientList);

      //$requestIp = \Request::getClientIp() ?? "";
      $orderId = $user->id . $this->microseconds();

      //PREPARE PAYLOAD FOR SEND MESSAGE TABLE
      $payload = [
        "user_id" => $user->id,
        "recipient" => $request->msisdn,
        "senderID" => $request->cli,
        "message" => $request->message,
        "client_transaction_id" => $request->clienttransid,
        "orderid" => $orderId,
        "rn_code" => strrev($request->rn_code),
        "type" => $request->type ?? '',
        "long_sms" => $request->message ?? '',
        "is_long_sms" => $request->isLongSMS ?? false,
        "is_flash" => $request->isFlash ?? false,
        "flash" => $request->flash ?? '',
        "date" => date('Y-m-d H:i:s'),
        "source" => 'IPTSP',
        "sms_count" => $totalMessage,
        "IP" => $requestIp ?? "",
        "sms_type" => 'sendSms',
        "file" => '',
        "is_unicode" => $request->isUnicode ?? false,
        "unicode" => $request->unicode ?? '',
        "data_coding" => $request->dataCoding ?? '',
        "status" => 'Queue',
        "total_recipient" => $totalRecipient,
        "is_promotional" => $isPromotional,
        "template_type" => $request->template_type ?? '1',
      ];

      //SAVE TO SEND MESSAGE TABLE
      $sendMessage = Message::create($payload);

      $priority = $this->getPriority($recipientList);
      //PREPARE OUTBOX PAYLOAD
      $outboxPayloadArray = [];
      $messageIDs = [];
      foreach ($recipientList as $key => $destmn) {

        $messageDateTime = date('YmdHis');
        $prefix = getenv('MESSAGE_PREFIX');
        $messageTimestamp = hrtime(true);

        $outboxPayload = [
          "srcmn" => $request->cli,
          "mask" => $request->cli,
          "destmn" => trim($destmn),
          "message" => $request->message,
          "country_code" => null,
          "operator_prefix" => strrev($request->rn_code),
          "status" => 'Queue',
          "write_time" => date('Y-m-d H:i:s'),
          "sent_time" => null,
          "ton" => 5,
          "npi" => 1,
          "message_type" => 'text',
          "is_unicode" => $request->isUnicode ?? false,
          "smscount" => $totalMessage,
          "esm_class" => '',
          "data_coding" => $request->dataCoding ?? '',
          "reference_id" => $sendMessage->id,
          "last_updated" => date('Y-m-d H:i:s'),
          "schedule_time" => null,
          "retry_count" => 0,
          "user_id" => $user->id,
          "remarks" => '',
          "uuid" => hex2bin(str_replace('-', '', Str::uuid()->toString())),
          "priority" => $priority,
          "blocked_status" => null,
          "created_at" => date('Y-m-d H:i:s'),
          "updated_at" => date('Y-m-d H:i:s'),
          "error_code" => null,
          "error_message" => null,
          "sms_cost" => doubleval(@$user->smsRate->nonmasking_rate),
          "sms_uniq_id" => $prefix . " " . $messageDateTime . '-' . trim($destmn) . '-' . '0000000001',
          "rn_code" => strrev($request->rn_code),
          "type" => $request->type
        ];

        $outboxPayloadArray[] = $outboxPayload;

        for ($i = 1; $i <= $totalMessage; $i++) {
          $messageIDs[trim($destmn)][] = $prefix . ' ' .
            $messageDateTime . '-' .
            trim($destmn) . '-' . sprintf('%010d', $i);
        }
      }

      //SAVE OUTBOX TABLE
      Outbox::insert($outboxPayloadArray);

      $totalCostForAllMessage = doubleval(@$user->smsRate->nonmasking_rate * intval($totalMessage * $totalRecipient));
      $user->available_balance -= $totalCostForAllMessage;
      $user->save();

      /* if (Outbox::insert($outboxPayloadArray)) {
        Log::channel('sms')->info("Before call sendMessageToSocket");
        $this->sendMessageToSocket($payload, $sendMessage->id);
      }*/

      DB::commit();

      return [
        'message_id' => $sendMessage->id,
        'message_formated_ids' => $messageIDs,
      ];

      //return $messageIDs;
    } catch (\Exception $e) {
      DB::rollBack();
      // dd($e->getMessage());
      //Log::channel('sms')->info($e->getMessage());

    }
    return null;
  }


  function compareSentences($sentence1, $sentence2)
  {
    // Convert sentences to lowercase and remove punctuation for better matching.
    $sentence1 = strtolower(preg_replace("/[^\w\s]/", "", $sentence1));
    $sentence2 = strtolower(preg_replace("/[^\w\s]/", "", $sentence2));

    // Split the sentences into arrays of words.
    $words1 = explode(" ", $sentence1);
    $words2 = explode(" ", $sentence2);

    // Remove empty elements (in case of multiple spaces).
    $words1 = array_filter($words1, fn($word) => !empty($word));
    $words2 = array_filter($words2, fn($word) => !empty($word));

    // Find common words.
    $common = array_intersect($words1, $words2);

    if (!empty($common)) {
      return false;
    } else {
      return true;
    }
  }


  public function checkUser($request)
  {
    $user = User::where('username', $request->username)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
      return null;
    } else {
      return User::with('wallet')->where('username', $request->username)->first();
    }
  }

  public function microseconds()
  {
    $mt = explode(' ', microtime());
    return intval($mt[1] * 1E6) + intval(round($mt[0] * 1E6));
  }

  public function checkBalance(Request $request)
  {
    /*if($request->ip() !== $this->whiteListIP)
      return $this->checkBalanceResponse(
        $this->IPBlacklistCode,
        $this->IPBlacklistMessage,
        $request->clienttransid,
        ['availablebalance' => null],
        403
      );*/

    /*  return $this->checkBalanceResponse(
        $this->internalServerErrorCode,
        $this->internalServerErrorMessage,
        $request->clienttransid,
        ['availablebalance' => null],
        400
      );*/

    /*
      return $this->checkBalanceResponse(
      $this->invalidParameterCode,
      $this->invalidParameterMessage,
      $request->clienttransid,
      ['availablebalance' => null],
      400
    );*/

    //Parameter missing response
    if (
      !$request->has('username') || is_null($request->username) || $request->username === '' ||
      !$request->has('password') || is_null($request->password) || $request->password === '' ||
      !$request->has('clienttransid') || is_null($request->clienttransid) || $request->clienttransid === ''
    ) {
      return $this->checkBalanceResponse(
        $this->parameterMissingCode,
        $this->parameterMissingMessage,
        $request->clienttransid,
        ['availablebalance' => null],
        400
      );
    }

    if (!preg_match('/^[a-zA-Z0-9-]+$/', $request->clienttransid)) {
      return $this->checkBalanceResponse(
        $this->invalidTransactionIdCode,
        $this->invalidTransactionIdMessage,
        $request->clienttransid,
        ['availablebalance' => null],
        400
      );
    }

    //Duplicate Transaction ID response
    $duplicateTransactionId = BalanceTransactionRecord::where('client_trans_id', $request->clienttransid)->first();
    if ($duplicateTransactionId) {
      return $this->checkBalanceResponse(
        $this->duplicateTransactionIDCode,
        $this->duplicateTransactionIDMessage,
        $request->clienttransid,
        ['availablebalance' => null],
        400
      );
    }


    $user = User::with('wallet')->where('username', $request->username)->first();

    if (!$user) {
      return $this->checkBalanceResponse(
        $this->invalidUsernameCode,
        $this->invalidUsernameMessage,
        $request->clienttransid,
        ['availablebalance' => null],
        403
      );
    }

    if (!Hash::check($request->password, $user->password)) {
      return $this->checkBalanceResponse(
        $this->invalidPasswordCode,
        $this->invalidPasswordMessage,
        $request->clienttransid,
        ['availablebalance' => null],
        403
      );
    }

    //Account Barred response
    if ($user->status == 'INACTIVE') {
      return $this->checkBalanceResponse(
        $this->accountBarredCode,
        $this->accountBarredMessage,
        $request->clienttransid,
        ['availablebalance' => null],
        403
      );
    }


    //API is not allowed for user response
    if ($user->api_status == 'INACTIVE') {
      return $this->checkBalanceResponse(
        $this->APIisNotAllowedForUserCode,
        $this->APIisNotAllowedForUserMessage,
        $request->clienttransid,
        ['availablebalance' => null],
        403
      );
    }

    //Invalid Transaction Id response
    if (strpos($request->clienttransid, ' ') !== false) {
      return $this->checkBalanceResponse(
        $this->invalidTransactionIdCode,
        $this->invalidTransactionIdMessage,
        $request->clienttransid,
        ['availablebalance' => null],
        400
      );
    }

    //SAVE TO BALANCE TRANSACTION TABLE
    BalanceTransactionRecord::insert([
      "user_id" => $user->id,
      "client_trans_id" => $request->clienttransid,
      "created_at" => date('Y-m-d H:i:s'),
    ]);

    return $this->checkBalanceResponse(
      $this->successCode,
      $this->successMessage,
      $request->clienttransid,
      ['availablebalance' => number_format(@$user->available_balance, 2, '.')]
    );
  }
}

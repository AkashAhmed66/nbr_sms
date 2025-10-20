<?php

namespace Modules\API\App\Repositories;

use App\Jobs\CreateInfozillionMessageJob;
use App\Jobs\InfozillionApiResponseJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\API\App\Trait\ResponseAPI;
use Modules\Messages\App\Models\BalanceTransactionRecord;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Smsconfig\App\Models\SenderId;
use Modules\Users\App\Models\User;
use Modules\Messages\App\Models\InfozillionMessage;

class LoadTestRepository implements LoadTestRepositoryInterface
{
  use ResponseAPI;
  use SmsCountTrait;

  public function sendMessage(Request $request, $type)
  {
    Log::channel('sms')->info("API request at  : " . now());

    $payload = $request->only(['username', 'password', 'msisdn', 'cli', 'message', 'clienttransid']);

    $InfozillionMessage = InfozillionMessage::create($payload);

    $validator = Validator::make($request->all(), [
      'username' => ['bail', 'required', 'exists:users,username'],
      'password' => ['bail', 'required', 'string'],
      'msisdn' => ['bail', 'required', 'string'],
      'cli' => ['bail', 'required', 'string'],
      'message' => ['bail', 'required', 'string', function ($attribute, $value, $fail) {
        if (trim($value) === '') {
          $fail('The message body is invalid.');
        }
      }],
      'clienttransid' => ['bail', 'required', 'regex:/^[a-zA-Z0-9-]+$/'],
      'rn_code' => ['bail', 'required'],
    ], [
      'username.required' => 'Username is required.',
      'username.exists' => 'Invalid username.',
      'password.required' => 'Password is required.',
      'msisdn.required' => 'MSISDN is required.',
      'cli.required' => 'CLI is required.',
      'message.required' => 'Message is required.',
      'clienttransid.required' => 'Client Transaction ID is required.',
      'clienttransid.regex' => 'Client Transaction ID format is invalid.',
      'rn_code.required' => 'Routing code is required.',
    ]);

    if ($validator->fails()) {
      $payload = $request->only(['username', 'password', 'msisdn', 'cli', 'message', 'clienttransid']);
      //$payload['status'] = 400;
      //$payload['status_desc'] = $validator->errors()->first();

      //CreateInfozillionMessageJob::dispatch($payload);
      // update the InfozillionMessage with error status
      $InfozillionMessage->update([
        'status' => 400,
        'status_desc' => $validator->errors()->first(),
        'updated_at' => now(),
      ]);

      return $this->apiResponse(
        400,
        $validator->errors()->first(),
        $request->clienttransid,
        [],
        400
      );
    }

    $user = User::where('username', $request->username)->first();

    if (!Hash::check($request->password, $user->password)) {
      return $this->errorResponse($request, 'Invalid password.', $this->invalidPasswordCode);
    }

    if ($user->status == 'INACTIVE') {
      return $this->errorResponse($request, 'User account is barred.', $this->accountBarredCode);
    }

    if ($user->api_status == 'INACTIVE') {
      return $this->errorResponse($request, 'API access is not allowed for this user.', $this->APIisNotAllowedForUserCode);
    }

    $validCli = SenderId::where('senderID', $request->cli)->exists();
    if (!$validCli) {
      return $this->errorResponse($request, 'Invalid CLI/Masking.', $this->CLIMaskingInvalidCode);
    }

    $msisdnList = explode(',', $request->msisdn);
    $isPromotional = $type === 'promo';
    $messageLimit = $isPromotional ? 999 : 1;

    if (count($msisdnList) > $messageLimit) {
      return $this->errorResponse($request, 'MSISDN limit exceeded.', $this->MSISDNLimitExceededCode);
    }

/*    foreach ($msisdnList as $msisdn) {
      if (!preg_match('/^(8801)[0-9]{9}$/', trim($msisdn))) {
        return $this->errorResponse($request, 'Invalid MSISDN format.', $this->invalidMSISDNCode);
      }
    }*/

    $dndNumbers = DB::table('dnds')->whereIn('phone', $msisdnList)->pluck('phone')->toArray();
    if (!empty($dndNumbers)) {
      return $this->errorResponse($request, 'One or more recipients are in DND list.', $this->DNDUserCode);
    }

    $totalMessage = $this->countSms($request->message)->count;
    $totalRecipient = count($msisdnList);
    $totalCost = doubleval($user->smsRate->nonmasking_rate * $totalMessage * $totalRecipient);

    if ($totalCost > doubleval($user->available_balance)) {
      return $this->errorResponse($request, 'Insufficient balance.', $this->insufficientBalanceCode);
    }

    $duplicate = Message::where('client_transaction_id', $request->clienttransid)->exists();
    if ($duplicate) {
      return $this->errorResponse($request, 'Duplicate Transaction ID.', $this->duplicateTransactionIDCode);
    }

    //InfozillionApiResponseJob::dispatch($request->all(), $user, $isPromotional);
    $InfozillionMessage->update([
      'status' => 400,
      'status_desc' => $validator->errors()->first(),
      'updated_at' => now(),
      'smsc_to_infozillion_response_time' => now()
    ]);

    $messageIDs = [];
    $recipientList = explode(',', $request->msisdn);
    foreach ($recipientList as $key => $destmn) {
      $messageDateTime = date('YmdHis');
      $prefix = getenv('MESSAGE_PREFIX');
      for ($i = 1; $i <= $totalMessage; $i++) {
        $messageIDs[trim($destmn)][] = $prefix . ' ' .
          $messageDateTime . '-' .
          trim($destmn) . '-' . sprintf('%010d', $i);
      }
    }

    Log::channel('sms')->info("API response at  : " . now());

    return $this->apiResponse(
      $this->successCode,
      $this->successMessage,
      $request->clienttransid,
      [],
      200,
      $messageIDs
    );
  }

  private function errorResponse($request, $message, $code)
  {
    $payload =[
      'username' => $request->username,
      'password' => $request->password,
      'msisdn' => $request->msisdn,
      'cli' => $request->cli,
      'message' => $request->message,
      'clienttransid' => $request->clienttransid,
      'status' => $code,
      'status_desc' => $message,
      'created_at' => now(),
      'updated_at' => now(),
      'smsc_to_infozillion_response_time' => now()
    ];

    CreateInfozillionMessageJob::dispatch($payload);

    return $this->apiResponse($code, $message, $request->clienttransid, [], 400);
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

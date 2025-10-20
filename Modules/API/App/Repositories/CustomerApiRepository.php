<?php

namespace Modules\API\App\Repositories;

use App\Jobs\SendMessageToInfozillionJob;
use App\Services\SocketServiceBackup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;
use Modules\Messages\App\Models\OutboxHistory;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Smsconfig\App\Models\Mask;
use Modules\Smsconfig\App\Models\SenderId;
use Modules\Users\App\Models\User;

class CustomerApiRepository implements CustomerApiRepositoryInterface
{
  use SmsCountTrait;

  public function __construct(Message $message, Outbox $outbox, OutboxHistory $outboxHistory, SocketServiceBackup $socketService)
  {
    $this->message = $message;
    $this->outbox = $outbox;
    $this->outboxHistory = $outboxHistory;
    $this->socketService = $socketService;
    $this->user = Auth::user();
  }

  public function sendMessage($request, $userInfo)
  {
    try {
      $phoneNumbersString = $this->filterPhoneNumbers($request->numbers);

      if (empty($phoneNumbersString)) {
        return [
          'error' => true,
          'error_message' => 'No recipient found',
          'error_code' => 1010,
        ];
      }

      $senderIds = SenderId::pluck('senderID')->toArray();

      if($userInfo->id_user_group != 1){
        $users = User::where('created_by', $userInfo->id)->pluck('id')->toArray();
        $users[] = $userInfo->id;
        $senderIds = SenderId::whereIn('user_id', $users)->pluck('senderID')->toArray();
      }
      
      $masks = Mask::pluck('mask')->toArray();
      
      if($userInfo->id_user_group != 1){
        $users = User::where('created_by', $userInfo->id)->pluck('id')->toArray();
        $users[] = $userInfo->id;
        $masks = Mask::whereIn('user_id', $users)->pluck('mask')->toArray();
      }

      // dd(!in_array($request->senderid, $senderIds), !in_array($request->senderid, $masks));
      if (!in_array($request->senderid, $senderIds) and !in_array($request->senderid, $masks)) {
        return [
          'error' => true,
          'error_message' => 'Sender ID not found',
          'error_code' => 1002 ,
        ];
      }


      $phoneNumbersArray = explode(',', $phoneNumbersString);
      $totalPhoneNumber = count($phoneNumbersArray);
      $message = $request->msg;
      $smsInfo = $this->countSms($message);
      $countSms = $smsInfo->count;
      $currentBalance = User::where('id', $userInfo->id)->first();
      $smsRate = DB::table('rates')->where('id', $currentBalance->sms_rate_id)->first();

      if (strlen($request->senderid) == 13) {
        $isMasking = 0;
        $balance = ($countSms * $totalPhoneNumber) * $smsRate->nonmasking_rate;
      } else {
        $isMasking = 1;
        $balance = ($countSms * $totalPhoneNumber) * $smsRate->masking_rate;
      }

      if ($balance > $currentBalance->available_balance) {
        return [
          'error' => true,
          'error_message' => 'Insufficient balance',
          'error_code' => 1020,
        ];

      }

      $isUnicode = $this->getSMSType($message) == 'unicode' ? 1 : 0;

      $sendMessage = null;
      foreach($phoneNumbersArray as $number) {
        $orderid = $userInfo->id . $this->microseconds();
        $api_data = array(
          'user_id' => $userInfo->id,
          'message' => $message,
          'recipient' => $number,
          'senderID' => $request->isMethod('get') ? $request->senderid : "",
          'date' => date('Y-m-d H:i:s'),
          'source' => 'API',
          'sms_count' => $smsInfo->count,
          //'IP' => \http\Env\Request::getClientIp(),
          'sms_type' => 'sendSms',
          'orderid' => $orderid,
          'file' => null,
          'is_unicode' => $isUnicode,
          'status' => 'Queue',
          'total_recipient' => 1,
          'template_type' => 2
        );

        $sendMessage = Message::create($api_data);
        $apiResponse = $this->saveToOutbox($number, $message, $request->senderid, $smsInfo->count, $userInfo->id, $sendMessage->id, $isMasking, $isUnicode);
        /*if ($apiResponse === true) {
          return [
            'error' => false,
            'message' => 'Messages sent successfully',
            'message_id' => $sendMessage->orderid
          ];

        } else {
          return [
            'error' => true,
            'error_message' => 'Failed to send messages',
            'error_code' => 1016
          ];
        }*/
      }

      return [
        'error' => false,
        'message' => 'Messages sent successfully',
        'message_id' => $sendMessage->orderid
      ];



    } catch (\Exception $e) {
      return [
        'error' => true,
        'error_message' => 'An unexpected error occurred: ' . $e->getMessage(),
        'error_code' => 1015,
      ];
    }
  }


  public function saveToOutbox($destmn, $message, $senderID, $totalMessage, $userId, $sendMessageId, $isMasking, $isUnicode)
  {
    date_default_timezone_set('Asia/Dhaka');
    //$recipientList = explode(",", $recipients);
    $priority = $this->getPriority([$destmn]);
    //$outboxPayloadArray = [];
    $user = User::where('id', $userId)->first();

    if ($isMasking == 1) {
      $totalMessageCost = doubleval(@$user->smsRate->masking_rate * $totalMessage);
    } else {
      $totalMessageCost = doubleval(@$user->smsRate->nonmasking_rate * $totalMessage);
    }

    $commaSeparated = '';
    //foreach ($recipientList as $key => $destmn) {
      //$operatorName = $this->getOperatorName($destmn);
      $outboxPayload = [
        "srcmn" => $senderID,
        "mask" => $senderID,
        "destmn" => trim($destmn),
        //"operator_name" => $operatorName,
        "message" => $message,
        "country_code" => NULL,
        "operator_prefix" => $this->getPrefix($destmn),
        "status" => 'Queue',
        "write_time" => date('Y-m-d H:i:s'),
        "sent_time" => NULL,
        "ton" => 5,
        "npi" => 1,
        "message_type" => 'text',
        "is_unicode" => $isUnicode,
        "smscount" => $totalMessage,
        "esm_class" => '',
        "data_coding" => $isUnicode == 1 ? 8 : 0, // 8 for Unicode, 0 for GSM
        "reference_id" => $sendMessageId,
        "last_updated" => date('Y-m-d H:i:s'),
        "schedule_time" => NULL,
        "retry_count" => 0,
        "user_id" => $userId,
        "remarks" => '',
        "uuid" => hex2bin(str_replace('-', '', Str::uuid()->toString())),
        "priority" => $priority,
        "blocked_status" => NULL,
        "created_at" => date('Y-m-d H:i:s'),
        "updated_at" => date('Y-m-d H:i:s'),
        "error_code" => NULL,
        "error_message" => NULL,
        // "dlr_status_code" => null,
        "dlr_status" => null,
        "dlr_status_meaning" => null,
        "sms_cost" => $totalMessageCost,
        "sms_uniq_id" => "MNBL " . date('Ymdhis') . '-' . trim($destmn) . '-' . '0000000001',  //module division by 999999999
      ];

      $outbox = Outbox::create($outboxPayload);
      $outbox_id = $outbox->id;

      //Balance deduction
      $user->available_balance -= $totalMessageCost;
      $user->save();


      if (env('APP_TYPE') == 'Aggregator') {
        // if(($isMasking == '1')){
        //   $company = "grameenphone"; //Will be changed later
        //   $recipients = [$destmn];
        //   $message = $message;
        //   $messageType = 1;
        //   $cli = $senderID;
        //   if($outbox->is_unicode == '1'){
        //     $messageType = 3;
        //   }
        //   SendMaskedMessageJob::dispatch($company, $recipients, $message, $messageType, $cli);
        // } else {

          if (!str_starts_with($destmn, '88')) {
            if (str_starts_with($destmn, '+88')) {
              $destmn = substr($destmn, 1); // remove '+' only
            } else {
              $destmn = '88' . $destmn;
            }
          }

          $numbers = [$destmn];

          $infozillion_array = array('msisdnList' => $numbers, 'message' => $message, 'campaign_id' => "100001");
          $infozillion_array['transactionType'] = count($numbers) > 1 ? 'P' : 'T';
          // $infozillion_array['transactionType'] = 'T';
          $infozillion_array['cli'] = $senderID;
          $infozillion_array['isunicode'] = $isUnicode;

          //$this->sendMessageToInfozilion($infozillion_array);
          SendMessageToInfozillionJob::dispatch($infozillion_array, $sendMessageId);
        // }
      }
    //}

  return true;

  }

  public function filterPhoneNumbers($recipientNumbers)
  {
    $prefixArray = ['88017', '88019', '88016', '88015', '88013', '88014', '88018', '017', '019', '016', '015', '013', '014', '018'];

    $phoneNumbers = explode(',', $recipientNumbers);
    $phoneNumbers = array_filter(array_map('trim', $phoneNumbers));

    $valid13Numbers = [];
    $valid11Numbers = [];

    foreach ($phoneNumbers as $number) {

      if (strlen($number) == 13) {
        $prefix = substr($number, 0, 5);
        if (in_array((int) $prefix, $prefixArray)) {
          $valid13Numbers[] = $number;
        }
      }
    }

    foreach ($phoneNumbers as $number) {
      if (strlen($number) == 11) {
        $prefix = substr($number, 0, 3);
        if (in_array((int) $prefix, $prefixArray)) {
          $valid11Numbers[] = $number;
        }
      }
    }

    $phoneNumbersArray = array_merge($valid11Numbers, $valid13Numbers);
    $phoneNumbersString = implode(",", $phoneNumbersArray);
    return $phoneNumbersString;
  }

  public function getPrefix($destmn)
  {
    try {
      $pattern = '/^\+?88/';
      $mobileNumber = preg_replace($pattern, '', $destmn);

      $prefixes = array(
        '017' => '17',
        '018' => '18',
        '019' => '19',
        '015' => '15',
        '016' => '18',
        '013' => '17',
        '014' => '19'
      );
      $prefix = substr($mobileNumber, 0, 3);
      if (array_key_exists($prefix, $prefixes)) {
        return $prefixes[$prefix];
      } else {
        return '00';
      }
    } catch (\Exception $exception) {
      return '00';
    }

  }

  //Check operator
  function getOperatorName($phoneNumber)
  {
    $prefixes = [
      '88017' => 'GP',
      '017' => 'GP',
      '88013' => 'GP',
      '013' => 'GP',
      '88018' => 'Robi',
      '018' => 'Robi',
      '88019' => 'Banglalink',
      '019' => 'Banglalink',
      '88014' => 'Banglalink',
      '014' => 'Banglalink',
      '88016' => 'Airtel',
      '016' => 'Airtel',
      '88015' => 'Teletalk',
      '015' => 'Teletalk',
    ];

    foreach ($prefixes as $prefix => $operator) {
      if (str_starts_with($phoneNumber, $prefix)) {
        return $operator;
      }
    }

    return 'Unknown';
  }

  public function getBalance($userId)
  {
  }

  public function getDLR()
  {
    // Get DLR
  }

  public function getKey()
  {
    // Get key
  }

  public function getUnreadReplies()
  {
    // Get unread replies
  }

  private function microseconds(): int
  {
    $mt = explode(' ', microtime());
    return intval($mt[1] * 1E6) + intval(round($mt[0] * 1E6));
  }

  function getSMSType(string $usrSms): string
  {
    $usrSms = trim($usrSms);

    // Check for Unicode characters (non-ASCII)
    if (preg_match('/[^\x00-\x7F]+/', $usrSms)) {
      return 'unicode';
    }

    // Check for GSM extended characters
    if (preg_match('/(\x0C|\^|\{|\}|\\\\|\[|~|\]|\||€)/u', $usrSms)) {
      return 'gsmextended';
    }

    // Default to plain text
    return 'plaintext';
  }
}

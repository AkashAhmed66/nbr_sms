<?php

namespace Modules\API\App\Repositories;

use App\Services\SocketService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\API\App\Repositories\CustomerApiRepositoryInterface;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;
use Modules\Messages\App\Models\OutboxHistory;
use Modules\Messages\App\Models\SMSRecord;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Users\App\Models\User;
use Modules\SmsConfig\App\Models\Rate;

class CustomerApiRepository implements CustomerApiRepositoryInterface
{
  use SmsCountTrait;

  public function __construct(Message $message, Outbox $outbox, OutboxHistory $outboxHistory, SocketService $socketService)
  {
    $this->message = $message;
    $this->outbox = $outbox;
    $this->outboxHistory = $outboxHistory;
    $this->socketService = $socketService;
    $this->user = Auth::user();
  }

  private function microseconds(): int
  {
    $mt = explode(' ', microtime());
    return intval($mt[1] * 1E6) + intval(round($mt[0] * 1E6));
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

        $totalPhoneNumber = count(explode(',', $phoneNumbersString));

        $message = $request->msg;
        //$recipients = $request->numbers;
        //$totalNumber = count(explode(",", $recipients));
        $smsInfo = $this->countSms($message);

        $countSms = $smsInfo->count;
        //$totalMessage = $totalNumber * $countSms;

        $currentBalance = User::where('id',  $userInfo->id)->first();
        $smsRate = DB::table('rates')->where('id', $currentBalance->sms_rate_id)->first();
        //$smsRate = Rate::where('id', $currentBalance->sms_rate_id)->first();
        $balance = ($countSms * $totalPhoneNumber) * $smsRate->nonmasking_rate;
        //dd($countSms);

        if($balance > $currentBalance->available_balance){
          return [
              'error' => true,
              'error_message' => 'Insufficient balance',
              'error_code' => 1020,
          ];

        }
        $orderid = $userInfo->id . $this->microseconds();
        $api_data = array(
            'user_id' => $userInfo->id,
            'message' => $message,
            'recipient' => $phoneNumbersString,
            'senderID' => $request->isMethod('get') ? $request->senderid : "",
            'date' => date('Y-m-d H:i:s'),
            'source' => 'API',
            'sms_count' => $smsInfo->count,
            //'IP' => \http\Env\Request::getClientIp(),
            'sms_type' => 'sendSms',
            'orderid' => $orderid,
            'file' => null,
            'is_unicode' => 1,
            'status' => 'Queue',
            'total_recipient' => $totalPhoneNumber,
            'template_type' => 2
        );

        $sendMessage = Message::create($api_data);
        $apiResponse = $this->saveToOutbox($phoneNumbersString, $message, $request->senderid, $smsInfo->count, $userInfo->id, $sendMessage->id);

        //$apiResponse = $this->callReveApi($recipients, $message, $request->senderid, $userInfo->id);
        //$responseData = $apiResponse->getData(true);
        if (isset($apiResponse['Message_IDs'])) {

          if ($apiResponse['Status']['Text'] == "ACCEPTD" && $apiResponse['Status']['Status'] == 0) {

              $currentBalance->available_balance -= $balance;
              $currentBalance->save();

              Outbox::where('reference_id', $sendMessage->id)
                      ->update(['status' => 'ACCEPTD', 'reason' => 'sms request successfull']);
              Message::where('id', $sendMessage->id)
                      ->update(['status' => 'ACCEPTD']);
          }

          if ($apiResponse['Status']['Status'] == 4) {

            Outbox::where('reference_id', $sendMessage->id)
                  ->update(['status' => 'SENT', 'reason' => 'request sent']);
            Message::where('id', $sendMessage->id)
                  ->update(['status' => 'SENT']);
          }
          if ($apiResponse['Status']['Status'] == 2) {

              Outbox::where('reference_id', $sendMessage->id)
                    ->update(['status' => 'QUEUED', 'reason' => 'request pending']);
              Message::where('id', $sendMessage->id)
                    ->update(['status' => 'QUEUED']);
          }
          if ($apiResponse['Status']['Status'] == 1) {

              Outbox::where('reference_id', $sendMessage->id)
                    ->update(['status' => 'QUEUED', 'reason' => 'request failed']);
              Message::where('id', $sendMessage->id)
                    ->update(['status' => 'QUEUED']);
          }
          if ($apiResponse['Status']['Status'] == -42) {

              Outbox::where('reference_id', $sendMessage->id)
                    ->update(['status' => 'REJECTED', 'reason' => 'Authorization failed']);
              Message::where('id', $sendMessage->id)
                    ->update(['status' => 'REJECTED']);
          }
          if ($apiResponse['Status']['Status'] == 101) {

              Outbox::where('reference_id', $sendMessage->id)
                    ->update(['status' => 'REJECTED', 'reason' => 'Internal server erros occurs']);
              Message::where('id', $sendMessage->id)
                    ->update(['status' => 'REJECTED']);
          }
          if ($apiResponse['Status']['Status'] == 114) {

              Outbox::where('reference_id', $sendMessage->id)
                    ->update(['status' => 'REJECTED', 'reason' => 'Message id it not provided']);
              Message::where('id', $sendMessage->id)
                    ->update(['status' => 'REJECTED']);
          }
          if ($apiResponse['Status']['Status'] == 108) {

              Outbox::where('reference_id', $sendMessage->id)
                    ->update(['status' => 'REJECTED', 'reason' => 'Wrong password']);
              Message::where('id', $sendMessage->id)
                    ->update(['status' => 'REJECTED']);
          }
          if ($apiResponse['Status']['Status'] == 109) {

              Outbox::where('reference_id', $sendMessage->id)
                    ->update(['status' => 'REJECTED', 'reason' => 'API key is not provided']);
              Message::where('id', $sendMessage->id)
                    ->update(['status' => 'REJECTED']);
          }

          Log::info('SMS API Response: ' . json_encode($apiResponse));

          $messageIds = $apiResponse['Message_IDs'];
          return [
              'error' => false,
              'message' => 'Messages sent successfully',
              'message_id' => $messageIds
          ];


        //echo '<pre>';print_r($apiResponse['Status']['Text']);exit;

        /*if (isset($apiResponse['Message_IDs'])) {
          Outbox::where('reference_id', $sendMessage->id)
                ->update(['status' => 'Sent']);
          Message::where('id', $sendMessage->id)
              ->update(['status' => 'Sent']);
            $messageIds = $apiResponse['Message_IDs'];*/

        } else {
            return [
                'error' => true,
                'error_message' => $apiResponse['Text'] ?? 'Failed to send messages',
                'error_code' => 1016
            ];
        }

        /*if ($statusCode == 200) {

            Outbox::where('reference_id', $sendMessage->id)
                          ->update(['status' => 'Sent']);
            Message::where('id', $sendMessage->id)
                          ->update(['status' => 'Sent']);
            return [
                'error' => false,
                'message_id' => $orderid,
                'message' => 'Your SMS is successfully submitted',
            ];
        } else {
            return [
                'error' => true,
                'error_message' => 'Message sending failed',
                'error_code' => 1014,
            ];
        }*/
    } catch (\Exception $e) {
        return [
            'error' => true,
            'error_message' => 'An unexpected error occurred: ' . $e->getMessage(),
            'error_code' => 1015,
        ];
    }
  }



  public function saveToOutbox($recipients, $message, $senderID, $totalMessage, $userId, $sendMessageId)
  {
    date_default_timezone_set('Asia/Dhaka');
    $recipientList = explode(",", $recipients);
    $priority = $this->getPriority($recipientList);
    $outboxPayloadArray = [];
    $user = User::where('id', $userId)->first();
    $commaSeparated = '';
    foreach ($recipientList as $key => $destmn) {
      $operatorName = $this->getOperatorName($destmn);
      $outboxPayload = [
        "srcmn" => $senderID,
        "mask" => $senderID,
        "destmn" => trim($destmn),
        "operator_name" => $operatorName,
        "message" => $message,
        "country_code" => NULL,
        "operator_prefix" => $this->getPrefix($destmn),
        "status" => 'Queue',
        "write_time" => date('Y-m-d H:i:s'),
        "sent_time" => NULL,
        "ton" => 5,
        "npi" => 1,
        "message_type" => 'text',
        "is_unicode" => $request->isUnicode ?? false,
        "smscount" => $totalMessage,
        "esm_class" => '',
        "data_coding" => $request->dataCoding ?? '',
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
        "sms_cost" => doubleval(@$user->smsRate->nonmasking_rate),
        "sms_uniq_id" => "NBR " . date('Ymdhis') . '-' . trim($destmn) . '-' . '0000000001',  //module division by 999999999
      ];

        $outbox = Outbox::create($outboxPayload);
        $outbox_id = $outbox->id;
        //dd($outbox_id);

        $aggregator = env('Aggregator');

        if($aggregator == 'gbarta'){
          $response = Http::post('https://gbarta.gennet.com.bd/api/v1/smsapi', [
              'api_key'  => env('GBARTA_SMS_SECRET_KEY'),
              'type'     => 'text',
              'senderid' => env('GBARTA_SENDER_ID'),
              'msg'      => $message,
              'numbers'  => $destmn,
          ]);

          Log::info('Gbarta SMS API Response: ', ['response' => $response->body()]);

          // Optional: Check if the request was successful
          if ($response->successful()) {
              $responsedata = $response->json();
    
              $messageIds = explode(',', $responsedata['message_id']);
              if($commaSeparated == ''){
                $commaSeparated .= $responsedata['message_id'];
              } else {
                $commaSeparated .= ','.$responsedata['message_id'];
              }


              $formattedResponse = array_map(function ($messageId) use ($outbox_id, $message, $userId) {
                  return [
                      'user_id' => $userId,
                      'outbox_id' => $outbox_id,
                      'status' => "Message Submitted",
                      'text' => $message,
                      'message_id' => $messageId,                
                      'created_at' => now(),
                      'updated_at' => now(),
                  ];
              }, $messageIds);
    
              SmsRecord::insert($formattedResponse);
              
              $responsedata = [
                  'Status' => '0',
                  'Text' => 'ACCEPTD',
                  'Message_ID' => $messageIds[0]
              ];
          }
          
        } else {
          $response = Http::get('http://smpp.revesms.com:7788/send', [
              'apikey' => env('SMS_API_KEY'),
              'secretkey' => env('SMS_SECRET_KEY'),
              'content' => json_encode([
                  [
                      'callerID' => $senderID,
                      'toUser' => $destmn,
                      'messageContent' => $message
                  ]
              ])
          ]);
  
          if ($response->successful()) {
            $responsedata = $response->json();
  
            $messageIds = explode(',', $responsedata['Message_ID']);
            if($commaSeparated == ''){
              $commaSeparated .= $responsedata['Message_ID'];
            } else {
              $commaSeparated .= ','.$responsedata['Message_ID'];
            }
            //$commaSeparated = rtrim($commaSeparated, ',');
            $formattedResponse = array_map(function ($messageId) use ($userId, $responsedata, $outbox_id) {
                return [
                    'user_id' => $userId,
                    'outbox_id' => $outbox_id,
                    'status' => $responsedata['Status'],
                    'text' => $responsedata['Text'],
                    'message_id' => $messageId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $messageIds);
  
            SmsRecord::insert($formattedResponse);
  
          }
        }

    }
    Log::info('SMS Records inserted for Outbox ID: ' . json_encode($responsedata));

    return [
        'Status' => $responsedata,
        'Text' => 'Message sent successfully',
        'Message_IDs' => $commaSeparated // Returning the message IDs
    ];
  }

  /*public function callReveApi($toUsers, $messageContent, $callerID, $userID){

    if (!$callerID || !$toUsers || !$messageContent) {
      return response()->json(['Status' => '114', 'Text' => 'REJECTD']);
    }

    //$toUsers = is_array($toUsers) ? implode(',', $toUsers) : $toUsers;

    $response = Http::get('http://smpp.revesms.com:7788/send', [
      'apikey' => env('SMS_API_KEY'),
      'secretkey' => env('SMS_SECRET_KEY'),
      'content' => json_encode([
        [
          'callerID' => $callerID,
          'toUser' => $toUsers,
          'messageContent' => $messageContent
        ]
      ])
    ]);

    if($response->successful()) {
      $data = $response->json();
      $data['user_id'] = $userID;
      $messageIds = explode(',', $data['Message_ID']);

      $formattedResponse = array_map(function ($messageId) use ($data) {
        return [
          'status' => $data['Status'],
          'text' => $data['Text'],
          'message_id' => $messageId,
          'user_id' => $data['user_id'],
          'created_at' => now(),
          'updated_at' => now(),
        ];
      }, $messageIds);

      SmsRecord::insert($formattedResponse);
      return [
        'Status' => $data,
        'Text' => 'Message sent successfully',
        'Message_IDs' => $messageIds // Returning the message IDs
    ];
      //dd('Data saved successfully');
    } else {

      return false;
    }
  }*/

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
                if (in_array((int)$prefix, $prefixArray)) {
                    $valid13Numbers[] = $number;
                }
            }
        }

        foreach ($phoneNumbers as $number) {
            if (strlen($number) == 11) {
              $prefix = substr($number, 0, 3);
                if (in_array((int)$prefix, $prefixArray)) {
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

  public function getBalance()
  {
    // Get balance
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
}

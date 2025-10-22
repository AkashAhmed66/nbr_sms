<?php

namespace Modules\Messages\App\Repositories;

use App\Imports\MobileNumberImport;
use App\Services\SocketService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;
use Modules\Messages\App\Models\SMSRecord;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Users\App\Models\User;

class MessageRepository implements MessageRepositoryInterface
{
  use SmsCountTrait;

  protected $model;
  protected $user;

  public function __construct(Message $model, SocketService $socketService)
  {
    $this->socketService = $socketService;
    $this->model = $model;
    $this->user = Auth::user();
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();

    if (isset($filters['title'])) {
      $query->where('title', 'like', '%' . $filters['title'] . '%');
    }

    if (isset($filters['content'])) {
      $query->where('content', 'like', '%' . $filters['content'] . '%');
    }

    return $query->get();
  }

  public function saveRegularMessage(array $data): bool
  {
    Log::info('Save Regular Message Data: ', ['data' => $data]);
    $phoneNumbersString = $this->filterPhoneNumbers($data['recipient_number']);

    if (empty($phoneNumbersString)) {
      return false;
    }
    $totalPhoneNumber = count(explode(',', $phoneNumbersString));

    $user = User::where('id', $this->user->id)->first();
    $msmCount = $this->countSms($data['message_text']);
    $balance = ($msmCount->count * $totalPhoneNumber) * $this->user->smsRate->nonmasking_rate;

    if($balance > $user->available_balance){

      return false;
    }

    try {
      $messagePayload['senderID'] = $data['sender_id'];
      $messagePayload['message'] = $data['message_text'];
      $messagePayload['source'] = 'WEB';
      $messagePayload['date'] = date('Y-m-d H:i:s');
      $messagePayload['sms_type'] = 'sendSms';
      $messagePayload['scheduleDateTime'] = $data['scheduleDateTime'] ?? null;
      $messagePayload['status'] = 'Queue';
      $messagePayload['content_type'] = "text";
      $messagePayload['campaign_name'] = $data['campaign_name'] ?? 'WEB';
      $messagePayload['orderid'] = $this->user->id . $this->microseconds();
      $messagePayload['user_id'] = $this->user->id;
      $messagePayload['IP'] = request()->ip();
      //$messagePayload['total_recipient'] = $data['totalPhoneNumber'];
      $messagePayload['total_recipient'] = $totalPhoneNumber;
      $messagePayload['is_dnd_applicable'] = $data['is_dnd_applicable'] ?? null;
      $messagePayload['is_unicode'] = $data['isunicode'] == 'unicode' ? 1 : ($data['isunicode'] == 'gsmextended' ? 2 : 2);
      //$messagePayload['recipient'] = $data['recipient_number'];
      $messagePayload['recipient'] = $phoneNumbersString;
      $messagePayload['template_type'] = 2;

      $messageResponse = $this->model->create($messagePayload);

      $data['recipients'] = $phoneNumbersString;


      //SAVE TO OUTBOX TABLE
      $apiResponse = $this->saveToOutbox($data, $messageResponse->id);

      //$apiResponse = $this->callReveApi($phoneNumbersString, $data['message_text'], $data['sender_id']);
      $responseData = $apiResponse->getData(true);

      if ($responseData['status']['Text'] == "ACCEPTD" && $responseData['status']['Status'] == 0) {

        $user->available_balance -= $balance;
        $user->save();

        Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'ACCEPTD', 'reason' => 'sms request successfull']);
        Message::where('id', $messageResponse->id)
                ->update(['status' => 'ACCEPTD']);
      }
      
      if ($responseData['status']['Status'] == 4) {

        Outbox::where('reference_id', $messageResponse->id)
              ->update(['status' => 'SENT', 'reason' => 'request sent']);
        Message::where('id', $messageResponse->id)
              ->update(['status' => 'SENT']);
      }
      if ($responseData['status']['Status'] == 2) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'QUEUED', 'reason' => 'request pending']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'QUEUED']);
      }
      if ($responseData['status']['Status'] == 1) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'QUEUED', 'reason' => 'request failed']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'QUEUED']);
      }
      if ($responseData['status']['Status'] == -42) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Authorization failed']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 101) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Internal server erros occurs']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 114) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Message id it not provided']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 108) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Wrong password']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 109) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'API key is not provided']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      //dd($statusCode);
      //$this->sendMessageToSocket($data, $messageResponse->orderid);

      return true;
    } catch (\Exception $e) {
      Log::info('Error Saving Regular Message: ', ['error' => $e->getMessage()]);
      return false;
    }
  }

  public function saveGroupMessage(array $data): bool
  {

    $groupPhones = DB::table('contacts')
        ->whereIn('group_id',$data['group_ids'])
        ->pluck('phone')
        ->toArray();
    $groupPhoneNo = implode(', ', $groupPhones);

    $phoneNumbersString = $this->filterPhoneNumbers($groupPhoneNo);
    if (empty($phoneNumbersString)) {
      return false;
    }
    $totalPhoneNumber = count(explode(',', $phoneNumbersString));

    $user = User::where('id', $this->user->id)->first();
    $msmCount = $this->countSms($data['message_text']);
    $balance = ($msmCount->count * $totalPhoneNumber) * $this->user->smsRate->nonmasking_rate;

    if($balance > $user->available_balance){
      return false;
    }

    try {
      $messagePayload['senderID'] = $data['sender_id'];
      $messagePayload['message'] = $data['message_text'];
      $messagePayload['source'] = 'WEB';
      $messagePayload['date'] = date('Y-m-d H:i:s');
      $messagePayload['sms_type'] = 'groupSms';
      $messagePayload['scheduleDateTime'] = $data['scheduleDateTime'] ?? null;
      $messagePayload['status'] = 'Queue';
      $messagePayload['content_type'] = 'text';
      $messagePayload['campaign_name'] = $data['campaign_name'] ?? 'WEB';
      $messagePayload['orderid'] = $this->user->id . $this->microseconds();
      $messagePayload['user_id'] = $this->user->id;
      $messagePayload['IP'] = request()->ip();
      $messagePayload['total_recipient'] = $totalPhoneNumber;
      $messagePayload['is_dnd_applicable'] = $data['is_dnd_applicable'] ?? null;
      $messagePayload['is_unicode'] = $data['isunicode'] == 'unicode' ? 1 : ($data['isunicode'] == 'gsmextended' ? 2 : 2);
      //$messagePayload['recipient'] = $data['recipient_number'];
      $messagePayload['recipient'] = $phoneNumbersString;
      $messagePayload['template_type'] = 2;
      //dd($messagePayload);
      $messageResponse = $this->model->create($messagePayload);

      $data['recipients'] = $phoneNumbersString;
      //SAVE TO OUTBOX TABLE
      $apiResponse = $this->saveToOutbox($data, $messageResponse->id);

      //$this->sendMessageToSocket($data, $messageResponse->orderid);
      //$apiResponse = $this->callReveApi($phoneNumbersString, $data['message_text'], $data['sender_id']);
      $responseData = $apiResponse->getData(true);

      if ($responseData['status']['Text'] == "ACCEPTD" && $responseData['status']['Status'] == 0) {

        $user->available_balance -= $balance;
        $user->save();

        Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'ACCEPTD', 'reason' => 'sms request successfull']);
        Message::where('id', $messageResponse->id)
                ->update(['status' => 'ACCEPTD']);
      }

      if ($responseData['status']['Status'] == 4) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'SENT', 'reason' => 'request sent']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'SENT']);
      }
      if ($responseData['status']['Status'] == 2) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'QUEUED', 'reason' => 'request pending']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'QUEUED']);
      }
      if ($responseData['status']['Status'] == 1) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'QUEUED', 'reason' => 'request failed']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'QUEUED']);
      }
      if ($responseData['status']['Status'] == -42) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Authorization failed']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 101) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Internal server erros occurs']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 114) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Message id it not provided']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 108) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Wrong password']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 109) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'API key is not provided']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }

      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  public function createFileMessage(array $data): bool
  {
    $file = $data['file'];

    $import = new MobileNumberImport();
    $excelData = Excel::toArray($import, $file);

    $mobileNumbers = $excelData[0];
    $mobileNumbers = array_slice($mobileNumbers, 1);

    $recipientNumbers = [];
    foreach ($mobileNumbers as $row) {
      $mobileNumber = $row[0];
      $recipientNumbers[] = $mobileNumber;
    }
    $recipientsString = implode(',', $recipientNumbers);

    $phoneNumbersString = $this->filterPhoneNumbers($recipientsString);
	if (empty($phoneNumbersString)) {
		return false;
	}
    $totalRecipients = count(explode(',', $phoneNumbersString));

    $user = User::where('id', $this->user->id)->first();
    $msmCount = $this->countSms($data['message_text']);
    $balance = ($msmCount->count * $totalRecipients) * $this->user->smsRate->nonmasking_rate;

    if($balance > $user->available_balance){
      return false;
    }

    try {
      $messagePayload['senderID'] = $data['sender_id'];
      $messagePayload['message'] = $data['message_text'];
      $messagePayload['source'] = 'WEB';
      $messagePayload['date'] = date('Y-m-d H:i:s');
      $messagePayload['sms_type'] = 'groupSms';
      $messagePayload['scheduleDateTime'] = $data['scheduleDateTime'] ?? null;
      $messagePayload['status'] = 'Queue';
      $messagePayload['content_type'] = 'text';
      $messagePayload['campaign_name'] = $data['campaign_name'] ?? 'WEB';
      $messagePayload['orderid'] = $this->user->id . $this->microseconds();
      $messagePayload['user_id'] = $this->user->id;
      $messagePayload['IP'] = request()->ip();
      $messagePayload['total_recipient'] = $totalRecipients;
      $messagePayload['is_dnd_applicable'] = $data['is_dnd_applicable'] ?? null;
      $messagePayload['is_unicode'] = $data['isunicode'] == 'unicode' ? 1 : ($data['isunicode'] == 'gsmextended' ? 2 : 2);
      //$messagePayload['recipient'] = $data['recipient_number'];
      $messagePayload['recipient'] = $phoneNumbersString;
      $messagePayload['template_type'] = 2;
      //dd($messagePayload);
      $messageResponse = $this->model->create($messagePayload);
      $data['recipients'] = $phoneNumbersString;
      //SAVE TO OUTBOX TABLE

      $apiResponse = $this->saveToOutbox($data, $messageResponse->id);
      //$apiResponse = $this->callReveApi($phoneNumbersString, $data['message_text'], $data['sender_id']);
      $responseData = $apiResponse->getData(true);

      if ($responseData['status']['Text'] == "ACCEPTD" && $responseData['status']['Status'] == 0) {

        $user->available_balance -= $balance;
        $user->save();

        Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'ACCEPTD', 'reason' => 'sms request successfull']);
        Message::where('id', $messageResponse->id)
                ->update(['status' => 'ACCEPTD']);
      }

      if ($responseData['status']['Status'] == 4) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'SENT', 'reason' => 'request sent']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'SENT']);
      }
      if ($responseData['status']['Status'] == 2) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'QUEUED', 'reason' => 'request pending']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'QUEUED']);
      }
      if ($responseData['status']['Status'] == 1) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'QUEUED', 'reason' => 'request failed']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'QUEUED']);
      }
      if ($responseData['status']['Status'] == -42) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Authorization failed']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 101) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Internal server erros occurs']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 114) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Message id it not provided']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 108) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'Wrong password']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }
      if ($responseData['status']['Status'] == 109) {

          Outbox::where('reference_id', $messageResponse->id)
                ->update(['status' => 'REJECTED', 'reason' => 'API key is not provided']);
          Message::where('id', $messageResponse->id)
                ->update(['status' => 'REJECTED']);
      }

      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  public function microseconds(): int
  {
    $mt = explode(' ', microtime());
    return intval($mt[1] * 1E6) + intval(round($mt[0] * 1E6));
  }

  public function create(array $data): Message
  {
    return $this->model->create($data);
  }

  private function saveToOutbox(array $data, $sendMessageId)
  {
    Log::info('Save to Outbox Data: ', ['data' => $data, 'sendMessageId' => $sendMessageId]);
    try {
      date_default_timezone_set('Asia/Dhaka');
      $explodeRecipients = explode(",", $data['recipients']);
      $priority = $this->getPriority($explodeRecipients);

      $countSmsInfo = $this->countSms($data['message_text']);
      $maskSmsCount = (int)$countSmsInfo->count;
      $nonMaskSmsCount = 0;
      $totalMessage = $maskSmsCount + $nonMaskSmsCount;

      Log::info('Total Messages to Send: ', ['totalMessage' => $totalMessage, 'countSmsInfo' => $countSmsInfo, 'explodeRecipients' => $explodeRecipients]);
      //$outboxPayloadArray = [];
      foreach ($explodeRecipients as $key => $destmn) {
        Log::info('Sending SMS to: ', ['destmn' => $destmn]);
        $operatorName = $this->getOperatorName($destmn);
        $outboxPayload = [
          "srcmn" => $data['sender_id'],
          "mask" => $data['sender_id'],
          "destmn" => trim($destmn),
          "operator_name" => $operatorName,
          "message" => $data['message_text'],
          "country_code" => null,
          "operator_prefix" => $this->getPrefix($destmn),
          "status" => 'Queue',
          "write_time" => date('Y-m-d H:i:s'),
          "sent_time" => null,
          "ton" => 5,
          "npi" => 1,
          "message_type" => 'text',
          "is_unicode" => $data['isUnicode'] ?? false,
          "smscount" => $totalMessage,
          "esm_class" => '',
          "data_coding" => $data['dataCoding'] ?? '',
          "reference_id" => $sendMessageId,
          "last_updated" => date('Y-m-d H:i:s'),
          "schedule_time" => null,
          "retry_count" => 0,
          "user_id" => $this->user->id,
          "remarks" => '',
          "uuid" => hex2bin(str_replace('-', '', Str::uuid()->toString())),
          "priority" => $priority,
          "blocked_status" => null,
          "created_at" => date('Y-m-d H:i:s'),
          "updated_at" => date('Y-m-d H:i:s'),
          "error_code" => null,
          "error_message" => null,
          "sms_cost" => doubleval($this->user->smsRate->nonmasking_rate ?? 0) * $totalMessage,
          "sms_uniq_id" => "RIB " . date('Ymdhis') . '-' . trim($destmn) . '-' . '0000000001',
          //module division by 999999999
        ];

        $outbox = Outbox::create($outboxPayload);
        $outbox_id = $outbox->id;

        Log::info('Sending SMS via REVE SMS API to: ', ['toUser' => $destmn, 'outbox_id' => $outbox_id]);
        

        $response = null;
        
        $aggregator = env('Aggregator');

        if($aggregator == 'gbarta'){
          $response = Http::post('https://gbarta.gennet.com.bd/api/v1/smsapi', [
              'api_key'  => env('GBARTA_SMS_SECRET_KEY'),
              'type'     => 'text',
              'senderid' => env('GBARTA_SENDER_ID'),
              'msg'      => $data['message_text'],
              'numbers'  => $destmn,
          ]);

          Log::info('Gbarta SMS API Response: ', ['response' => $response->body()]);

          // Optional: Check if the request was successful
          if ($response->successful()) {
              $responsedata = $response->json();
    
              $messageIds = explode(',', $responsedata['message_id']);
    
              $formattedResponse = array_map(function ($messageId) use ($outbox_id, $data) {
                  return [
                      'user_id' => $this->user->id,
                      'outbox_id' => $outbox_id,
                      'status' => "Message Submitted",
                      'text' => $data['message_text'],
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
                      'callerID' => $data['sender_id'],
                      'toUser' => $destmn,
                      'messageContent' => $data['message_text']
                  ]
              ])
          ]);
  
          Log::info('REVE SMS API Response: ', ['response' => $response->body()]);

          if ($response->successful()) {
              $responsedata = $response->json();
    
              $messageIds = explode(',', $responsedata['Message_ID']);
    
              $formattedResponse = array_map(function ($messageId) use ($responsedata, $outbox_id) {
                  return [
                      'user_id' => $this->user->id,
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
      //fix the logging issue
      Log::info('All SMS requests have been sent.', ['response' => $response]);
      return response()->json(['status' => $responsedata]);
      //Outbox::insert($outboxPayloadArray);
    } catch (\Exception $e) {
      Log::info('Error Saving to Outbox: ', ['error' => $e->getMessage()]);
      throw new \Exception($e->getMessage());
    }
  }

  function callReveApi($toUsers, $messageContent, $callerID)
  {
      if (!$callerID || !$toUsers || !$messageContent) {
          return response()->json(['Status' => '114', 'Text' => 'REJECTED']);
      }
      
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

      if ($response->successful()) {
          $data = $response->json();

          $messageIds = explode(',', $data['Message_ID']);

          $formattedResponse = array_map(function ($messageId) use ($data) {
              return [
                  'user_id' => $this->user->id,
                  'status' => $data['Status'],
                  'text' => $data['Text'],
                  'message_id' => $messageId,                
                  'created_at' => now(),
                  'updated_at' => now(),
              ];
          }, $messageIds);

          SmsRecord::insert($formattedResponse);

          return response()->json(['status' => $data]);

      } else {

          // Handle HTTP-level errors
          return response()->json([
              'Status' => (string)$response->status(),
              'Text' => 'HTTP Request failed',
              'Error' => $response->body(),
          ]);
      }
  }

  public function update(array $data, int $id): Message
  {
    $message = $this->model->find($id);
    $message->update($data);

    return $message;
  }

  public function find(int $id): Message
  {
    return $this->model->find($id);
  }

  public function saveFileMessage(array $data): bool
  {
    return $this->model->create($data);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }

  public function sendMessageToSocket(array $data, $orderId): void
  {
    try {
      $countSmsInfo = $this->countSms($data['message_text']);
      $socketData = [
        'messageId' => (string)$orderId,
        'senderId' => $data['sender_id'],
        'userId' => $this->user->id,
        'recepient' => $data['recipient_number'],
        'type' => 'group',
        'encoding' => 0,
        "parts" => (array)$countSmsInfo->parts,
        "has_gsm_extended" => 0,
        "no_of_sms" => (int)$countSmsInfo->count
      ];

      $dataString = json_encode($socketData) . "\n";
      $this->socketService->sendData($dataString);
      //$this->socketService->receiveData();
      $this->socketService->closeSocket();
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
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
}

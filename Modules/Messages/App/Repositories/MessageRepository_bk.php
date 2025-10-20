<?php

namespace Modules\Messages\App\Repositories;

use App\Imports\DynamicMessageImport;
use App\Imports\Messagemport;
use App\Imports\MobileNumberImport;
use App\Jobs\SendMaskedMessageJob;
use App\Services\SocketService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Messages\App\Trait\AggregatorTrait;
use Modules\Messages\App\Trait\ReveApiTrait;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Users\App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Jobs\SendMessageToInfozillionJob;
use Modules\Phonebook\App\Models\Dnd;

class MessageRepository_bk implements MessageRepositoryInterface
{
  use SmsCountTrait;
  use AggregatorTrait;
  use ReveApiTrait;

  protected $model;
  protected $user;

  public function __construct(Message $model)
  {
    //$this->socketService = $socketService;
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
    Log::channel('sms')->info("SaveRegularMessage:");
    Log::channel('sms')->info($data);

    try {
      $messagePayload['senderID'] = $data['sender_id'];
      $messagePayload['message'] = $data['message_text'];
      $messagePayload['source'] = $data['source'] ?? 'WEB';
      $messagePayload['date'] = date('Y-m-d H:i:s');
      $messagePayload['sms_type'] = 'sendSms';
      $messagePayload['scheduleDateTime'] = (isset($data['isScheduleMessage']) && $data['isScheduleMessage'] == 1) ? $data['scheduleDate'] . ' ' . $data['scheduleTime'] . ':00' : null;
      $messagePayload['status'] = 'Queue';
      $messagePayload['content_type'] = $data['content_type'];
      $messagePayload['campaign_name'] = $data['campaign_name'] ?? 'WEB';
      $messagePayload['orderid'] = $this->user->id . $this->microseconds();
      $messagePayload['user_id'] = $this->user->id;
      $messagePayload['IP'] = request()->ip();
      $messagePayload['total_recipient'] = $data['totalPhoneNumber'];
      $messagePayload['is_dnd_applicable'] = $data['is_dnd_applicable'] ?? null;
      $messagePayload['is_unicode'] = $data['isunicode'] == 'unicode' ? 1 : ($data['isunicode'] == 'gsmextended' ? 2 : 2);
      $messagePayload['recipient'] = $data['recipient_number'];
      $messagePayload['campaign_id'] = $data['campaign_id'];
      $messagePayload['is_masking'] = $data['masking_type'] == 'Masking' ? 1 : 0;

      $messageResponse = $this->model->create($messagePayload);

      //SAVE TO OUTBOX TABLE
      $data['transactionType'] = 'T';
      $this->saveToOutbox($data, $messagePayload['orderid'], $messageResponse->id);

      return true;
    } catch (\Exception $e) {
      //dd($e->getMessage());
      Log::channel('sms')->info($e);
      return false;
    }
  }

  private function microseconds(): int
  {
    $mt = explode(' ', microtime());
    return intval($mt[1] * 1E6) + intval(round($mt[0] * 1E6));
  }

  public function create(array $data): Message
  {
    return $this->model->create($data);
  }

  public function saveGroupMessage(array $data): bool
  {
    Log::channel('sms')->info("SaveGroupMessage:");
    Log::channel('sms')->info($data);
    try {
      $messagePayload['senderID'] = $data['sender_id'];
      $messagePayload['message'] = $data['message_text'];
      $messagePayload['source'] = 'WEB';
      $messagePayload['date'] = date('Y-m-d H:i:s');
      $messagePayload['sms_type'] = 'groupSms';
      $messagePayload['scheduleDateTime'] = (isset($data['isScheduleMessage']) && $data['isScheduleMessage'] == 1) ? $data['scheduleDate'] . ' ' . $data['scheduleTime'] . ':00' : null;
      $messagePayload['status'] = 'Queue';
      $messagePayload['content_type'] = $data['content_type'];
      $messagePayload['campaign_name'] = $data['campaign_name'] ?? 'WEB';
      $messagePayload['orderid'] = $this->user->id . $this->microseconds();
      $messagePayload['user_id'] = $this->user->id;
      $messagePayload['IP'] = request()->ip();
      $messagePayload['total_recipient'] = $data['total_recipient'];
      $messagePayload['is_dnd_applicable'] = $data['is_dnd_applicable'] ?? null;
      $messagePayload['is_unicode'] = $data['isunicode'] == 'unicode' ? 1 : ($data['isunicode'] == 'gsmextended' ? 2 : 2);
      $messagePayload['recipient'] = $data['recipient_number'];
      $messagePayload['campaign_id'] = $data['campaign_id'];
      //dd($messagePayload);
      $messageResponse = $this->model->create($messagePayload);

      //SAVE TO OUTBOX TABLE
      $data['transactionType'] = 'P';

      //get recipient numbers from group using group ids
      $groupIds = $data['group_ids'] ?? [];
      $groupIds = Arr::flatten($groupIds);
      $groupPhones = DB::table('contacts')
        ->whereIn('group_id', $groupIds)
        ->pluck('phone')
        ->toArray();
      // dd($data);
      $groupPhoneNo = implode(', ', $groupPhones);
      if($data['dnd'] && $data['dnd'] == '1') {
        $dndNumbers = DB::table('dnds')->where('user_id', auth()->user()->id)->pluck('phone')->toArray();
        // dd($groupPhoneNo);
        $numbers = explode(", ", $groupPhoneNo);
        $filteredNumbers = array_filter($numbers, function ($num) use ($dndNumbers) {
          return !in_array($num, $dndNumbers);
        });
        $groupPhoneNo = implode(", ", $filteredNumbers);
      }

      if ($data['dnd'] && $data['dnd'] == '1') {
          $dndNumbers = DB::table('dnds')
              ->where('user_id', auth()->user()->id)
              ->pluck('phone')
              ->toArray();

          // Normalize DND numbers (add 88 if missing)
          $dndNumbers = array_map(function ($num) {
              $num = preg_replace('/\s+/', '', $num); // remove spaces
              return str_starts_with($num, '88') ? $num : '88' . $num;
          }, $dndNumbers);

          // Split group numbers
          $numbers = explode(", ", $groupPhoneNo);

          // Filter numbers not in DND list
          $filteredNumbers = array_filter($numbers, function ($num) use ($dndNumbers) {
              $normalized = preg_replace('/\s+/', '', $num); // remove spaces
              $normalized = str_starts_with($normalized, '88') ? $normalized : '88' . $normalized;
              return !in_array($normalized, $dndNumbers);
          });

          // Rebuild string
          $groupPhoneNo = implode(", ", $filteredNumbers);
        }
        // dd($groupPhoneNo);


      $data['recipient_number'] = $groupPhoneNo;
      // dd($data['recipient_number']);

      $this->saveToOutbox($data, $messagePayload['orderid'], $messageResponse->id);

      return true;
    } catch (\Exception $e) {
      Log::channel('sms')->info($e);
      return false;
    }
  }

  public function saveFileMessage(array $data): bool
  {
    return $this->model->create($data);
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

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }

  private function saveToOutbox(array $data, $orderId, $sendMessageId): void
  {
    //Log::channel('sms')->info("SaveToOutbox:");
    //Log::channel('sms')->info($data);
    try {
      $explodeRecipients = explode(",", $data['recipient_number']);
      $priority = $this->getPriority($explodeRecipients);

      $pattern = "/(^(\+8801|8801|01|008801))[1|3-9]{1}(\d){8}$/";
      $numbers = [];

      foreach ($explodeRecipients as $explodeRecipient) {
        $cleaned = preg_replace('/\D+/', '', $explodeRecipient); // Remove non-digits

        // Add '88' if the number doesn't start with '88'
        if (!str_starts_with($cleaned, '88')) {
          $cleaned = '88' . $cleaned;
        }

        if (preg_match($pattern, $cleaned)) {
          $numbers[] = $cleaned;
        }
      }

      $totalNumber = count($numbers);
      $countSmsInfo = $this->countSms($data['message_text']);

      $totalMessage = (int) $countSmsInfo->count;


      if ($data['masking_type'] == 'Masking') {
        $totalCost = doubleval(($this->user->smsRate->masking_rate ?? 0) * $totalMessage);
      } else {
        $totalCost = doubleval(($this->user->smsRate->nonmasking_rate ?? 0) * $totalMessage);
      }

      $outboxPayloadArray = [];
      foreach ($explodeRecipients as $key => $destmn) {
        $outboxPayload = [
          "srcmn" => $data['sender_id'],
          "mask" => $data['sender_id'],
          "destmn" => trim($destmn),
          "message" => $data['message_text'],
          "country_code" => null,
          "operator_prefix" => $this->getPrefix($destmn),
          "status" => 'Queue',
          "write_time" => date('Y-m-d H:i:s'),
          "sent_time" => null,
          "ton" => 5,
          "npi" => 1,
          "message_type" => 'text',
          "is_unicode" => $data['isunicode'] == 'unicode' ? 1 : ($data['isunicode'] == 'gsmextended' ? 2 : 0),
          "smscount" => $totalMessage,
          "esm_class" => '',
          "data_coding" => $data['isunicode'] == 'unicode' ? 8 : ($data['isunicode'] == 'gsmextended' ? 4 : 0),
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
          // "dlr_status_code" => null,
          "dlr_status" => null,
          "dlr_status_meaning" => null,
          "sms_cost" => $totalCost,
          "sms_uniq_id" => getenv('MESSAGE_PREFIX') . " " . date('Ymdhis') . '-' . trim($destmn) . '-' . '0000000001',
          //module division by 999999999
        ];

        $outboxPayloadArray[] = $outboxPayload;
      }

      if (Outbox::insert($outboxPayloadArray)) {

        $this->balanceDeduction($totalCost, $this->user->id);
        if ($data['isScheduleMessage'] == 0) {
          if (env('APP_TYPE') == 'Aggregator') {
            Log::channel('sms')->info("APP_TYPE Aggregator ");
            Log::channel('sms')->info("IS_API_BASED ".env('IS_API_BASED'));

            /*if (env('IS_API_BASED')) {
              Log::channel('sms')->info("APP_TYPE IS_API_BASED ");
              $this->sendMessageToReveApi($sendMessageId);
            } */


            //FOR MASKING MESSAGE
            // if(($data['masking_type'] == 'Masking')){
            //   $company = "grameenphone"; //Will be changed later
            //   $recipients = $numbers;
            //   $message = $data['message_text'];
            //   $messageType = 1;
            //   $cli = $data['sender_id'];
            //   if($data['content_type'] == 'Flash'){
            //     $messageType = 2;
            //   }else{
            //     if($data['isunicode'] == 'unicode'){
            //       $messageType = 3;
            //     }
            //   }
            //   SendMaskedMessageJob::dispatch($company, $recipients, $message, $messageType, $cli);
            // }else {
              Log::channel("sms")->info("Calling Infozillion API");

              $infozillion_array = array('msisdnList' => $numbers, 'message' => $data['message_text'], 'campaign_id' => $data['campaign_id']);
              $infozillion_array['transactionType'] = count($numbers) > 1 ? 'P' : 'T';
              // $infozillion_array['transactionType'] = 'T';
              $infozillion_array['cli'] = $data['sender_id'];
              $infozillion_array['isunicode'] = $data['isunicode'] == 'unicode' ? '1' : '0';

              Log::channel('sms')->info("Infozillion Array:");
              Log::channel('sms')->info(json_encode($infozillion_array));

              //$this->sendMessageToInfozilion($infozillion_array);
              SendMessageToInfozillionJob::dispatch($infozillion_array, $sendMessageId);
            // }
          }
        }
      }

    } catch (\Exception $e) {
      // Log::channel('sms')->info($e);
      throw new \Exception($e->getMessage());
    }
  }

  public function sendMessageToSocket($data, $orderId): void
  {
    Log::channel('sms')->info("sendMessageToSocket");
    $outboxMessages = Outbox::where('reference_id', $orderId)->get();

    $priority = $this->messagePriority($data['message_text']);

    $countSmsInfo = $this->countSms($data['message_text']);

    foreach ($outboxMessages as $outboxMessage) {

      //$outboxMessage->parts  = preg_replace('/\\\\u([0-9a-fA-F]{4})/', '$1 ', $outboxMessage->parts);

      if ($data['isunicode'])
        $encoding = 8;
      else
        $encoding = 0;

      $socketData = [
        'messageId' => (string) $outboxMessage->id,
        'senderId' => $outboxMessage->srcmn,
        'userId' => $outboxMessage->user_id,
        'recepient' => $outboxMessage->destmn,
        'type' => 'single',
        'encoding' => $encoding,
        "parts" => (array) $countSmsInfo->parts,
        "has_gsm_extended" => 0,
        "no_of_sms" => (int) $countSmsInfo->count,
        "priority" => $priority,
      ];

      $dataString = json_encode($socketData) . "\n";
      Log::channel('sms')->info("Calling socket service for sending data:");
      //$this->socketService->sendData($dataString);

      app(SocketService::class)->sendData($dataString);

      Log::channel('sms')->info("Sent to socket:");
      //      Log::channel('sms')->info($dataString);
    }

    //when done
    //$this->socketService->disconnect();
  }

  public function createFileMessage($request): bool
  {
    try {
      $request->validate([
        'importFile' => 'required|file|mimes:xlsx,csv,xls',
      ]);

      DB::beginTransaction();
      try {

        $totalRecipients = $request->totalMobileNumbers ?? 0;
        $smsCount = $this->countSms($request->message_text);

        if ($request->masking_type == 'Masking') {
          $totalMessageCost = ($smsCount->count * $totalRecipients) * doubleval($this->user->smsRate->masking_rate ?? 0);
        } else {
          $totalMessageCost = ($smsCount->count * $totalRecipients) * doubleval($this->user->smsRate->nonmasking_rate ?? 0);
        }

        $messagePayload['senderID'] = $request->sender_id;
        $messagePayload['message'] = $request->message_text;
        $messagePayload['sms_count'] = $smsCount->count;
        $messagePayload['source'] = 'WEB';
        $messagePayload['date'] = date('Y-m-d H:i:s');
        $messagePayload['sms_type'] = 'fileSms';
        $messagePayload['scheduleDateTime'] = (isset($request->isScheduleMessage) && $request->isScheduleMessage == 1) ? $request->scheduleDate . ' ' . $request->scheduleTime . ':00' : null;
        $messagePayload['status'] = 'Queue';
        $messagePayload['content_type'] = $request->content_type;
        $messagePayload['campaign_name'] = $request->campaign_name ?? 'WEB';
        $messagePayload['campaign_id'] = $request->campaign_id;
        $messagePayload['orderid'] = $this->user->id . $this->microseconds();
        $messagePayload['user_id'] = $this->user->id;
        $messagePayload['IP'] = request()->ip();
        $messagePayload['total_recipient'] = $totalRecipients;
        $messagePayload['is_dnd_applicable'] = $request->is_dnd_applicable ?? null;
        $messagePayload['is_unicode'] = $request->isunicode == 'unicode' ? 1 : ($request->isunicode == 'gsmextended' ? 2 : 2);
        $messagePayload['recipient'] = '';
        $messagePayload['is_masking'] = $request->masking_type == 'Masking' ? 1 : 0;


        $messageResponse = $this->model->create($messagePayload);

        $this->balanceDeduction($totalMessageCost, $this->user->id);


        $dndnumbers = Dnd::where('user_id', auth()->user()->id)
          ->pluck('phone')
          ->map(function ($phone) {
              $phone = trim($phone); // remove extra spaces
              return substr($phone, 0, 2) === '88' ? $phone : '88' . $phone;
          })
          ->toArray();

        Excel::queueImport(
          new Messagemport($messageResponse, intval($this->user->id), $request->dnd, $dndnumbers),
          $request->file('importFile'),
        );


        DB::commit();

        return true;
      } catch (\Exception $e) {
        DB::rollBack();
        return false;
      }
    } catch (\Exception $e) {
      DB::rollBack();
      return false;
    }


    /*$file = $data['file'];

    $import = new MobileNumberImport();
    $excelData = Excel::toArray($import, $file);

    $mobileNumbers = $excelData[0];
    $mobileNumbers = array_slice($mobileNumbers, 1);
    $totalRecipients = count($mobileNumbers);

    $currentBalance = User::where('id', $this->user->id)->first()->available_balance;

    $msmCount = $this->countSms($data['message_text']);
    $balance = ($msmCount->count * $totalRecipients) * doubleval($this->user->smsRate->nonmasking_rate ?? 0);
    if ($balance > $currentBalance) {
      return 100;
    }

    $recipientNumbers = [];

    foreach ($mobileNumbers as $row) {
      $mobileNumber = $row[0];
      $recipientNumbers[] = $mobileNumber;
    }

    $recipientsString = implode(',', $recipientNumbers);

    try {
      $messagePayload['senderID'] = $data['sender_id'];
      $messagePayload['message'] = $data['message_text'];
      $messagePayload['source'] = 'WEB';
      $messagePayload['date'] = date('Y-m-d H:i:s');
      $messagePayload['sms_type'] = 'groupSms';
      $messagePayload['scheduleDateTime'] = $data['scheduleDateTime'] ?? null;
      $messagePayload['status'] = 'Queue';
      $messagePayload['content_type'] = $data['content_type'];
      $messagePayload['campaign_name'] = $data['campaign_name'] ?? 'WEB';
      $messagePayload['orderid'] = $this->user->id . $this->microseconds();
      $messagePayload['user_id'] = $this->user->id;
      $messagePayload['IP'] = request()->ip();
      $messagePayload['total_recipient'] = $totalRecipients;
      $messagePayload['is_dnd_applicable'] = $data['is_dnd_applicable'] ?? null;
      $messagePayload['is_unicode'] = $data['isunicode'] == 'unicode' ? 1 : ($data['isunicode'] == 'gsmextended' ? 2 : 2);
      //$messagePayload['recipient'] = $data['recipient_number'];
      $messagePayload['campaign_id'] = $data['campaign_id'];
      $messagePayload['recipient'] = $recipientsString;
      //dd($messagePayload);
      $messageResponse = $this->model->create($messagePayload);

      //SAVE TO OUTBOX TABLE
      $data['recipient_number'] = $recipientsString;
      $data['transactionType'] = 'P';
      $this->saveToOutbox($data, $messagePayload['orderid'], $messageResponse->id);

      return true;
    } catch (\Exception $e) {
      dd($e->getMessage());
      Log::channel('sms')->info($e);
      return false;
    }*/
  }
  public function createDynamicMessage($request): bool
  {
    try {
      $request->validate([
        'importFile' => 'required|file|mimes:xlsx,csv,xls',
      ]);

      DB::beginTransaction();
      try {

        $totalRecipients = $request->totalMobileNumbers ?? 0;
        $smsCount = $this->countSms($request->message_text);

        if ($request->masking_type == 'Masking') {
          $totalMessageCost = ($smsCount->count * $totalRecipients) * doubleval($this->user->smsRate->masking_rate ?? 0);
        } else {
          $totalMessageCost = ($smsCount->count * $totalRecipients) * doubleval($this->user->smsRate->nonmasking_rate ?? 0);
        }

        $messagePayload['senderID'] = $request->sender_id;
        $messagePayload['message'] = $request->message_text;
        $messagePayload['sms_count'] = $smsCount->count;
        $messagePayload['source'] = 'WEB';
        $messagePayload['date'] = date('Y-m-d H:i:s');
        $messagePayload['sms_type'] = 'dynamicSms';
        $messagePayload['scheduleDateTime'] = (isset($request->isScheduleMessage) && $request->isScheduleMessage == 1) ? $request->scheduleDate . ' ' . $request->scheduleTime . ':00' : null;
        $messagePayload['status'] = 'Queue';
        $messagePayload['content_type'] = $request->content_type;
        $messagePayload['campaign_name'] = $request->campaign_name ?? 'WEB';
        $messagePayload['campaign_id'] = $request->campaign_id;
        $messagePayload['orderid'] = $this->user->id . $this->microseconds();
        $messagePayload['user_id'] = $this->user->id;
        $messagePayload['IP'] = request()->ip();
        $messagePayload['total_recipient'] = $totalRecipients;
        $messagePayload['is_dnd_applicable'] = $request->is_dnd_applicable ?? null;
        $messagePayload['is_unicode'] = $request->isunicode == 'unicode' ? 1 : ($request->isunicode == 'gsmextended' ? 2 : 2);
        $messagePayload['recipient'] = '';
        $messagePayload['is_masking'] = $request->masking_type == 'Masking' ? 1 : 0;


        $messageResponse = $this->model->create($messagePayload);

        $this->balanceDeduction($totalMessageCost, $this->user->id);


        $dndnumbers = Dnd::where('user_id', auth()->user()->id)
          ->pluck('phone')
          ->map(function ($phone) {
              $phone = trim($phone); // remove extra spaces
              return substr($phone, 0, 2) === '88' ? $phone : '88' . $phone;
          })
          ->toArray();

        // dd(json_decode($request->excel_headers, true));
        $excelHeaders = json_decode($request->excel_headers, true);

        Excel::queueImport(
          new DynamicMessageImport($messageResponse, intval($this->user->id), $request->dnd, $dndnumbers, $excelHeaders),
          $request->file('importFile'),
        );


        DB::commit();

        return true;
      } catch (\Exception $e) {
        DB::rollBack();
        return false;
      }
    } catch (\Exception $e) {
      DB::rollBack();
      return false;
    }


    /*$file = $data['file'];

    $import = new MobileNumberImport();
    $excelData = Excel::toArray($import, $file);

    $mobileNumbers = $excelData[0];
    $mobileNumbers = array_slice($mobileNumbers, 1);
    $totalRecipients = count($mobileNumbers);

    $currentBalance = User::where('id', $this->user->id)->first()->available_balance;

    $msmCount = $this->countSms($data['message_text']);
    $balance = ($msmCount->count * $totalRecipients) * doubleval($this->user->smsRate->nonmasking_rate ?? 0);
    if ($balance > $currentBalance) {
      return 100;
    }

    $recipientNumbers = [];

    foreach ($mobileNumbers as $row) {
      $mobileNumber = $row[0];
      $recipientNumbers[] = $mobileNumber;
    }

    $recipientsString = implode(',', $recipientNumbers);

    try {
      $messagePayload['senderID'] = $data['sender_id'];
      $messagePayload['message'] = $data['message_text'];
      $messagePayload['source'] = 'WEB';
      $messagePayload['date'] = date('Y-m-d H:i:s');
      $messagePayload['sms_type'] = 'groupSms';
      $messagePayload['scheduleDateTime'] = $data['scheduleDateTime'] ?? null;
      $messagePayload['status'] = 'Queue';
      $messagePayload['content_type'] = $data['content_type'];
      $messagePayload['campaign_name'] = $data['campaign_name'] ?? 'WEB';
      $messagePayload['orderid'] = $this->user->id . $this->microseconds();
      $messagePayload['user_id'] = $this->user->id;
      $messagePayload['IP'] = request()->ip();
      $messagePayload['total_recipient'] = $totalRecipients;
      $messagePayload['is_dnd_applicable'] = $data['is_dnd_applicable'] ?? null;
      $messagePayload['is_unicode'] = $data['isunicode'] == 'unicode' ? 1 : ($data['isunicode'] == 'gsmextended' ? 2 : 2);
      //$messagePayload['recipient'] = $data['recipient_number'];
      $messagePayload['campaign_id'] = $data['campaign_id'];
      $messagePayload['recipient'] = $recipientsString;
      //dd($messagePayload);
      $messageResponse = $this->model->create($messagePayload);

      //SAVE TO OUTBOX TABLE
      $data['recipient_number'] = $recipientsString;
      $data['transactionType'] = 'P';
      $this->saveToOutbox($data, $messagePayload['orderid'], $messageResponse->id);

      return true;
    } catch (\Exception $e) {
      dd($e->getMessage());
      Log::channel('sms')->info($e);
      return false;
    }*/
  }

  public function retryCampaign($id)
  {
    $outboxMessages = Outbox::where('reference_id', $id)->where('status', 'Failed')->get();

    foreach ($outboxMessages as $outboxMessage) {
      $countSmsInfo = $this->countSms($outboxMessage->message);
      $socketData = [
        'messageId' => (string) $outboxMessage->id,
        'senderId' => $outboxMessage->srcmn,
        'userId' => $outboxMessage->user_id,
        'recepient' => $outboxMessage->destmn,
        'type' => 'group',
        'encoding' => 0,
        "parts" => (array) $countSmsInfo->parts,
        "has_gsm_extended" => 0,
        "no_of_sms" => (int) $countSmsInfo->count
      ];

      $dataString = json_encode($socketData) . "\n";
      //$this->socketService->sendData($dataString);
      app(SocketService::class)->sendData($dataString);
    }

    // when done
    //$this->socketService->disconnect();

  }

  public function balanceDeduction($amount, $userId): void
  {
    try {
      $user = User::find($userId);
      if ($user) {
        $user->available_balance -= $amount;
        $user->save();
      }
    } catch (\Exception $e) {
      Log::error("Balance deduction failed: " . $e->getMessage());
    }
  }
}

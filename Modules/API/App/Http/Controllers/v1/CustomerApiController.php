<?php

namespace Modules\API\App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\API\App\Http\Requests\CustomerApiSendMessageRequest;
use Modules\API\App\Repositories\CustomerApiRepositoryInterface;
use Modules\Messages\App\Repositories\MessageRepositoryInterface;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Smsconfig\App\Models\Rate;
use Modules\Smsconfig\App\Models\SenderId;
use Modules\Smsconfig\App\Models\Mask;
use Modules\Users\App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerApiController extends Controller
{
  protected MessageRepositoryInterface $messageRepository;
  protected CustomerApiRepositoryInterface $customerApiRepository;
  use SmsCountTrait;

  public function __construct(
    MessageRepositoryInterface     $messageRepository,
    CustomerApiRepositoryInterface $customerApiRepository
  )
  {
    $this->messageRepository = $messageRepository;
    $this->customerApiRepository = $customerApiRepository;
  }

  public function getBalance(Request $request)
  {
    $userKey = $request->query('api_key');
    $userInfo = User::where('APIKEY', $userKey)->first();
    if (empty($userInfo)) {
      $response["error"] = true;
      $response["error_message"] = "API Key does not matched";
      $response["error_code"] = 1003;

      return response()->json($response, 200);
    } else {
      $response["error"] = false;
      $response["message"] = "Your current balance is " . $userInfo->available_balance . " Taka";
      $response["balance"] = $userInfo->available_balance;

      return response()->json($response, 200);
    }
  }

  public function getDLR()
  {
    $dlr = $this->customerApiRepository->getDLR();
    return response()->json($dlr);
  }

  public function getKey()
  {
    $key = $this->customerApiRepository->getKey();
    return response()->json($key);
  }

  public function getUnreadReplies()
  {
    $unreadReplies = $this->customerApiRepository->getUnreadReplies();
    return response()->json($unreadReplies);
  }

  private function sendMessageByPost(CustomerApiSendMessageRequest $request)
  {
    $sendMessage = $this->customerApiRepository->sendMessage();
    if ($sendMessage) {
      $response["error"] = false;
      $response["message_id"] = $sendMessage->orderid;
      $response["message"] = "Your SMS is Submitted";

      return response()->json($response, 200);
    } else {
      $response["error"] = true;
      $response["error_message"] = "Something Wrong";
      $response["error_code"] = 1013;

      return response()->json($response, 200);
    }
  }

  public function sendMessage(Request $request)
  {
    $userInfo = User::where('APIKEY', $request->api_key)->first();

    if ($request->isMethod('post')) {
      //$this->sendMessageByPost($request);
      if (!empty($userInfo)) {
        $sendMessage = $this->customerApiRepository->sendMessage($request, $userInfo);

        if ($sendMessage['error'] && $sendMessage['error_code'] == 1010) {
          $response["error"] = true;
          $response["error_message"] = $sendMessage['error_message'];
          $response["error_code"] = $sendMessage['error_code'];
          return response()->json($response, 200);
        }

        if ($sendMessage['error'] && $sendMessage['error_code'] == 1020) {
          $response["error"] = true;
          $response["error_message"] = $sendMessage['error_message'];
          $response["error_code"] = $sendMessage['error_code'];
          return response()->json($response, 200);
        }

        if ($sendMessage['error'] && $sendMessage['error_code'] == 1015) {
          $response["error"] = true;
          $response["error_message"] = "Something Wrong";
          $response["error_code"] = $sendMessage['error_code'];
          return response()->json($response, 200);
        }

        if ($sendMessage['error'] && $sendMessage['error_code'] == 1002) {
          $response["error"] = true;
          $response["error_message"] = "Sender ID not found";
          $response["error_code"] = $sendMessage['error_code'];
          return response()->json($response, 200);
        }

        if ($sendMessage) {
          $response["error"] = false;
          $response["message_id"] = $sendMessage['message_id'];
          $response["message"] = "Your SMS is Submitted";

          return response()->json($response, 200);
        } else {
          $response["error"] = true;
          $response["error_message"] = "Something Wrong";
          $response["error_code"] = 1013;

          return response()->json($response, 200);
        }
      } else {
        $response["error"] = true;
        $response["error_message"] = "API Key does not matched";
        $response["error_code"] = 1003;

        return response()->json($response, 200);
      }
    } else {
      $this->sendMessageByGet($request, $userInfo);
    }
  }

  private function sendMessageByGet($request, $userInfo)
  {
    $this->inputValidation($request);
    $numbers = $this->filterWrongRecipient($request->contacts);
    $userInfo = User::where('APIKEY', $request->api_key)->first();

    $sendMessage = $this->customerApiRepository->sendMessage($request, $userInfo);

    if ($sendMessage['error'] === false) {
      $response["error"] = false;
      $response["message_id"] = $sendMessage['message_id'];
      $response["message"] = "Your SMS is Submitted successfully.";

      echo json_encode($response);
    } else {
      $response["error"] = true;
      $response["error_message"] = "Something Wrong";
      $response["error_code"] = 1013;

      echo json_encode($response);
    }
  }

  private function inputValidation(Request $request)
  {
    if (empty($request->api_key) || empty($request->msg_type) || empty($request->senderid) || empty($request->contacts) || empty($request->msg)) {
      $response["error"] = true;
      $response["error_message"] = "Field Missing";
      $response["error_code"] = 1008;

      return response()->json($response, 200);
    }

    $user = User::where('APIKEY', $request->api_key)->first();
    if (empty($user)) {
      $response["error"] = true;
      $response["error_message"] = "API Key does not matched";
      $response["error_code"] = 1003;

      return response()->json($response, 200);
    }

    //CHECK SENDER ID
    $assignedSenderId = SenderId::where('senderID', $request->senderid)
      ->where('user_id', $user->id)
      ->where('status', '=', 'Active')->first();
    $userSenderId = SenderId::where('senderID', $request->senderid)
      ->where('user_id', $user->id)
      ->where('status', '=', 'Active')->first();
    $senderId = (isset($assignedSenderId->senderID) and !empty($assignedSenderId->senderID)) ? $assignedSenderId->senderID : (isset($userSenderId->senderID) && !empty($userSenderId->senderID) ? $userSenderId->senderID : '');

    if (empty($senderId)) {
      $response["error"] = true;
      $response["error_message"] = "Sender ID Not Found";
      $response["error_code"] = 1002;

      return response()->json($response, 200);
    }

    if ($user->status != 'ACTIVE') {
      $response["error"] = true;
      $response["error_message"] = "Unauthorised Access, User Inactive";
      $response["error_code"] = 1003;

      return response()->json($response, 200);
    }

    if (!empty($user->reseller_id)) {
      if ($user->reseller->status != 'ACTIVE') {
        $response["error"] = true;
        $response["error_message"] = "Unauthorised Access, User Inactive";
        $response["error_code"] = 1003;

        return response()->json($response, 200);
      }
    }

    $this->checkBalance($request, $user);

    return true;
  }

  private function checkBalance($request, $user)
  {
    $userInputRecipients = $request->contacts;
    $message = $request->msg;
    $numbers = $this->filterWrongRecipient($userInputRecipients);
    $totalNumber = count($numbers);
    $recipients = implode(',', $numbers);
    $smsInfo = $this->countSms($message);
    $countSms = $smsInfo->count;

    if (($totalNumber * $countSms) > $user->wallet->available_balance) {
      $response["error"] = true;
      $response["error_message"] = "Insufficient Balance";
      $response["error_code"] = 1007;

      return response()->json($response, 200);
    }

    return true;
  }

  public function filterWrongRecipient($recipients)
  {
    $error_sms = [];
    $pattern = "/(^(\+8801|8801|01|008801))[1|3-9]{1}(\d){8}$/";
    $explodeRecipients = explode(',', $recipients);
    $numbers = [];
    foreach ($explodeRecipients as $explodeRecipient) {
      if (preg_match($pattern, $explodeRecipient)) {
        $numbers[] = $explodeRecipient;
      } else {
        $error_sms[] = [
          'wrong-recipient' => '(' . $explodeRecipient . ') is wrong recipient. Message can\'t send to this recipient'
        ];
      }
    }
    return $numbers;
  }

  public function getInbox(Request $request)
  {
    $receivers = $request->query('receiver');
    $receivers = explode(',', $receivers);
    $inbox = DB::table('inbox')->whereIn('receiver', $receivers)->where('is_aggregator_read', 0)->get();
    if ($inbox->isEmpty()) {
      $response["error"] = true;
      $response["error_message"] = "No messages found for the provided receivers.";
      $response["error_code"] = 400;

      return response()->json($response, 200);
    } else {
      //update is_aggregator_read to 1
      DB::table('inbox')
        ->whereIn('receiver', $receivers)
        ->update(['is_aggregator_read' => 1]);

      $response["error"] = false;
      $response["messages"] = $inbox;

      return response()->json($response, 200);
    }

  }


  public function getUserInfo(Request $request)
  {
    //get user by api_key
    $userInfo = User::where('APIKEY', $request->api_key)->first();

    $response = [];

    if ($userInfo) {
      $response["error"] = false;
      $response["user"] = [
        "id" => $userInfo->id,
        "name" => $userInfo->name,
        "username" => $userInfo->username,
        "mobile" => $userInfo->mobile,
        "email" => $userInfo->email,
        'api_key' => $userInfo->APIKEY,
        "address" => $userInfo->address,
        "my_sms_nonmasking_rate" => isset($userInfo->smsRate->nonmasking_rate) ? number_format($userInfo->smsRate->nonmasking_rate, 2) : null,
        "my_sms_masking_rate" => isset($userInfo->smsRate->masking_rate) ? number_format($userInfo->smsRate->masking_rate, 2) : null,
        "sms_rate_list" => $userInfo->rateList,
        "sms_senderId_list" => $userInfo->senderIds,
        "sms_mask_list" => $userInfo->masks
      ];
    } else {
      $response["error"] = true;
      $response["error_message"] = "User not found";
      $response["error_code"] = 404;
    }

    return response()->json($response, 200);
  }

  public function createUser(Request $request)
  {
    $userInfo = User::where('APIKEY', $request->api_key)->first();

    if (!$userInfo) {
      return response()->json([
        'error' => true,
        'error_message' => 'User not found',
        'error_code' => 404,
      ], 404);
    }
    
    if($userInfo->id_user_group != 2){
      return response()->json([
        'error' => true,
        'error_message' => 'Not Permitted',
      ], 403);
    }

    $validator = Validator::make($request->all(), [
      'name' => ['required', 'string', 'max:255'],
      'username' => ['required', 'string', 'max:255', 'unique:users,username'],
      'mobile' => ['required', 'string', 'max:15'],
      'email' => ['required', 'email', 'max:255', 'unique:users,email'],
      'password' => ['required', 'string', 'min:8'],
      'address' => ['required', 'string', 'max:255'],
      'sms_rate_id' => ['required', 'integer'],

      // One of them must be provided
      'sms_senderId' => ['required_without:sms_mask', 'nullable', 'integer', 'exists:senderid,id'],
      'sms_mask' => ['required_without:sms_senderId', 'nullable', 'integer', 'exists:mask,id'],
    ], [
      'sms_senderId.required_without' => 'Either sms sender ID or sms mask is required.',
      'sms_mask.required_without' => 'Either sms sender ID or sms mask is required.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'error' => true,
        'error_message' => 'Validation failed',
        'errors' => $validator->errors(),
      ], 422);
    }

    $data = $validator->validated();


    // Validate SMS Rate belongs to current user
    $smsRate = Rate::find($request->sms_rate_id);
    if (!$smsRate || $smsRate->created_by != $userInfo->id) {
      return response()->json([
        'error' => true,
        'error_message' => 'SMS rate not found or unauthorized',
      ], 403);
    }
    
    $senderId = null;
    $mask = null;

    // Validate Sender ID
    if ($request->filled('sms_senderId')) {
      $senderId = SenderId::find($request->sms_senderId);
      if (!$senderId || $senderId->user_id != $userInfo->id) {
        return response()->json([
          'error' => true,
          'error_message' => 'Sender ID not found or unauthorized',
        ], 403);
      }
    }

    // Validate Mask
    if ($request->filled('sms_mask')) {
      $mask = Mask::find($request->sms_mask);
      if (!$mask || $mask->user_id != $userInfo->id) {
        return response()->json([
          'error' => true,
          'error_message' => 'Mask not found or unauthorized',
        ], 403);
      }
    }

    try {
      $data['password'] = Hash::make($data['password']);
      $data['created_by'] = Auth::id() ?? $userInfo->id;
      $data['APIKEY'] = Hash::make($data['password'] . $data['username']);
      $data['tps'] = 50;
      $data['id_user_group'] = 4;

      $customer = User::create($data);

      // Update senderId and mask with new user ID
      if (!empty($senderId)) {
        $senderId->user_id = $customer->id;
        $senderId->save();
      }

      if (!empty($mask)) {
        $mask->user_id = $customer->id;
        $mask->save();
      }

      return response()->json([
        'error' => false,
        'message' => 'User created successfully',
        'user' => [
          'id' => $customer->id,
          'name' => $customer->name,
          'username' => $customer->username,
          'mobile' => $customer->mobile,
          'email' => $customer->email,
          'api_key' => $customer->APIKEY,
          'address' => $customer->address,
          'sms_nonmasking_rate' => isset($userInfo->smsRate->nonmasking_rate) ? number_format($userInfo->smsRate->nonmasking_rate, 2) : null,
          'sms_masking_rate' => isset($userInfo->smsRate->masking_rate) ? number_format($userInfo->smsRate->masking_rate, 2) : null,
          'sms_mask' => $customer->mask ?? null,
          'sms_senderId' => $customer->senderIds ?? null,
        ]
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'error' => true,
        'error_message' => 'Failed to create user: ' . $e->getMessage(),
      ], 500);
    }
  }

  public function updateUser(Request $request, $id)
  {
    $userInfo = User::where('APIKEY', $request->api_key)->first();
    if (!$userInfo) {
      return response()->json([
        'error' => true,
        'error_message' => 'User not found',
        'error_code' => 404,
      ], 404);
    }

    if($userInfo->id_user_group != 2){
      return response()->json([
        'error' => true,
        'error_message' => 'Not Permitted',
      ], 403);
    }

    $customer = User::find((int)$id);
    if (!$customer) {
      return response()->json([
        'error' => true,
        'error_message' => 'Target user not found',
        'error_code' => 404,
      ], 404);
    }

    // Improved validation logic
    $validator = Validator::make($request->all(), [
      'name' => ['required', 'string', 'max:255'],
      'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($id)],
      'mobile' => ['required', 'string', 'max:15'],
      'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
      'password' => ['nullable', 'string', 'min:8'],
      'address' => ['required', 'string', 'max:255'],
      'sms_rate_id' => ['required', 'integer'],

      // One of them must be provided
      'sms_senderId' => ['required_without:sms_mask', 'nullable', 'integer', 'exists:senderid,id'],
      'sms_mask' => ['required_without:sms_senderId', 'nullable', 'integer', 'exists:mask,id'],
    ], [
      'sms_senderId.required_without' => 'Either sms sender ID or sms mask is required.',
      'sms_mask.required_without' => 'Either sms sender ID or sms mask is required.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'error' => true,
        'error_message' => 'Validation failed',
        'errors' => $validator->errors(),
      ], 422);
    }

    $data = $validator->validated();

    $senderId = $request->filled('sms_senderId') ? SenderId::find((int)$request->sms_senderId) : null;
    $mask = $request->filled('sms_mask') ? Mask::find((int)$request->sms_mask) : null;

    $createdUserIds = User::where('created_by', $userInfo->id)->pluck('id')->toArray();

    if ($request->filled('sms_senderId')) {
      $senderId = SenderId::find($request->sms_senderId);
      if ((int)$senderId->user_id !== (int)$customer->id) {
        return response()->json([
          'error' => true,
          'error_message' => 'Sender ID is already used by another user',
        ], 403);
      }

      if (!$senderId || !in_array((int)$senderId->user_id, array_merge([(int)$customer->id], array_map('intval', $createdUserIds)), true)) {
        return response()->json([
          'error' => true,
          'error_message' => 'Sender ID not found or unauthorized',
        ], 403);
      }
    }

    if ($request->filled('sms_mask')) {
      $mask = Mask::find($request->sms_mask);
      if (!$mask || !in_array((int)$mask->user_id, array_merge([(int)$customer->id], array_map('intval', $createdUserIds)), true)) {
        return response()->json([
          'error' => true,
          'error_message' => 'Mask not found or unauthorized',
        ], 403);
      }
    }

    try {
      DB::transaction(function () use ($customer, $data, $senderId, $mask) {
        if (!empty($data['password'])) {
          $data['password'] = Hash::make($data['password']);
        } else {
          unset($data['password']);
        }

        $customer->update($data);

        if ($senderId) {
          $senderId->user_id = $customer->id;
          $senderId->save();
        }
        if ($mask) {
          $mask->user_id = $customer->id;
          $mask->save();
        }
      });

      //$customer->refresh();

      return response()->json([
        'error' => false,
        'message' => 'User updated successfully',
        'user' => [
          'id' => $customer->id,
          'name' => $customer->name,
          'username' => $customer->username,
          'mobile' => $customer->mobile,
          'email' => $customer->email,
          'address' => $customer->address,
          'sms_nonmasking_rate' => optional($userInfo->smsRate)->nonmasking_rate
            ? number_format($userInfo->smsRate->nonmasking_rate, 2) : null,
          'sms_masking_rate' => optional($userInfo->smsRate)->masking_rate
            ? number_format($userInfo->smsRate->masking_rate, 2) : null,
          'sms_mask' => $customer->mask ?? null,
          'sms_senderId' => $customer->senderIds ?? null,
        ]
      ]);

    } catch (\Throwable $e) {
      return response()->json([
        'error' => true,
        'error_message' => 'Failed to update user: ' . $e->getMessage(),
      ], 500);
    }
  }


  //incoming message list
  public function incomingMessagesList(Request $request)
  {
    try {
      $userInfo = User::where('APIKEY', $request->api_key)->first();

      if (!$userInfo) {
        return response()->json([
          'error' => true,
          'error_message' => 'User not found',
          'error_code' => 404,
        ], 404);
      }

      // Fetch sender IDs if not already an attribute
      $senderIds = SenderId::where('user_id', $userInfo->id)->pluck('senderID')->toArray();

      if (empty($senderIds)) {
        return response()->json([
          'error' => false,
          'messages' => [],
          'message' => 'No sender IDs found for this user.'
        ]);
      }

      // Retrieve inbox messages where receiver is in the senderIds list
      $messages = DB::table('inbox')
        ->whereIn('receiver', $senderIds)
        ->orderByDesc('id')
        ->get();

      return response()->json([
        'error' => false,
        'messages' => $messages,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'error' => true,
        'error_message' => 'Failed to retrieve messages: ' . $e->getMessage(),
      ], 500);
    }
  }


  public function incomingMessages(Request $request)
  {
    try {
      $data = $request->all();
      DB::table('inbox')->insert([
        'sender' => $data['sender'],
        'operator_prefix' => $data['operator_prefix'],
        'receiver' => $data['receiver'],
        'message' => $data['message'],
        'smscount' => $data['smscount'],
        'part_no' => $data['part_no'],
        'total_parts' => $data['total_parts'],
        'reference_no' => $data['reference_no'],
        'read' => $data['read'],
        'created_at' => now(),
        'updated_at' => now()
      ]);

      Log::channel('sms')->info('Incoming message saved successfully');

    } catch (\Exception $e) {
      Log::error($e->getMessage());
    }
  }

}

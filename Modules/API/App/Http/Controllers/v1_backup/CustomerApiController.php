<?php

namespace Modules\API\App\Http\Controllers\v1_backup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\API\App\Repositories\CustomerApiRepositoryInterface;

class CustomerApiController extends Controller
{
  protected CustomerApiRepositoryInterface $customerApiRepository;
  use SmsCountTrait;

  public function __construct(CustomerApiRepositoryInterface $customerApiRepository)
  {
    $this->customerApiRepository = $customerApiRepository;
  }

  public function sendSingle(Request $request)
  {
    //check request get or post
    if ($request->isMethod('get')) {
      $apiKey = $request->query('api_key');
      $senderId = $request->query('senderid');
      $message = $request->query('msg');
      $contacts = $request->query('contacts');
      $msgType = $request->query('type');
    }else{
      $apiKey = $request->input('api_key');
      $senderId = $request->input('senderid');
      $message = $request->input('msg');
      $contacts = $request->input('contacts');
      $msgType = $request->input('type');
    }

    //validate input
    if (empty($apiKey) || empty($senderId) || empty($message) || empty($contacts) || empty($msgType)) {
      $response["error"] = true;
      $response["error_message"] = "Field Missing";
      $response["error_code"] = 1008;

      return response()->json($response, 200);
    }

    $user = $this->customerApiRepository->checkValidUser($apiKey);
    if (!$user) {
      $response["error"] = true;
      $response["error_message"] = "API Key does not matched";
      $response["error_code"] = 1003;

      return response()->json($response, 200);
    }

    if (!$this->customerApiRepository->checkSenderId($senderId, $user)) {
      $response["error"] = true;
      $response["error_message"] = "Sender ID Not Found";
      $response["error_code"] = 1002;

      return response()->json($response, 200);
    }

    if (!$this->customerApiRepository->checkBalance($request, $user)) {
      $response["error"] = true;
      $response["error_message"] = "Insufficient balance";
      $response["error_code"] = 1020;

      return response()->json($response, 200);
    }

    $numbers = $this->customerApiRepository->filterWrongRecipient($contacts);

    $messagePayload['sender_id'] = $senderId;
    $messagePayload['message_text'] = $message;
    $messagePayload['source'] = 'API';
    $messagePayload['content_type'] = $msgType;
    $messagePayload['campaign_name'] = 'API';
    $messagePayload['totalPhoneNumber'] = 1;
    $messagePayload['recipient_number'] = $numbers;
    $messagePayload['user_id'] = $user->id;
    $messagePayload['campaign_id'] = 'API';

    $sendMessage = $this->customerApiRepository->saveRegularMessage($messagePayload, $user);

    if ($sendMessage) {
      $response["error"] = false;
      $response["message_id"] = $sendMessage->orderid;
      $response["message"] = "Your SMS is submitted and being processed.";

      return response()->json($response, 200);
    } else {
      $response["error"] = true;
      $response["error_message"] = "Something Wrong";
      $response["error_code"] = 1013;

      return response()->json($response, 200);
    }
  }

  // Single number, multiple messages
  public function sendSingleMultipleMessages(Request $request)
  {
    if ($request->isMethod('get')) {
      $apiKey = $request->query('api_key');
      $senderId = $request->query('senderid');
      $messages = $request->query('msg', []);
      $contacts = $request->query('contacts');
      $msgType = $request->query('type');
    }else{
      $apiKey = $request->input('api_key');
      $senderId = $request->input('senderid');
      $messages = $request->input('msg');
      $contacts = $request->input('contacts');
      $msgType = $request->input('type');
    }

    //validate input
    if (empty($apiKey) || empty($senderId) || empty($messages) || empty($contacts) || empty($msgType)) {
      $response["error"] = true;
      $response["error_message"] = "Field Missing";
      $response["error_code"] = 1008;

      return response()->json($response, 200);
    }

    $user = $this->customerApiRepository->checkValidUser($apiKey);
    if (!$user) {
      $response["error"] = true;
      $response["error_message"] = "API Key does not matched";
      $response["error_code"] = 1003;

      return response()->json($response, 200);
    }

    if (!$this->customerApiRepository->checkSenderId($senderId, $user)) {
      $response["error"] = true;
      $response["error_message"] = "Sender ID Not Found";
      $response["error_code"] = 1002;

      return response()->json($response, 200);
    }

    $numbers = $this->customerApiRepository->filterWrongRecipient($contacts);

    $results = [];
    foreach ($messages as $message) {
      $request->contacts = $numbers;
      $request->msg = $message;
      if (!$this->customerApiRepository->checkBalance($request, $user)) {
        $response["error"] = true;
        $response["error_message"] = "Insufficient balance";
        $response["error_code"] = 1020;

        return response()->json($response, 200);
      }

      $messagePayload['sender_id'] = $senderId;
      $messagePayload['message_text'] = $message;
      $messagePayload['source'] = 'API';
      $messagePayload['content_type'] = $msgType;
      $messagePayload['campaign_name'] = 'API';
      $messagePayload['totalPhoneNumber'] = 1;
      $messagePayload['recipient_number'] = $numbers;
      $messagePayload['user_id'] = $user->id;
      $messagePayload['campaign_id'] = 'API';

      $sendMessage = $this->customerApiRepository->saveRegularMessage($messagePayload, $user);

      $results [] = [
        'message_id' => $sendMessage->orderid,
      ];
    }

    if ($results) {
      $response["error"] = false;
      $response["message_id"] = $results;
      $response["message"] = "Your SMS is Submitted successfully.";

      return response()->json($response, 200);
    } else {
      $response["error"] = true;
      $response["error_message"] = "Something Wrong";
      $response["error_code"] = 1013;

      return response()->json($response, 200);
    }
  }

  // Multiple numbers, multiple messages
  public function sendMultipleMultipleMessages(Request $request)
  {
    if ($request->isMethod('get')) {
      $apiKey = $request->query('api_key');
      $senderId = $request->query('senderid');
      $messageItems = $request->query('msg', []);
      $msgType = $request->query('type');
    }else {
      $apiKey = $request->input('api_key');
      $senderId = $request->input('senderid');
      $messageItems = $request->input('msg');
      $msgType = $request->input('type');
    }

    //validate input
    if (empty($apiKey) || empty($senderId) || empty($messageItems) || empty($msgType)) {
      $response["error"] = true;
      $response["error_message"] = "Field Missing";
      $response["error_code"] = 1008;

      return response()->json($response, 200);
    }

    $user = $this->customerApiRepository->checkValidUser($apiKey);
    if (!$user) {
      $response["error"] = true;
      $response["error_message"] = "API Key does not matched";
      $response["error_code"] = 1003;

      return response()->json($response, 200);
    }

    if (!$this->customerApiRepository->checkSenderId($senderId, $user)) {
      $response["error"] = true;
      $response["error_message"] = "Sender ID Not Found";
      $response["error_code"] = 1002;

      return response()->json($response, 200);
    }

    $results = [];
    foreach ($messageItems as $item) {
      $request->contacts = $item['to'];
      $request->msg = $item['message'];
      if (!$this->customerApiRepository->checkBalance($request, $user)) {
        $response["error"] = true;
        $response["error_message"] = "Insufficient balance";
        $response["error_code"] = 1020;

        return response()->json($response, 200);
      }

      $numbers = $this->customerApiRepository->filterWrongRecipient($item['to']);
      $messagePayload['sender_id'] = $senderId;
      $messagePayload['message_text'] = $item['message'];
      $messagePayload['source'] = 'API';
      $messagePayload['content_type'] = $msgType;
      $messagePayload['campaign_name'] = 'API';
      $messagePayload['totalPhoneNumber'] = 1;
      $messagePayload['recipient_number'] = $numbers;
      $messagePayload['user_id'] = $user->id;
      $messagePayload['campaign_id'] = 'API';

      $sendMessage = $this->customerApiRepository->saveRegularMessage($messagePayload, $user);

      $results [] = [
        'message_id' => $sendMessage->orderid,
      ];
    }
    if ($results) {
      $response["error"] = false;
      $response["message_id"] = $results;
      $response["message"] = "Your SMS is Submitted successfully.";

      return response()->json($response, 200);
    } else {
      $response["error"] = true;
      $response["error_message"] = "Something Wrong";
      $response["error_code"] = 1013;

      return response()->json($response, 200);
    }
  }

  public function getBalance()
  {
    $balance = $this->customerApiRepository->getBalance();
    return response()->json($balance);
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
}


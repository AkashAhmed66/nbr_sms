<?php

namespace Modules\API\App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\API\App\Http\Requests\CustomerApiSendMessageRequest;
use Modules\API\App\Repositories\CustomerApiRepositoryInterface;
use Modules\Messages\App\Repositories\MessageRepositoryInterface;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Smsconfig\App\Models\SenderId;
use Modules\Users\App\Models\User;

class CustomerApiController extends Controller
{
  protected MessageRepositoryInterface $messageRepository;
  protected CustomerApiRepositoryInterface $customerApiRepository;
  use SmsCountTrait;

  public function __construct(
    MessageRepositoryInterface $messageRepository,
    CustomerApiRepositoryInterface $customerApiRepository
  ) {
    $this->messageRepository = $messageRepository;
    $this->customerApiRepository = $customerApiRepository;
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
        //dd($sendMessage['message_id']);
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

      echo  json_encode($response);
    } else {
      $response["error"] = true;
      $response["error_message"] = "Something Wrong";
      $response["error_code"] = 1013;

      echo  json_encode($response);
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
      ->where('assigned_user_id', $user->id)
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
}

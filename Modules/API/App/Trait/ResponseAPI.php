<?php

namespace Modules\API\App\Trait;

trait ResponseAPI
{
  public $successCode = 1000;
  public $successMessage = "Success";
  public $IPBlacklistCode = 1001;
  public $IPBlacklistMessage = "IP Blacklist";
  public $invalidUsernameCode = 1002;
  public $invalidUsernameMessage = "Invalid Username";
  public $invalidPasswordCode = 1003;
  public $invalidPasswordMessage = "Invalid Password";
  public $parameterMissingCode = 1004;
  public $parameterMissingMessage = "Parameter missing";
  public $invalidParameterCode = 1005;
  public $invalidParameterMessage = "Invalid Parameter";
  public $CLIMaskingInvalidCode = 1006;
  public $CLIMaskingInvalidMessage = "CLI/Masking Invalid";
  public $accountBarredCode = 1007;
  public $accountBarredMessage = "Account Barred";
  public $insufficientBalanceCode = 1008;
  public $insufficientBalanceMessage = "Insufficient Balance";
  public $DNDUserCode = 1009;
  public $DNDUserMessage = "DND User";
  public $invalidMSISDNCode = 1010;
  public $invalidMSISDNMessage = "Invalid MSISDN";
  public $duplicateTransactionIDCode = 1011;
  public $duplicateTransactionIDMessage = "Duplicate Transaction ID";
  public $messagelengthsExceedCode = 1012;
  public $messagelengthsExceedMessage = "Message lengths exceed";
  public $noRequestFoundCode = 1013;
  public $noRequestFoundMessage = "No Request Found";
  public $deliveryPendingCode = 1014;
  public $deliveryPendingMessage = "Delivery Pending";
  public $TPSLimitExceededCode = 1015;
  public $TPSLimitExceededMessage = "TPS Limit Exceeded";
  public $numberBarredCode = 1016;
  public $numberBarredMessage = "Number Barred";
  public $APIisNotAllowedForUserCode = 1017;
  public $APIisNotAllowedForUserMessage = "API is not allowed for user";
  public $noLiveCampaignCode = 1018;
  public $noLiveCampaignMessage = "No Live Campaign";
  public $messagebodyInvalidCode = 1019;
  public $messagebodyInvalidMessage = "Messagebody Invalid";
  public $internalServerErrorCode = 1020;
  public $internalServerErrorMessage = "Internal Server Error";
  public $alloweDcampaignsLimitExceededCode = 1050;
  public $alloweDcampaignsLimitExceededMessage = "Allowed Campaigns Limit Exceeded";
  public $allowedSMSQuotaIsCompletedCode = 1051;
  public $allowedSMSQuotaIsCompletedMessage = "Allowed SMS Quota is Completed";
  public $submissionRecordNotFoundCode = 1052;
  public $submissionRecordNotFoundMessage = "Submission Record Not Found";
  public $invalidTransactionIdCode = 1053;
  public $invalidTransactionIdMessage = "Invalid Transaction Id";
  public $MSISDNLimitExceededCode = 1054;
  public $MSISDNLimitExceededMessage = "MSISDN Limit Exceeded";

  /**
   * Core of response
   *
   * @param string $message
   * @param array|object $data
   * @param integer $statusCode
   * @param boolean $isSuccess
   */
  public function coreResponse($message, $data = null, $statusCode, $isSuccess = true)
  {
    // Check the params
    if (!$message) return response()->json(['message' => 'Message is required'], 500);

    // Send the response
    if ($isSuccess) {
      return response()->json([
        'message' => $message,
        'error' => false,
        'code' => $statusCode,
        'results' => $data
      ], $statusCode);
    } else {
      return response()->json([
        'message' => $message,
        'error' => true,
        'code' => $statusCode,
      ], $statusCode);
    }
  }

  /**
   * Send any success response
   *
   * @param string $message
   * @param array|object $data
   * @param integer $statusCode
   */
  public function success($message, $data, $statusCode = 200)
  {
    return $this->coreResponse($message, $data, $statusCode);
  }

  /**
   * Send any error response
   *
   * @param string $message
   * @param integer $statusCode
   */
//    public function error($message, $statusCode = 500)
//    {
//        return $this->coreResponse($message, null, $statusCode, false);
//    }

  public function apiResponse($statusCode, $message, $clienttransid, $extraElements = [], $httpStatusCode = 200, $messageIDs = null)
  {
    return response()->json(
      [
        'statusInfo' =>
          array_merge([
            'statusCode' => "".$statusCode,
            'errordescription' => $message,
            'clienttransid' => $clienttransid,
            'messageIDs' => $messageIDs
          ], $extraElements)
      ]
      ,
      $httpStatusCode
    );
  }

  public function checkBalanceResponse($statusCode, $message, $clienttransid, $extraElements = [], $httpStatusCode = 200)
  {
    return response()->json(
      [
        'statusInfo' =>
          array_merge([
            'statusCode' => "".$statusCode,
            'errordescription' => $message,
            'clienttransid' => $clienttransid
          ], $extraElements)
      ]
      ,
      $httpStatusCode
    );
  }
}

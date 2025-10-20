<?php

namespace App\Trait;

trait MakingPayloadTrait
{
    public function makeInfozilionPayload($msg)
    {
        $map = [
            'gp' => ['88017', '88013', '017', '013'],
            'bl' => ['88019', '88014', '019', '014'],
            'rb' => ['88018', '88016', '018', '016'],
            'tt' => ['88015', '015'],
        ];

        $key = null;

        foreach ($map as $k => $prefixes) {
            foreach ($prefixes as $prefix) {
                if (str_starts_with($msg->recipient, $prefix)) {
                    $key = $k;
                    break 2;
                }
            }
        }

        $billMsisdn = null;
        $operator_password = null;
        $operator_username = null;

        if ($key == 'gp') {
          $billMsisdn = env('AGGREGATOR_GRAMEENPHONE_BILL_MSISDN');
          $operator_username = env('GRAMEENPHONE_API_USERNAME');
          $operator_password = env('GRAMEENPHONE_API_PASSWORD');
        } else if ($key == 'bl') {
          $billMsisdn = env('AGGREGATOR_BANGLALINK_BILL_MSISDN');
          $operator_username = env('BANGLALINK_API_USERNAME');
          $operator_password = env('BANGLALINK_API_PASSWORD');
        } else if ($key == 'rb') {
          $billMsisdn = env('AGGREGATOR_ROBI_BILL_MSISDN');
          $operator_username = env('ROBI_API_USERNAME');
          $operator_password = env('ROBI_API_PASSWORD');
        } else if ($key == 'tt') {
          $billMsisdn = env('AGGREGATOR_TELETALK_BILL_MSISDN');
          $operator_username = env('TELETALK_API_USERNAME');
          $operator_password = env('TELETALK_API_PASSWORD');
        }

        if($msg->is_masking == '1') {
            $payload = [
              "username" => $operator_username,
              "password" => $operator_password,
              "billMsisdn" => $billMsisdn,
              "usernameSecondary" => env('AGGREGATOR_SECONDARY_USERNAME'),
              "passwordSecondary" => env('AGGREGATOR_SECONDARY_PASSWORD'),
              "billMsisdnSecondary" => env('AGGREGATOR_SECONDARY_BILL_MSISDN'),
              "apiKey" => env('AGGREGATOR_API_KEY'),
              "cli" => $msg->senderID,
              "msisdnList" => [$this->addPrefix($msg->recipient)],
              "transactionType" => 'T',
              "messageType" => $msg->isunicode == '1' ? '3' : '1',
              "isLongSMS" => false,
              "message" => $msg->message,
              "campaignId" => null,
            ];
        } else {
            $payload = [
              "username" => env('AGGREGATOR_USERNAME'),
              "password" => env('AGGREGATOR_PASSWORD'),
              "billMsisdn" => $msg->recipient ?? env('AGGREGATOR_BILL_MSISDN'),
              "usernameSecondary" => env('AGGREGATOR_SECONDARY_USERNAME'),
              "passwordSecondary" => env('AGGREGATOR_SECONDARY_PASSWORD'),
              "billMsisdnSecondary" => env('AGGREGATOR_SECONDARY_BILL_MSISDN'),
              "apiKey" => env('AGGREGATOR_API_KEY'),
              "cli" => $msg->senderID,
              "msisdnList" => [$this->addPrefix($msg->recipient)],
              "transactionType" => 'T',
              "messageType" => $msg->isunicode == '1' ? '3' : '1',
              "isLongSMS" => false,
              "message" => $msg->message,
              "campaignId" => null,
            ];
        }

        return $payload;
    }

    private function addPrefix($number)
    {
        if (str_starts_with($number, '880')) {
            return $number;
        } elseif (str_starts_with($number, '0')) {
            return '88' . $number;
        }
    }
}

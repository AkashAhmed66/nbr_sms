<?php

namespace Modules\Reports\App\Trait;

trait DataTableTrait
{
  public function getTableHeader($tableName = ''): array
  {
    $headers = [
      'last-2days-failed-sms-list' => [
        "userId" => "User ID",
        "mask" => "Sender ID",
        "destmn" => "Mobile",
        "message" => "Message",
        'write_time' => "Write Time",
        'sent_time' => "Sent Time",
        // 'last_updated' => "Last Update",
        'smscount' => "SMS Count",
        "rate" => "Rate(BDT)",
        "sms_cost" => "Charge(BDT)",
        "error_code" => "Error Code",
        "error_message" => "Reason",
        "api_web" => "API/WEB",
        "campaign" => "Campaign"
      ],
      'failed-archived-sms-list' => [
        "userId" => "User ID",
        "mask" => "Sender ID",
        "destmn" => "Mobile",
        "message" => "Message",
        'write_time' => "Write Time",
        'sent_time' => "Sent Time",
        // 'last_updated' => "Last Update",
        'smscount' => "SMS Count",
        'status' => 'Status',
        "error_message" => "Reason",
      ],
      'last-2days-sms-list' => [
        'id' => "#",
        "mask" => "Sender ID",
        "name" => "Username",
        "destmn" => "Mobile",
        "message" => "Message",
        'write_time' => "Write Time",
        //'sent_time' => "Sent Time",
        // 'last_updated' => "Last Update",
        'smscount' => "SMS Count",
        "rate" => "Rate(BDT)",
        "sms_cost" => "Charge(BDT)",
        "status" => "Status",
        "source" => "API/WEB",
        "retry_count" => "Retry Count",
        //        "campaign_name" => "Campaign",
        "error_code" => "Status Code",
        "error_message" => "Status Message"
      ],
      'archived-sms-list' => [
        'id' => "#",
        "mask" => "Sender ID",
        "name" => "Username",
        "destmn" => "Mobile",
        "message" => "Message",
        'write_time' => "Write Time",
        'sent_time' => "Sent Time",
        // 'last_updated' => "Last Update",
        'smscount' => "SMS Count",
        "rate" => "Rate(BDT)",
        "sms_cost" => "Charge(BDT)",
        "status" => "Status",
        "source" => "API/WEB",
        "retry_count" => "Retry Count",
//        "campaign_name" => "Campaign",
        "error_code" => "Status Code",
        "error_message" => "Status Message"
      ],
      'summery-log' => [
        "userID" => "User ID",
        "name" => "User Name",
        'totalsms' => "Total SMS",
        'rate' => "Rate",
        "total_amount_deduction" => "Total Amount"
      ],
      'day-wise-log' => [
        "userId" => "User ID",
        "date_range" => "Date",
        'message_count' => "Total SMS",
        "total_cost" => "Total Amount",
        "name" => "Username",
      ],
      'total-sms-log' => [
        "userId" => "User ID",
        "senderID" => "Sent By",
        "message_count" => "Total SMS",
        "operator_prefix" => "Operator Name"
      ],
      'btrc-report-list' => [
        "operator" => "Operator",
        "incoming" => "Incoming",
        "outgoing" => "Outgoing"
      ],
      'inbox-list' => [
        "sender" => "Sender",
        "receiver" => "Receiver",
        "operator_prefix" => "Operator",
        "message" => "Message",
        "received_time" => "Received Time",
        ]

    ];

    return $headers[$tableName] ?? [];
  }
}

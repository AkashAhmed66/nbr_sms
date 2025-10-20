<?php

namespace Modules\Campaign\App\Trait;

trait DataTableTrait
{
  public function getTableHeader($tableName = ''): array
  {
    $headers = [
      'schedule-message-list' => [
        "id" => "#", "campaign_name" => "Campaign", 'username' => "User", "senderID" => "Sender ID", "recipientList" => "Recipient", "total_recipient" => "Total Recipient", "message" => "Message",
        "date" => "Request Time", "scheduleDateTime" => "Schedule Time", "status" => "Status", "error" => "File Error"
      ],
      'running-message-list' => [
        'username' => "User",
        "senderID" => "Sender ID",
        "campaign_id" => "Campaign ID",
        "message" => "Message",
        "date" => "Request Time",
        "campaign_name" => "Campaign",
        "status" => "Status",
        "error" => "File Error",
        "action" => "Action"
      ],
      'archive-message-list' => [
        'username' => "User",
        "senderID" => "Sender ID",
        "recipientList" => "Recipient",
        "message" => "Message",
        "date" => "Request Time",
        "campaign_name" => "Campaign",
        "status" => "Status",
        "error" => "File Error",
      ]
    ];

    return $headers[$tableName] ?? [];
  }
}

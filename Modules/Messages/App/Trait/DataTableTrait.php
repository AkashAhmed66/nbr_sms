<?php

namespace Modules\Messages\App\Trait;

trait DataTableTrait
{
  public function getTableHeader($tableName = ''): array
  {
    $headers = [
      'templates-list' => [
        "id" => "#",
        'title' => "Title",
        "description" => "Message",
        "status" => "Status",
        'action' => 'Manage'
      ],
      'sms-inbox-list' => [
        "id" => "#",
        'senderID' => 'sender ID',
        'message' => 'Message',
        'recipient' => 'Recipient',
        'total_cost' => 'Cost',
        'status' => 'Status',
        'write_time' => 'Write Time',
       // 'sent_time' => ' Sent Time'

      ]
    ];

    return $headers[$tableName] ?? [];
  }
}

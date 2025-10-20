<?php

namespace Modules\Phonebook\App\Trait;

trait DataTableTrait
{
  public function getTableHeader($tableName = ''): array
  {
    $headers = [
      'group-list' => [
        "id" => "#",
        "name" => "Group Name",
        'username' => "User",
        'type' => "Type",
        'resellername' => "Reseller",
        'phone_count' => 'Total Number',
        'status' => 'Status',
        'action' => 'Manage'
      ],
      'dnd-list' => [
        "id" => "#",
        "phone" => "Phone",
        "status" => "Status",
        "action" => "Action"
      ],
      'phonebook-list' => [
        "id" => "#",
        "group" => "Group",
        "phone" => "Phone Number",
        "status" => "Status",
        "action" => "Action"
      ]
    ];

    return $headers[$tableName] ?? [];
  }
}

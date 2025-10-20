<?php

namespace Modules\Users\App\Trait;

trait DataTableTrait
{
  public function getTableHeader($tableName = ''): array
  {
    $headers = [
      'user-group-list' => [
        "id" => "#",
        'title' => "Group Title",
        "comment" => "Comment",
        "status" => "Status",
        'action' => 'Manage'
      ],
      'user-list' => [
        'id' => "#",
        'group' => 'User Type',
        'name' => 'Name',
        'username' => 'User Name',
        'email' => 'Email',
        'mobile' => 'Mobile No',
        'sms_rate' => 'SMS Rate',
        'available_balance' => 'Current Balance',
        'status' => 'Status',
        'action' => 'Manage'
      ],
      'reseller-list' => [
        'id' => "#",
        'reseller_name' => 'Name',
        'address' => 'Address',
        'sms_rate' => 'SMS Rate',
        'available_balance' => 'Available Balance',
        'due' => 'Due',
        'phone' => 'Phone',
        'tps' => 'TPS',
        'status' => 'Status',
        'action' => 'Manage'
      ],
    ];

    return $headers[$tableName] ?? [];
  }
}

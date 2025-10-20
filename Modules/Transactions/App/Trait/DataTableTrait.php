<?php

namespace Modules\Transactions\App\Trait;

trait DataTableTrait
{
  public function getTableHeader($tableName = ''): array
  {
    $headers = [
      'users-wallet-list' => [
        "id" => "#",
        "username" => "User Name",
        'balance' => "Balance",
        'balance_type' => "Balance Type",
        'date' => 'Date Time'
      ],
      'online-list' => [
        "id" => "#",
        "username" => "User Name",
        'amount' => "Balance",
        'created_at' => 'Date'
      ],
      'user-transfer-list' => [
        'id' => "#",
        'user_name' => 'User Name',
        'deposit_amount' => 'Deposit Amount',
        'dr_cr' => 'DR/CR',
        'created_at' => 'Deposit Date',
        'approved_date' => 'Approved Date',
        'deposit_by' => 'Deposit By',
        'status' => 'Status'
      ],
      'reseller-wallet-list' => [
        "id" => "#",
        "username" => "Name",
        'masking_balance' => "Masking Balance",
        'non_masking_balance' => "Non Masking Balance",
        'action' => 'Manage'
      ],
      'reseller-transfer-list' => [
        'id' => "#",
        'reseller_name' => 'Reseller Name',
        'deposit_amount' => 'Deposit Amount',
        'dr_cr' => 'DR/CR',
        'created_at' => 'Deposit Date',
        'approved_date' => 'Approved Date',
        'deposit_by' => 'Deposit By',
        'expire_date' => 'Expire Date',
        'status' => 'Status'
      ],
    ];

    return $headers[$tableName] ?? [];
  }
}

<?php

namespace Modules\Smsconfig\App\Trait;

trait DataTableTrait
{
  public function getTableHeader($tableName = ''): array
  {
    $headers = [
     'black-listed-keyword' => [
        "id" => "#",
        'title' => "Title",
        'keywords' => "Keywords",
        "user" => "User",
        "userType" => "User Type",
        "action" => "Manage"
      ],
      'country' => [
        "id" => "#",
        "name" => "Country Name",
        'nickname' => "Nickname",
        'phonecode' => 'Phone Code',
        "action" => "Manage"
      ],
      'operator' => [
        "id" => "#",
        'full_name' => "Operator Name",
        "short_name" => "Short Name",
        "prefix" => "Prefix",

        "ton" => "TON",
        "npi" => "NPI",
        "action" => "Manage"
      ],
      'rate' => [
        'id' => "#",
        'rate_name' => 'Rate Name',
        'masking_rate' => 'Masking Rate',
        'nonmasking_rate' => 'Nonmasking Rate',
        "action" => "Manage"
      ],
      'route' => [
        "id" => "#",
        "operator_prefix" => "Operator Prefix",
        "provider_name" => "Service Provider",
        "mask" => "Mask Option",
        "default_mask" => "Default Mask",
        "status" => "Status",
        "action" => "Manage"
      ],
      'sender-id' => [
        "id" => "#",
        "senderID" => "Sender ID",
        'count' => "Count",
        "status" => "Status",
        "action" => "Manage"
      ],
      'mask' => [
        "id" => "#",
        "mask" => "Mask",
        "username" => "User",
        "status" => "Status",
        "action" => "Manage"
      ],
      'service-provider' => [
        "id" => "#",
        'name' => "SMS Provider Name",
        "api_provider" => "API Provider",
        "channel_type" => "Type",
        "method" => "Method",
        "tps" => "TPS",
        "status" => "Status",
        "action" => "Manage"
      ]
    ];

    return $headers[$tableName] ?? [];
  }
}

<?php

namespace Modules\Smsconfig\App\Repositories;


use Illuminate\Database\Eloquent\Collection;

interface SenderIdRepositoryInterface extends BaseRepositoryInterface
{
  // Additional methods specific to SenderIdRepository can be declared here
  public function getSenderIds(): Collection;
}

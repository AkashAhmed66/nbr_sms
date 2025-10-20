<?php

namespace Modules\Transactions\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Transactions\App\Models\UserWallet;

interface UserWalletRepositoryInterface
{
  public function getUserWallet(array $filters = []): Collection;
  public function getResellerWallet(array $filters = []): Collection;
  public function all(array $filters = []): Collection;

  public function create(array $data): UserWallet;

  public function update(array $data, int $id): UserWallet;

  public function find(int $id): UserWallet;

  public function delete(int $id): bool;
}

<?php

namespace Modules\Transactions\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Transactions\App\Models\ResellerWallet;

interface ResellerWalletRepositoryInterface
{
  public function getUserWallet(array $filters = []): Collection;
  public function getResellerWallet(array $filters = []): Collection;
  public function all(array $filters = []): Collection;

  public function create(array $data): ResellerWallet;

  public function update(array $data, int $id): ResellerWallet;

  public function find(int $id): ResellerWallet;

  public function delete(int $id): bool;
}

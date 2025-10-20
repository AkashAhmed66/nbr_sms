<?php

namespace Modules\Transactions\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Transactions\App\Models\Transaction;

interface TransactionRepositoryInterface
{
  public function getUserTransferList(array $filters = []): Collection;
  public function getResellerTransferList(array $filters = []): Collection;
  public function all(array $filters = []): Collection;

  public function create(array $data): Transaction;

  public function update(array $data, int $id): Transaction;

  public function find(int $id): Transaction;

  public function delete(int $id): bool;
}

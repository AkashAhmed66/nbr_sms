<?php

namespace Modules\Phonebook\App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{
  public function all(array $filters = []): Collection;

  public function create(array $data): object;

  public function find(int $id): object;

  public function update(array $data, int $id): object;

  public function delete(int $id): bool;
}

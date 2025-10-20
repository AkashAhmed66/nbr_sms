<?php

namespace Modules\Developers\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Developers\App\Models\Developer;

interface DeveloperRepositoryInterface
{
  public function all(array $filters = []): Collection;

  public function create(array $data): Developer;

  public function find(int $id): Developer;

  public function update(array $data, int $id): Developer;

  public function delete(int $id): bool;
}

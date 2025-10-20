<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Menus\App\Models\Menu;

interface MenuRepositoryInterface
{
  public function all(array $filters = []): Collection;

  public function create(array $data): Menu;

  public function find(int $id): Menu;

  public function update(array $data, int $id): Menu;

  public function delete(int $id): bool;

}

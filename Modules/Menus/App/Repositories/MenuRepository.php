<?php

namespace Modules\Menus\App\Repositories;

use App\Repositories\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Modules\Menus\App\Models\Menu;

class MenuRepository implements MenuRepositoryInterface
{
  protected $model;

  public function __construct(Menu $model)
  {
    $this->model = $model;
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();

    if (isset($filters['title'])) {
      $query->where('title', 'like', '%' . $filters['title'] . '%');
    }

    if (isset($filters['content'])) {
      $query->where('content', 'like', '%' . $filters['content'] . '%');
    }

    return $query->get();
  }

  public function create(array $data): Menu
  {
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Menu
  {
    $menu = $this->model->find($id);
    $menu->update($data);

    return $menu;
  }

  public function find(int $id): Menu
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }

}

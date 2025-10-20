<?php
namespace Modules\Developers\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Developers\App\Models\Developer;

class DeveloperRepository implements DeveloperRepositoryInterface
{
  protected $model;

  public function __construct(Developer $model)
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

  public function create(array $data): Developer
  {
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Developer
  {
    $developer = $this->model->find($id);
    $developer->update($data);

    return $developer;
  }

  public function find(int $id): Developer
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

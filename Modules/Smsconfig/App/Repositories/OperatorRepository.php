<?php
namespace Modules\Smsconfig\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Smsconfig\App\Models\Operator;

class OperatorRepository implements OperatorRepositoryInterface
{
  protected $model;

  public function __construct(Operator $model)
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

  public function create(array $data): Operator
  {
    //$data['created_by'] = auth()->id();
	//$data['updated_by'] = auth()->id();
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Operator
  {
    
    $operator = $this->model->find($id);
    //$data['updated_by'] = auth()->id();
    $operator->update($data);

    return $operator;
  }

  public function find(int $id): Operator
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }

  public function changeStatus(array $data, int $id): Operator
  {
    $operator = $this->model->find($id);
    $data['updated_by'] = auth()->id();
    $operator->update($data);

    return $operator;
  }
}

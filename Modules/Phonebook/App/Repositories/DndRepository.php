<?php
namespace Modules\Phonebook\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Phonebook\App\Models\Dnd;

class DndRepository implements DndRepositoryInterface
{
  protected $model;
  private $user_id = '';
  private $reseller_id = '';
  private $user_group_id = '';

  public function __construct(Dnd $model)
  {
    $this->model = $model;
    $this->user_id = auth()->user()->id;
    $this->user_group_id = auth()->user()->id_user_group;
    $this->reseller_id = auth()->user()->reseller_id;
    $this->model = $model;
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();

    if (isset($filters['name'])) {
      $query->where('name', 'like', '%' . $filters['name'] . '%');
    }

    if (isset($filters['phone'])) {
      $query->where('phone', 'like', '%' . $filters['phone'] . '%');
    }

    if($this->user_group_id == 4){
      $query->where('user_id', $this->user_id);
    }


    return $query->get();
  }

  public function create(array $data): Dnd
  {
    $data['user_id'] = auth()->id();
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Dnd
  {
    $phonebook = $this->model->find($id);
    $phonebook->update($data);

    return $phonebook;
  }

  public function find(int $id): Dnd
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

<?php
namespace Modules\Phonebook\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Phonebook\App\Models\Phonebook;

class PhoneBookRepository implements PhoneBookRepositoryInterface
{
  protected $model;
  private $user_id = '';
  private $reseller_id = '';
  private $user_group_id = '';

  public function __construct(Phonebook $model)
  {
    $this->model = $model;
    $this->user_id = auth()->user()->id;
    $this->user_group_id = auth()->user()->id_user_group;
    $this->reseller_id = auth()->user()->reseller_id;
    $this->model = $model;
  }


  public function query(array $filters = []): \Illuminate\Database\Eloquent\Builder
  {
    $query = $this->model->query();

    if (!empty($filters['name'])) {
      $query->where('name_en', 'like', '%' . $filters['name'] . '%');
    }

    if (!empty($filters['phone'])) {
      $query->where('phone', 'like', '%' . $filters['phone'] . '%');
    }

    if ($this->user_group_id == 4) {
      $query->where('user_id', $this->user_id);
    }

    return $query->orderBy('created_at', 'desc'); // ✅ Builder
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();

    if (!empty($filters['name'])) {
      $query->where('name_en', 'like', '%' . $filters['name'] . '%');
    }

    if (!empty($filters['phone'])) {
      $query->where('phone', 'like', '%' . $filters['phone'] . '%');
    }

    if ($this->user_group_id == 4) {
      $query->where('user_id', $this->user_id);
    }

    return $query->orderBy('created_at', 'desc');
  }

  public function create(array $data): Phonebook
  {
    $data['user_id'] = auth()->id();
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Phonebook
  {
    $phonebook = $this->model->find($id);
    $phonebook->update($data);

    return $phonebook;
  }

  public function find(int $id): Phonebook
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

<?php
namespace Modules\Phonebook\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Phonebook\App\Models\Group;
use Illuminate\Support\Facades\DB;

class GroupRepository implements GroupRepositoryInterface
{
  protected $model;
  protected $user_id;
  protected $user_group_id;

  public function __construct(Group $model)
  {
    $this->model = $model;
    $this->user_id = auth()->id();
    $this->user_group_id = auth()->user()->id_user_group;
  }

  public function getGroups(): Collection
  {
    if($this->user_group_id == 1 || $this->user_group_id == 2) {
      return $this->model->query()->orderBy('id', 'desc')->get();
    }
    return $this->model->query()->where('user_id', $this->user_id)->orderBy('id', 'desc')->get();
  }

  public function all(array $filters = []): Collection
  {
      $query = $this->model->query();
      
      $query->leftJoin('contacts', 'group.id', '=', 'contacts.group_id');
      
      $query->select(
          'group.id',
          'group.user_id',
          'group.name',
          'group.type',
          'group.status',
          'group.reseller_id',
          'group_id',
          DB::raw('COUNT(phone) as phone_count')
      );

      if ($this->user_group_id != 1) {
          $query->where('group.user_id', $this->user_id);
      }

      $query->groupBy(
          'group.id',
          'group.user_id',
          'group.name',
          'group.type',
          'group.status',
          'group.reseller_id',
          'group_id'
      );

      return $query->get();
  }


  public function allGroups(array $filters = []): Collection
  {
    $query = $this->model->query();
    if($this->user_group_id != 1){
      $query->where('user_id', $this->user_id);
    }
    $query->orderBy('name', 'asc');

    return $query->get();
  }

  public function create(array $data): Group
  {
    $data['user_id'] = auth()->id();
    $data['reseller_id'] = 3;
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Group
  {
    $phonebook = $this->model->find($id);
    $phonebook->update($data);

    return $phonebook;
  }

  public function find(int $id): Group
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

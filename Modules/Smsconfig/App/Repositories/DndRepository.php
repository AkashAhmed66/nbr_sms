<?php

namespace Modules\Smsconfig\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Phonebook\App\Repositories\DndRepositoryInterface;
use Modules\Smsconfig\App\Models\Mask;
use Modules\Users\App\Models\User;

class DndRepository implements DndRepositoryInterface
{
  protected $model;
  private $user_id = '';
  private $user_group_id = '';

  public function __construct(Mask $model)
  {
    $this->model = $model;
    $this->user_id = auth()->user()->id;
    $this->user_group_id = auth()->user()->id_user_group;
  }

  public function getDndList(): Collection
  {
    $query = $this->model->query();
    if ($this->user_group_id == 2 || $this->user_group_id == 3 || $this->user_group_id == 4) {
      $query->where('user_id', $this->user_id);
    }
    $query->where('status', 'active');
    $query->orderBy('id', 'desc');

    return $query->get();
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();

    if ($this->user_group_id == 2 || $this->user_group_id == 3 || $this->user_group_id == 4) {
      $query->with(['user'])->where('user_id', $this->user_id);
    }

    if (isset($filters['search_ifo'])) {
      $query->where('mask', 'like', '%' . $filters['search_ifo'] . '%');
    }

    $query->where('status', 'active');
    $query->orderBy('id', 'desc');

    return $query->get();
  }

  public function create(array $data): Mask
  {
    $data['created_by'] = $this->user_id;
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Mask
  {
    $report = $this->model->find($id);
    $report->update($data);

    return $report;
  }

  public function find(int $id): Mask
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }

  public function getAvailableMask(): Collection
  {
    if ($this->user_group_id == 1) {
      return $this->model->where('user_id', null)->get();
    } elseif ($this->user_group_id == 2) {
      /*      $userIds = User::where('created_by', $this->user_id)->pluck('id')->toArray();
            return $this->model->where(function ($query) use ($userIds) {
              $query->where('user_id', null)
                ->orWhereIn('user_id', $userIds);
            })->get();*/
      $userIds = User::where('created_by', $this->user_id)->pluck('id')->toArray();
      $userIds[] = $this->user_id;
      return $this->model->where(function ($query) use ($userIds) {
        $query->WhereIn('user_id', $userIds);
      })->get();
    } elseif ($this->user_group_id == 3) {
      $userIds = User::where('created_by', $this->user_id)->pluck('id')->toArray();
      $userIds[] = $this->user_id;
      return $this->model->where(function ($query) use ($userIds) {
        $query->WhereIn('user_id', $userIds);
      })->get();
    } elseif ($this->user_group_id == 4) {
      $userIds = [$this->user_id];
      return $this->model->where(function ($query) use ($userIds) {
        $query->WhereIn('user_id', $userIds);
      })->get();
    } else {
      return $this->model->where('user_id', null)->get();
    }
  }
}

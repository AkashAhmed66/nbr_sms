<?php

namespace Modules\Smsconfig\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Redis;
use Modules\Smsconfig\App\Models\SenderId;
use Modules\Users\App\Models\User;

class SenderIdRepository implements SenderIdRepositoryInterface
{
  protected $model;
  private $user_id = '';
  private $user_group_id = '';

  public function __construct(SenderId $model)
  {
    $this->model = $model;
    $this->user_id = auth()->user()->id;
    $this->user_group_id = auth()->user()->id_user_group;
  }

  public function getSenderIds(): Collection
  {
    $query = $this->model->query();
    $query->orderBy('id', 'desc');

    return $query->get();
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();

    if ($this->user_group_id == 1 || $this->user_group_id == 2) {
      $query->with(['reseller', 'user']);
    }

    // if ($this->user_group_id == 2 || $this->user_group_id == 3 || $this->user_group_id == 4) {
    //   $userIds = User::where('created_by', $this->user_id)->pluck('id')->toArray();
    //   $userIds[] = $this->user_id;
    //   return $query->with(['user'])->where(function ($q) use ($userIds) {
    //     $q->WhereIn('user_id', $userIds);
    //   })->get();
    //   //$query->with(['user'])->whereIn('user_id', [$this->user_id]);
    // }

    if (isset($filters['search_ifo'])) {
      $query->where('senderID', 'like', '%' . $filters['search_ifo'] . '%');
    }

    if (isset($filters['reseller'])) {
      $query->whereHas('user', function ($qry) use ($filters) {
        $qry->where('name', 'like', $filters['reseller'] . '%');
      });
    }

    $query->orderBy('id', 'desc');

    return $query->get();
  }

  public function create(array $data): object
  {
    $senderId = [];
    for ($i = 0; $i < $data['count']; $i++) {
      $senderId[] = [
        'senderID' => $data['senderID'] + $i,
        'user_id' =>  $data['user_id'],
        'status' => 'Active',
        'count' => 1,
        'assigned_user_id' => null,
      ];
    }

    $this->model->insert($senderId);

    $user = User::find($data['user_id']);

    if (env('APP_TYPE') != 'Aggregator') {
      //Redis::set("user:{$user->username}", 'cli', $senderId[0]['senderID'] ?? '');

      $user = User::find($data['user_id']);

      $key = "user:{$user->username}";
      $json = Redis::get($key);
      $userData = $json ? json_decode($json, true) : [];
      $userData['cli'] = $senderId[0]['senderID'] ?? '';
      Redis::set($key, json_encode($userData));
    }

    return $this->model;
  }

  public function update(array $data, int $id): SenderId
  {
    $report = $this->model->find($id);
    $report->update($data);

    return $report;
  }

  public function find(int $id): SenderId
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }

  public function getSenderIdByGroupId(int $id): Collection
  {
    return $this->model->where('assigned_user_id', null)->where('user_id', $id)->get();
  }

  public function getAvailableSenderId(): Collection
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
    }elseif ($this->user_group_id == 3) {
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
    }else{
      return $this->model->where('user_id', null)->get();
    }
  }

  public function getSenderIdByUserId(int $id): Collection
  {
    return $this->model->where('user_id', $id)->get();
  }
}

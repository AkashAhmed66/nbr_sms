<?php

namespace Modules\Transactions\App\Repositories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Modules\Transactions\App\Models\UserWallet;
use Modules\Users\App\Models\User;
use Illuminate\Support\Facades\Redis;

class UserWalletRepository implements UserWalletRepositoryInterface
{
  protected $model;
  private $user_id = '';
  private $reseller_id = '';
  private $user_group_id = '';


  public function __construct(UserWallet $model)
  {
    $this->model = $model;
    $this->user_id = auth()->user()->id;
    $this->user_group_id = auth()->user()->id_user_group;
    $this->reseller_id = auth()->user()->reseller_id;
    $this->user = auth()->user();
  }

  public function getUserWallet(array $filters = []): Collection
  {
    $query = UserWallet::with('user')
      ->whereHas('user');
    if (auth()->user()->id_user_group != 1) {
      $userIds = User::where('created_by', $this->user_id)->pluck('id')->toArray();
      $userIds[] = $this->user_id;
      return $query->with(['user'])->where(function ($q) use ($userIds) {
        $q->WhereIn('user_id', $userIds);
      })->get();
    }

    return $query->orderBy('id', 'DESC')->get();

  }
  public function getOnlineTransaction(array $filters = []): Collection
  {
    $query = Payment::with('user');
    $query->where('status', 'paid');
    // dd(auth()->user()->id_user_group);
    if (auth()->user()->id_user_group != 1) {
      $userIds = User::where('created_by', $this->user_id)->pluck('id')->toArray();
      $userIds[] = $this->user_id;
      return $query->with(['user'])->where(function ($q) use ($userIds) {
        $q->WhereIn('customer_id', $userIds);
      })->orderBy('id', 'DESC')->get();
    }
    return $query->orderBy('id', 'DESC')->get();
  }

  public function getResellerWallet(array $filters = []): Collection
  {
    $filters['from_date'] = $filters['from_date'] ?? date('Y-m-d', strtotime("-300 days"));
    $filters['to_date'] = $filters['to_date'] ?? date('Y-m-d');

    if ($filters['from_date'] && $filters['to_date']) {
      $query = $this->model->query();
      $query->whereDate('expire_date', '>=', $filters['from_date']);
      $query->whereDate('expire_date', '<=', $filters['to_date']);
      $query->with('user');
      if ($this->user_group_id == 1 || $this->user_group_id == 2) {
        $query->with(['user'])->whereHas("user", function ($qry) use ($filters) {
          $qry->where('id_user_group', '3');
          $qry->where('status', 'Active');
        });
      }

      if (isset($filters['search_info'])) {
        $query->with(['user'])->whereHas("user", function ($qry) use ($filters) {
          $qry->where('name', 'like', '%' . $filters['search_info'] . '%');
        });
      }

      $query->orderBy('expire_date', 'DESC');
      return $query->get();
    } else {
      return Collection::make([]);
    }
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

  public function create(array $data): UserWallet
  {
    $user = User::find($data['user_id']);
    if ($user) {
      $user->available_balance += $data['balance'];
      $user->save();

      if($this->user_group_id == 2){
        $this->user->available_balance -= $data['balance'];
        $this->user->save();
      }

      if (env('APP_TYPE') != 'Aggregator') {
        $key = "user:{$user->username}";
        $json = Redis::get($key);
        $userData = $json ? json_decode($json, true) : [];
        $userData['available_balance'] = $user->available_balance;
        Redis::set($key, json_encode($userData));
      }

    }

    //$data['user_id'] = auth()->id();
    return $this->model->create($data);
  }

  public function update(array $data, int $id): UserWallet
  {

    //$prev_balance = UserProfile::where('user_id', $userId)->first();
    $prevBalance = $this->model->where('id', $id)->first();
    $user = User::find(auth()->id());
    if ($user) {
      $user->available_balance -= $prevBalance->balance;
      $user->save();

    }

    $report = $this->model->find($id);
    $report->update($data);

    $user->available_balance += $data['balance'];
    $user->save();

    return $report;
  }

  public function find(int $id): UserWallet
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

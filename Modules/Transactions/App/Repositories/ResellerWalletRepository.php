<?php

namespace Modules\Transactions\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Transactions\App\Models\ResellerWallet;

class ResellerWalletRepository implements ResellerWalletRepositoryInterface
{
  protected $model;
  private $user_id = '';
  private $reseller_id = '';
  private $user_group_id = '';


  public function __construct(ResellerWallet $model)
  {
    $this->model = $model;
    $this->user_id = auth()->user()->id;
    $this->user_group_id = auth()->user()->id_user_group;
    $this->reseller_id = auth()->user()->reseller_id;
  }

  public function getUserWallet(array $filters = []): Collection
  {

    $query = $this->model->query();
    return $query->get();
    /*$filters['from_date'] = $filters['from_date'] ?? date('Y-m-d', strtotime("-300 days"));
    $filters['to_date'] = $filters['to_date'] ?? date('Y-m-d');

    if ($filters['from_date'] && $filters['to_date']) {
      $query = $this->model->query();
      $query->whereDate('expire_date', '>=', $filters['from_date']);
      $query->whereDate('expire_date', '<=', $filters['to_date']);
      $query->with('user');
      if ($this->user_group_id == 1 || $this->user_group_id == 2) {
        $query->with(['user'])->whereHas("user", function ($qry) use ($filters) {
          $qry->where('status', 'Active');
        });
      }

      if ($this->user_group_id == 3) {
        $query->with(['user'])->whereHas("user", function ($qry) use ($filters) {
          $qry->where("reseller_id", $this->reseller_id)->where('status', 'Active');
        });
      }

      if ($this->user_group_id == 4) {
        $query->with(['user'])->whereHas("user", function ($qry) use ($filters) {
          $qry->where("user_id", $this->user_id)->where('status', 'Active');
        });
      }

      if (isset($filters['search_info'])) {
        $query->with(['user'])->whereHas("user", function ($qry) use ($filters) {
          $qry->where('name', 'like', '%' . $filters['search_info'] . '%');
          $qry->orWhere('name', 'like', '%' . $filters['search_info'] . '%');
        });
      }

      $query->orderBy('expire_date', 'DESC');
      return $query->get();
    } else {
      return Collection::make([]);
    }*/
  }

  public function getResellerWallet(array $filters = []): Collection
  {
      $query = $this->model->query();
      return $query->get();

    /*$filters['from_date'] = $filters['from_date'] ?? date('Y-m-d', strtotime("-300 days"));
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
    }*/
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

  public function create(array $data): ResellerWallet
  {
    $data['user_id'] = auth()->id();
    return $this->model->create($data);
  }

  public function update(array $data, int $id): ResellerWallet
  {
    $report = $this->model->find($id);
    $report->update($data);

    return $report;
  }

  public function find(int $id): ResellerWallet
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

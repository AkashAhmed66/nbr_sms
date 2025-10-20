<?php

namespace Modules\Transactions\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Transactions\App\Models\DepositeHistory;
use Modules\Transactions\App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
  protected $model;
  private $user_id = '';
  private $reseller_id = '';
  private $user_group_id = '';


  public function __construct(DepositeHistory $model)
  {
    $this->model = $model;
    $this->user_id = auth()->user()->id;
    $this->user_group_id = auth()->user()->id_user_group;
    $this->reseller_id = auth()->user()->reseller_id;
  }

  public function getUserTransferList(array $filters = []): Collection
  {
    $filters['from_date'] = $filters['from_date'] ?? date('Y-m-d', strtotime("-300 days"));
    $filters['to_date'] = $filters['to_date'] ?? date('Y-m-d');

    $query = $this->model->query();
    $query->whereDate('expire_date', '>=', $filters['from_date']);
    $query->whereDate('expire_date', '<=', $filters['to_date']);
    if ($this->user_group_id == 4) {
      $query->with('user');
      $query->where('user_id', $this->user_id);
    } else {
      $query->with(['user'])->whereHas("user", function ($qry) use ($filters) {
        $qry->where("reseller_id", $this->reseller_id);
      });
    }

    if (!empty($filters['search_info'])) {
      $query->with(['user'])->whereHas("user", function ($qry) use ($filters) {
        $qry->where('name', 'like', '%' . $filters['search'] . '%');
        $qry->orWhere('username', 'like', '%' . $filters['search'] . '%');
      });
    }

    if(!empty($filters['deposit_date'])){
      $query->whereDate('created_at', $filters['deposit_date']);
    }

    if (!empty($filters['approved_date'])) {
      $query->whereDate('approved_date', $filters['approved_date']);
    }

    if(!empty($filters['expire_date'])){
      $query->whereDate('expire_date', $filters['expire_date']);
    }

    $query->orderBy('created_at', 'DESC');
    return $query->get();
  }

  public function getResellerTransferList(array $filters = []): Collection
  {
    $filters['from_date'] = $filters['from_date'] ?? date('Y-m-d', strtotime("-300 days"));
    $filters['to_date'] = $filters['to_date'] ?? date('Y-m-d');

    $query = $this->model->query();
    $query->whereDate('expire_date', '>=', $filters['from_date']);
    $query->whereDate('expire_date', '<=', $filters['to_date']);
    if ($this->user_group_id == 4) {
      $query->with('user');
      $query->where('reseller_id', $this->user_id);
    } else {
      $query->with(['user'])->whereHas("user", function ($qry) use ($filters) {
        $qry->where("reseller_id", $this->reseller_id);
      });
    }

    if (!empty($filters['search_info'])) {
      $query->with(['user'])->whereHas("user", function ($qry) use ($filters) {
        $qry->where('name', 'like', '%' . $filters['search'] . '%');
        $qry->orWhere('username', 'like', '%' . $filters['search'] . '%');
      });
    }

    if(!empty($filters['deposit_date'])){
      $query->whereDate('created_at', $filters['deposit_date']);
    }

    if (!empty($filters['approved_date'])) {
      $query->whereDate('approved_date', $filters['approved_date']);
    }

    if(!empty($filters['expire_date'])){
      $query->whereDate('expire_date', $filters['expire_date']);
    }

    $query->orderBy('created_at', 'DESC');
    return $query->get();
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

  public function create(array $data): Transaction
  {
    $data['user_id'] = auth()->id();
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Transaction
  {
    $report = $this->model->find($id);
    $report->update($data);

    return $report;
  }

  public function find(int $id): Transaction
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

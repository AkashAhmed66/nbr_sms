<?php

namespace Modules\Smsconfig\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Smsconfig\App\Models\Route;

class RouteRepository implements RouteRepositoryInterface
{
  protected $model;
  private $reseller_id;
  private $user_group;
  private $user_id;

  public function __construct(Route $model)
  {
    $this->model = $model;
    $this->reseller_id = Auth::user()->reseller_id ?? null;
    $this->user_group = Auth::user()->id_user_group ?? null;
    $this->user_id = Auth::user()->id ?? null;
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->with(['user', 'channel']);

    if (isset($filters['search_info'])) {
      $query->whereHas('channel', function ($qry) use ($filters) {
        $qry->where('name', 'like', '%' . $filters['search_info'] . '%');
      });
    }

    $query->orderBy('id', 'desc');

    return $query->get();
  }

  public function create(array $data): Route
  {
    $data['user_id'] = auth()->id();
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Route
  {
    $report = $this->model->find($id);
    $report->update($data);

    return $report;
  }

  public function find(int $id): Route
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

<?php
namespace Modules\Smsconfig\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Smsconfig\App\Models\ServiceProvider;

class ServiceProviderRepository implements ServiceProviderRepositoryInterface
{
  protected $model;

  public function __construct(ServiceProvider $model)
  {
    $this->model = $model;
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();

    if (isset($filters['search_info'])) {
      $query->where('name', 'like', '%' . $filters['search_info'] . '%');
    }

    $query->orderBy('id', 'desc');

    return $query->get();
  }

  public function create(array $data): ServiceProvider
  {
    $data['created_by'] = auth()->user()->id;
    return $this->model->create($data);
  }

  public function update(array $data, int $id): ServiceProvider
  {
    $report = $this->model->find($id);
    $data['updated_by'] = auth()->user()->id;
    $report->update($data);

    return $report;
  }

  public function find(int $id): ServiceProvider
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

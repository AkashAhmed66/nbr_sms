<?php
namespace Modules\Smsconfig\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Smsconfig\App\Models\Country;

class CountryRepository implements CountryRepositoryInterface
{
  protected $model;

  public function __construct(Country $model)
  {
    $this->model = $model;
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();
    return $query->get();
  }

  public function create(array $data): Country
  {
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Country
  {
    $report = $this->model->find($id);
    $report->update($data);

    return $report;
  }

  public function find(int $id): Country
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

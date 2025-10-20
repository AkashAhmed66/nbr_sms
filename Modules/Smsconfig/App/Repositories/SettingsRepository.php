<?php
namespace Modules\Smsconfig\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Smsconfig\App\Models\Setting;

class SettingsRepository implements SettingsRepositoryInterface
{
  protected $model;

  public function __construct(Setting $model)
  {
    $this->model = $model;
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

  public function create(array $data): Setting
  {
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Setting
  {
    $report = $this->model->find($id);
    $report->update($data);

    return $report;
  }

  public function find(int $id): Setting
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

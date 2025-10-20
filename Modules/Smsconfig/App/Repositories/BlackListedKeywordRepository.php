<?php
namespace Modules\Smsconfig\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Smsconfig\App\Models\BlacklistedKeyword;

class BlackListedKeywordRepository implements BlackListedKeywordRepositoryInterface
{
  protected $model;

  public function __construct(BlacklistedKeyword $model)
  {
    $this->model = $model;
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();
    $query->with('user', 'user.userType');

    if (isset($filters['title'])) {
      $query->where('title', 'like', '%' . $filters['title'] . '%');
    }

    return $query->get();
  }

  public function create(array $data): BlacklistedKeyword
  {
    $data['user_id'] = auth()->id();
    return $this->model->create($data);
  }

  public function update(array $data, int $id): BlacklistedKeyword
  {
    $report = $this->model->find($id);
    $report->update($data);

    return $report;
  }

  public function find(int $id): BlacklistedKeyword
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

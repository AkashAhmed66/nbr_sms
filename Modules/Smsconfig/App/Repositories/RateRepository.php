<?php
namespace Modules\Smsconfig\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Smsconfig\App\Models\Rate;

class RateRepository implements RateRepositoryInterface
{
  protected $model;
  private $reseller_id;
  private $user_group;
  private $user_id;

  public function __construct(Rate $model)
  {
    $this->model = $model;
    $this->reseller_id = Auth::user()->reseller_id ?? null;
    $this->user_group = Auth::user()->id_user_group ?? null;
    $this->user_id = Auth::user()->id ?? null;
    $this->sms_rate_id = Auth::user()->sms_rate_id ?? null;
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();

    if ($this->user_group != 1) {
      $query->where(function ($q) {
        $q->where('id', $this->sms_rate_id)
          ->orWhere('created_by', auth()->id());
      });
    }

    if (isset($filters['title'])) {
      $query->where('title', 'like', '%' . $filters['title'] . '%');
    }

    return $query->get();
  }

  public function create(array $data): Rate
  {
    $data['rate_type'] = "sms";
    $data['created_by'] = $this->user_id;
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Rate
  {
    $report = $this->model->find($id);
    $report->update($data);

    return $report;
  }

  public function find(int $id): Rate
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

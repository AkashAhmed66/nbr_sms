<?php

namespace Modules\Messages\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Messages\App\Models\Template;

class TemplateRepository implements TemplateRepositoryInterface
{
  protected $model;
  private $user_id;
  private $user_group_id;

  public function __construct(Template $model)
  {
    $this->model = $model;
    $this->user_id = auth()->id();
    $this->user_group_id = auth()->user()->id_user_group;
  }

  public function getTemplates(): Collection
  {
    if($this->user_group_id == 1 || $this->user_group_id == 2) {
      return $this->model->query()->orderBy('id', 'desc')->get();
    }
    return $this->model->query()->where('user_id', $this->user_id)->orderBy('id', 'desc')->get();
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();

    if ($this->user_group_id == 2 || $this->user_group_id == 3 || $this->user_group_id == 4) {
      $query->where('user_id', $this->user_id);
    }

    if (isset($filters['search_info'])) {
      $query->where('title', 'like', '%' . $filters['search_info'] . '%');
      $query->orWhere('description', 'like', '%' . $filters['search_info'] . '%');
    }

    $query->orderBy('id', 'desc');

    return $query->get();
  }

  public function create(array $data): Template
  {
    $data['user_id'] = $this->user_id;
    try {
      return $this->model->create($data);
    }catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function update(array $data, int $id): Template
  {
    $message = $this->model->find($id);
    $message->update($data);

    return $message;
  }

  public function find(int $id): Template
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }
}

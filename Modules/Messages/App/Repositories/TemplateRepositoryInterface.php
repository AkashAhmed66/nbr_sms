<?php

namespace Modules\Messages\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Messages\App\Models\Template;

interface TemplateRepositoryInterface
{
  public function getTemplates(): Collection;
  public function all(array $filters = []): Collection;

  public function create(array $data): Template;

  public function find(int $id): Template;

  public function update(array $data, int $id): Template;

  public function delete(int $id): bool;
}

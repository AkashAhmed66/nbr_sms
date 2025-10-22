<?php

namespace Modules\Messages\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Messages\App\Models\Message;

interface MessageRepositoryInterface
{
  public function all(array $filters = []): Collection;

  public function create(array $data): Message;

  public function saveRegularMessage(array $data): bool;

  public function saveGroupMessage(array $data): bool;

  public function saveFileMessage(array $data): bool;

  public function find(int $id): Message;

  public function update(array $data, int $id): Message;

  public function delete(int $id): bool;

  public function sendMessageToSocket(array $data, int $orderId): void;
}

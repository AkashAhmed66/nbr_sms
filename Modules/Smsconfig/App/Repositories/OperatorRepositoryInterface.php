<?php

namespace Modules\Smsconfig\App\Repositories;

interface OperatorRepositoryInterface extends BaseRepositoryInterface
{
    public function changeStatus(array $data, int $id): object;
}

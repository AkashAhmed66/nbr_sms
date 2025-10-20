<?php

namespace Modules\Phonebook\App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface GroupRepositoryInterface extends BaseRepositoryInterface
{
    public function getGroups(): Collection;
}

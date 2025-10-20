<?php

namespace Modules\Campaign\App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface CampaignRepositoryInterface
{
  public function getScheduleCampaignList(array $filters = []): Collection;

  public function getRunningCampaignList(array $filters = []): Collection;

  public function getArchiveCampaignList(array $filters = []): Collection;
}

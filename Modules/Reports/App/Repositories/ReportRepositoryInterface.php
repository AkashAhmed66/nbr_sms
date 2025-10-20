<?php

namespace Modules\Reports\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Reports\App\Models\Report;
use Illuminate\Database\Eloquent\Builder;

interface ReportRepositoryInterface
{
  public function getLast2DaysFailedSmsList($request): Builder;

  public function getFailedArchivedSms($request): Builder;

  public function getLast2DaysSmsList($request): Builder;

  public function getArchivedSms($request): Builder;

  public function getSummeryLog(array $filters = []): Collection;

  public function getDayWiseLog(array $filters = []): Collection;

  public function getTotalSmsLog(array $filters = []): Collection;
}

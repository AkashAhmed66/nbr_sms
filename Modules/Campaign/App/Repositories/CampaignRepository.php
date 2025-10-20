<?php

namespace Modules\Campaign\App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Messages\App\Models\Message;

class CampaignRepository implements CampaignRepositoryInterface
{
  protected $model;
  private $user_id;
  private $user_group_id;

  public function __construct(Message $model)
  {
    $this->model = $model;
    $this->user_id = Auth::user()->id ?? null;
    $this->user_group_id = Auth::user()->id_user_group ?? null;
  }

  public function getScheduleCampaignList(array $filters = []): Collection
  {
    //if (isset($filters['from_date']) && isset($filters['to_date'])) {
    $query = $this->model->query();
    $query->with('smsGroup');
    $query->whereNotNull('scheduleDateTime');
    $query->whereDate('scheduleDateTime', '>=', Carbon::today());
    if ($this->user_group_id == 3 || $this->user_group_id == 4) {
      $query->where('user_id', $this->user_id);
    }
    $query->where('user_id', $this->user_id);
    //$query->whereDate('created_at', '>=', $filters['from_date']);
    //$query->whereDate('created_at', '<=', $filters['to_date']);

    /*if (isset($filters['search_info'])) {
      $query = $query->where(function ($query) use ($filters) {
        $query->where('senderID', 'like', '%' . $filters['search_info'] . '%')
          ->orWhere('recipient', 'like', '%' . $filters['search_info'] . '%')
          ->orWhere('campaign_name', 'like', '%' . $filters['search_info'] . '%');
      });
    }

    if (isset($filters['status'])) {
      $query->where('status', '=', $filters['status']);
    }

    if (isset($filters['sms_type'])) {
      $query->where('sms_type', '=', $filters['sms_type']);
    }*/

    //$query->whereDate('scheduleDateTime', '>=', $filters['from_date']);
    //$query->whereDate('scheduleDateTime', '<=', $filters['to_date']);
    $query->orderBy('created_at', 'desc');

    return $query->get();
    //} else {
    //return Collection::make([]);
    //}
  }

  public function getRunningCampaignList(array $filters = []): Collection
  {
    // dd($filters);
    $startDate = Carbon::now()->subDays(1)->startOfDay();
    $endDate = Carbon::now()->endOfDay();

    if (isset($filters['from_date'])) {
      $startDate = Carbon::parse($filters['from_date'])->startOfDay();
    }

    if (isset($filters['to_date'])) {
      $endDate = Carbon::parse($filters['to_date'])->endOfDay();
    }

    $query = $this->model->query();
    $query->with('smsGroup');

    $query->whereNotNull('campaign_id');

    if (isset($filters['campaign_id'])) {
      $query->where('campaign_id', '=', $filters['campaign_id']);
    }

    if (isset($filters['user_id'])) {
      $query->where('user_id', '=', $filters['user_id']);
    }

    $query->whereBetween('date', [$startDate, $endDate]);

    if (isset($filters['status'])) {
      $query->where('status', '=', $filters['status']);
    }

    if (isset($filters['sms_type'])) {
      $query->where('sms_type', '=', $filters['sms_type']);
    }


    if ($this->user_group_id == 3 || $this->user_group_id == 4) {
      $query->where('user_id', $this->user_id);
    }
    
    $query->orderBy('id', 'desc');

    return $query->get();
    //} else {
    //return Collection::make([]);
    //}
  }

  public function getArchiveCampaignList(array $filters = []): Collection
  {
    $startDate = Carbon::now()->subMonths(6)->startOfDay();
    $endDate = Carbon::now()->endOfDay();

    //if (isset($filters['from_date']) && isset($filters['to_date'])) {
    $query = $this->model->query();
    $query->with('smsGroup');
    $query->whereBetween('date', [$startDate, $endDate]);
    if ($this->user_group_id == 3 || $this->user_group_id == 4) {
      $query->where('user_id', $this->user_id);
    }
    //$query->where('archived', 1);
    //$query->whereDate('created_at', '>=', $filters['from_date']);
    //$query->whereDate('created_at', '<=', $filters['to_date']);

    /*if (isset($filters['search_info'])) {
      $query = $query->where(function ($query) use ($filters) {
        $query->where('senderID', 'like', '%' . $filters['search_info'] . '%')
          ->orWhere('recipient', 'like', '%' . $filters['search_info'] . '%')
          ->orWhere('campaign_name', 'like', '%' . $filters['search_info'] . '%');
      });
    }*/
    //if (isset($filters['status'])) {
    //$query->where('status', '=', $filters['status']);
    //}

    //if (isset($filters['sms_type'])) {
    //$query->where('sms_type', '=', $filters['sms_type']);
    //}

    $query->orderBy('id', 'desc');

    return $query->get();
    //} else {
    //return Collection::make([]);
    //}
  }
}

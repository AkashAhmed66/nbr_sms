<?php

namespace Modules\Dashboard\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Dashboard\App\Repositories\DashboardRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Modules\Users\App\Models\User;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;

class DashboardController extends Controller
{

  protected DashboardRepositoryInterface $dashboardRepository;

  public function __construct(DashboardRepositoryInterface $dashboardRepository)
  {
    $this->dashboardRepository = $dashboardRepository;
    $this->user_id = Auth::user()->id ?? null;
    $this->user_group_id = Auth::user()->id_user_group ?? null;
  }

  public function index()
  {
    $totalUsers = $this->dashboardRepository->countTotalUsers();

    $totalMessages = $this->dashboardRepository->countTotalMessages();

    $totalMessagesDay = $this->dashboardRepository->countTotalMessagesDay();

    $totalTransections = $this->dashboardRepository->countTotalTransections();

    $totalSenderId = $this->dashboardRepository->countTotalSenderId();

    $userInfo = Auth::user();

    $userSmsRate = $userInfo ? $userInfo->smsRate : null;

    // Ensure safe defaults
    $nonmaskingRate = $userSmsRate && $userSmsRate->nonmasking_rate > 0 ? $userSmsRate->nonmasking_rate : null;
    $maskingRate = $userSmsRate && $userSmsRate->masking_rate > 0 ? $userSmsRate->masking_rate : null;

    $remainingNonmaskingmessageCount = $nonmaskingRate
      ? round($userInfo->available_balance / $nonmaskingRate)
      : 0;

    $remainingMaskingMessageCount = $maskingRate
      ? round($userInfo->available_balance / $maskingRate)
      : 0;
      
    $api_key = $userInfo->APIKEY;


    //dd($totalFailedMessages);
    return view(
      'dashboard::superadmin',
      compact(
        'totalUsers',
        'totalMessages',
        'totalTransections',
        'totalSenderId',
        'userInfo',
        'remainingNonmaskingmessageCount',
        'remainingMaskingMessageCount',
        'api_key',
        'totalMessagesDay'
      )
    );
  }

  public function getStatusWiseMessage(Request $request)
  {
    $range = $request->input('range');
    $query = Outbox::query();
    if ($this->user_group_id != 1 || $this->user_group_id != 2) {
      $query->where('user_id', $this->user_id);
    }
    switch ($range) {
      case 'today':
        $query->whereDate('created_at', Carbon::today());
        break;
      case 'yesterday':
        $query->whereDate('created_at', Carbon::yesterday()->toDateString());
        break;
      case 'last_7_days':
        $query->where('created_at', '>=', Carbon::now()->subDays(7));
        break;
      case 'last_30_days':
        $query->whereDate('created_at', '>=', Carbon::now()->subDays(30)->toDateString());
        break;
      case 'current_month':
        $query->whereMonth('created_at', Carbon::now()->month)
          ->whereYear('created_at', Carbon::now()->year);
        break;
      case 'last_month':
        $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
          ->whereYear('created_at', Carbon::now()->subMonth()->year);
        break;
      default:
        break;
    }

    $baseQuery = clone $query;
    $totalMessages = $query->count();

    if ($totalMessages > 0) {
      $statusList = ['Failed', 'Delivered', 'Sent', 'Processing', 'Queue', 'Hold'];
      $messageCounts = collect();

      foreach ($statusList as $status) {

        $statusQuery = clone $baseQuery;
        $count = $statusQuery->where('status', $status)->count();

        $messageCounts->push((object) [
          'status' => $status,
          'count' => $count,
          'percentage' => round(($count / $totalMessages) * 100)
        ]);
      }
    } else {

      $messageCounts = collect();
      $statusList = ['Failed', 'Delivered', 'Sent', 'Processing', 'Queue', 'Hold'];
      foreach ($statusList as $status) {
        $messageCounts->push((object) [
          'status' => $status,
          'count' => 0,
          'percentage' => 0
        ]);
      }
    }

    $messageCountsWithPercentage = $messageCounts;

    return response()->json($messageCountsWithPercentage)->header('Content-Type', 'application/json; charset=utf-8');
  }

}

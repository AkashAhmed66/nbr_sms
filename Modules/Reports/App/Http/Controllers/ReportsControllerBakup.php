<?php

namespace Modules\Reports\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Messages\App\Models\Outbox;
use Modules\Reports\App\Repositories\ReportRepositoryInterface;
use Modules\Reports\App\Trait\DataTableTrait;
use Modules\Users\App\Repositories\UserRepositoryInterface;
use Yajra\DataTables\DataTables;

class ReportsControllerBakup extends Controller
{
  use DataTableTrait;

  protected ReportRepositoryInterface $reportRepository;
  protected UserRepositoryInterface $userRepository;

  public function __construct(ReportRepositoryInterface $reportRepository, UserRepositoryInterface $userRepository)
  {
    $this->reportRepository = $reportRepository;
    $this->userRepository = $userRepository;
  }

  //2DAYS FAILED MESSAGE LIST
  public function last2DaysFailedSmsList(Request $request)
  {
    $title = '2 Days Failed Message List';
    $datas = $this->reportRepository->getLast2DaysFailedSmsList($request->all());
    $ajaxUrl = route('last-2days-failed-sms-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->editColumn('userId', fn($row) => $row->user->username)
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 2))
        ->editColumn('write_time', fn($row) => date("D jS \\of M Y h:i:s A", strtotime($row->write_time)))
        ->editColumn(
          'sent_time',
          fn($row) => $row->sent_time ? date("D jS \\of M Y h:i:s A", strtotime($row->sent_time)) : ''
        )
        ->editColumn(
          'last_updated',
          fn($row) => $row->last_updated ? date("D jS \\of M Y h:i:s A", strtotime($row->last_updated)) : ''
        )
        ->editColumn(
          'rate',
          fn($row) => ($row->sms_cost > 0 and $row->smscount > 0) ? number_format(
            $row->sms_cost / $row->smscount,
            2
          ) : '0.00'
        )
        //->editColumn('api_web', fn($row) => $row->message->source)
        ->editColumn('api_web', function ($row) {
          return $row->message?->source ?? 'N/A'; // Safely access the source property
        })
        ->editColumn('campaign', function ($row) {
          return $row->message?->campaign_name ?? 'N/A'; // Safely access the source property
        })
        //->editColumn('campaign', fn($row) => $row->message->campaign_name)
        ->editColumn('message', fn($row) => Str::words($row->message, '12'))
        ->make();
    }

    $tableHeaders = $this->getTableHeader('last-2days-failed-sms-list');

    return view('reports::last-2days-failed-sms-list', compact('title', 'tableHeaders', 'ajaxUrl'));
  }

  //FAILED ARCHIVE MESSAGE
  public function failedArchivedSms(Request $request)
  {
    $title = 'Failed Archive Message List';
    $datas = $this->reportRepository->getFailedArchivedSms($request->all());
    $ajaxUrl = route('failed-archived-sms');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->editColumn('userId', fn($row) => $row->user->username)
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 2))
        ->editColumn('write_time', fn($row) => date("D jS \\of M Y h:i:s A", strtotime($row->write_time)))
        ->editColumn(
          'sent_time',
          fn($row) => $row->sent_time ? date("D jS \\of M Y h:i:s A", strtotime($row->sent_time)) : ''
        )
        ->editColumn(
          'last_updated',
          fn($row) => $row->last_updated ? date("D jS \\of M Y h:i:s A", strtotime($row->last_updated)) : ''
        )
        ->editColumn(
          'rate',
          fn($row) => ($row->sms_cost > 0 and $row->smscount > 0) ? number_format(
            $row->sms_cost / $row->smscount,
            2
          ) : '0.00'
        )
        ->editColumn('api_web', fn($row) => $row->message->source ?? '')
        ->editColumn('campaign', fn($row) => $row->message->campaign_name ?? '')
        ->editColumn('message', fn($row) => Str::words($row->message, '12'))
        ->make();
    }

    $tableHeaders = $this->getTableHeader('failed-archived-sms-list');

    return view('reports::failed-archived-sms', compact('title', 'tableHeaders', 'ajaxUrl'));
  }

  //2DAYS DETAILS REPORT LIST
  /*public function last2DaysSmsList(Request $request)
  {
    $title = '2 Days Details Report List';
    $filters = $request->all();
    $datas = $this->reportRepository->getLast2DaysSmsList($filters);

    $ajaxUrl = route('last-2days-sms-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 2))
        ->editColumn('write_time', fn($row) => date("D jS \\of M Y h:i:s A", strtotime($row->write_time)))
        ->editColumn(
          'last_updated',
          fn($row) => $row->last_updated ? date("D jS \\of M Y h:i:s A", strtotime($row->last_updated)) : ''
        )
        ->editColumn(
          'rate',
          fn($row) => ($row->sms_cost > 0 and $row->smscount > 0) ? number_format(
            $row->sms_cost / $row->smscount,
            2
          ) : '0.00'
        )
        //->editColumn('api_web', fn($row) => $row->source??'')
        //->editColumn('campaign', fn($row) => $row->campaign_name??'')
        ->editColumn('message', fn($row) => Str::words($row->message, '12'))
        ->make();
    }

    $tableHeaders = $this->getTableHeader('last-2days-sms-list');
    $userLists = $this->reportRepository->gerUsers();
    return view('reports::last-2days-sms-list', compact('title', 'tableHeaders', 'ajaxUrl', 'userLists'));
  }*/

  public function last2DaysSmsList(Request $request)
  {
    $title = '2 Days Details Report List';
    $ajaxUrl = route('last-2days-sms-list');

    $startDate = isset($request->from_date)
      ? Carbon::parse(urldecode($request->from_date))->format('Y-m-d')
      : Carbon::now()->subDays(2)->startOfDay();

    $endDate = isset($request->to_date)
      ? Carbon::parse(urldecode($request->to_date))->format('Y-m-d')
      : Carbon::now()->endOfDay();

    if ($request->ajax()) {
      $reports = Outbox::with(['user.smsRate', 'sendMessage']);

      if ($request->message) {
        $reports->where('message', 'like', "%{$request->message}%");
      }
      if ($request->mobile) {
        $reports->where('destmn', 'like', "%{$request->mobile}%");
      }
      if ($request->source) {
        $reports->whereHas('sendMessage', function ($q) use ($request) {
          $q->where('source', 'like', "%{$request->source}%");
        });
      }
      if ($startDate) {
        $reports->whereDate('write_time', '>=', $startDate);
      }
      if ($endDate) {
        $reports->whereDate('write_time', '<=', $endDate);
      }
      if ($request->user_id) {
        $reports->where('user_id', $request->user_id);
      }
      if (Auth::user()->id_user_group != 1) {
        $reports->where('user_id', Auth::user()->id);
      }

      $reports->orderBy('write_time', 'desc');

      return DataTables::of($reports)
        ->addIndexColumn()
        ->editColumn('name', fn($row) => $row->user?->name ?? '')
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 2))
        ->editColumn('write_time', fn($row) => $row->write_time ? date("D jS \\of M Y h:i:s A", strtotime($row->write_time)) : '')
        ->editColumn('last_updated', fn($row) => $row->last_updated ? date("D jS \\of M Y h:i:s A", strtotime($row->last_updated)) : '')
        ->editColumn('sent_time', fn($row) => $row->sent_time ? date("D jS \\of M Y h:i:s A", strtotime($row->sent_time)) : '')
        ->editColumn('rate', fn($row) => $row->user && $row->user->smsRate ? number_format($row->user->smsRate->nonmasking_rate, 2) : '0.00')
        ->editColumn('message', fn($row) => \Illuminate\Support\Str::words($row->message, 12))
        ->editColumn('error_code', fn($row) => $row->dlr_status_code ?? '')
        ->editColumn('error_message', fn($row) => $row->dlr_status ?? '')
        ->editColumn('status', function ($row) {
          return $row->status == 'REJECTD' ? 'Failed' : 'Delivered';
        })
        ->editColumn('source', fn($row) => $row->sendMessage?->source ?? '')
        ->editColumn('campaign_name', fn($row) => $row->sendMessage?->campaign_name ?? '')
        ->make(true);
    }

    $tableHeaders = $this->getTableHeader('last-2days-sms-list');
    $userLists = $this->reportRepository->gerUsers();
    return view('reports::last-2days-sms-list', compact('title', 'tableHeaders', 'ajaxUrl', 'userLists'));
  }

  public function archivedSms(Request $request)
  {
    $title = 'Archived SMS Report List';
    $ajaxUrl = route('archived-sms');

    $startDate = isset($request->from_date)
      ? Carbon::parse(urldecode($request->from_date))->format('Y-m-d')
      : Carbon::now()->subDays(90)->startOfDay();

    $endDate = isset($request->to_date)
      ? Carbon::parse(urldecode($request->to_date))->format('Y-m-d')
      : Carbon::now()->endOfDay();
    if ($request->ajax()) {
      $reports = Outbox::with(['user.smsRate', 'sendMessage']);

      if ($request->message) {
        $reports->where('message', 'like', "%{$request->message}%");
      }
      if ($request->mobile) {
        $reports->where('destmn', 'like', "%{$request->mobile}%");
      }
      if ($request->source) {
        $reports->whereHas('sendMessage', function ($q) use ($request) {
          $q->where('source', 'like', "%{$request->source}%");
        });
      }
      if ($startDate) {
        $reports->whereDate('write_time', '>=', $startDate);
      }
      if ($endDate) {
        $reports->whereDate('write_time', '<=', $endDate);
      }
      if ($request->user_id) {
        $reports->where('user_id', $request->user_id);
      }
      if (Auth::user()->id_user_group != 1) {
        $reports->where('user_id', Auth::user()->id);
      }

      $reports->orderBy('write_time', 'desc');

      return DataTables::of($reports)
        ->addIndexColumn()
        ->editColumn('name', fn($row) => $row->user?->name ?? '')
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 2))
        ->editColumn('write_time', fn($row) => $row->write_time ? date("D jS \\of M Y h:i:s A", strtotime($row->write_time)) : '')
        ->editColumn('last_updated', fn($row) => $row->last_updated ? date("D jS \\of M Y h:i:s A", strtotime($row->last_updated)) : '')
        ->editColumn('sent_time', fn($row) => $row->sent_time ? date("D jS \\of M Y h:i:s A", strtotime($row->sent_time)) : '')
        ->editColumn('rate', fn($row) => $row->user && $row->user->smsRate ? number_format($row->user->smsRate->nonmasking_rate, 2) : '0.00')
        ->editColumn('message', fn($row) => \Illuminate\Support\Str::words($row->message, 12))
        ->editColumn('error_code', fn($row) => $row->dlr_status_code ?? '')
        ->editColumn('error_message', fn($row) => $row->dlr_status ?? '')
        ->editColumn('status', function ($row) {
          return $row->status == 'REJECTD' ? 'Failed' : 'Delivered';
        })
        ->editColumn('source', fn($row) => $row->sendMessage?->source ?? '')
        ->editColumn('campaign_name', fn($row) => $row->sendMessage?->campaign_name ?? '')
        ->make(true);
    }

    $tableHeaders = $this->getTableHeader('last-2days-sms-list');
    $userLists = $this->reportRepository->gerUsers();


    $tableHeaders = $this->getTableHeader('archived-sms-list');
    $userLists = $this->reportRepository->gerUsers();
    return view('reports::archived-sms', compact('title', 'tableHeaders', 'ajaxUrl', 'userLists'));
  }

  //archivedSms
  /*public function archivedSms(Request $request)
  {
    $title = 'Archived Message List';
    $filters = $request->all();

    $datas = $this->reportRepository->getArchivedSms($filters);
    $ajaxUrl = route('archived-sms');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 2))
        ->editColumn('write_time', fn($row) => date("D jS \\of M Y h:i:s A", strtotime($row->write_time)))
        ->editColumn(
          'sent_time',
          fn($row) => $row->sent_time ? date("D jS \\of M Y h:i:s A", strtotime($row->sent_time)) : ''
        )
        ->editColumn(
          'last_updated',
          fn($row) => $row->last_updated ? date("D jS \\of M Y h:i:s A", strtotime($row->last_updated)) : ''
        )
        ->editColumn(
          'rate',
          fn($row) => ($row->sms_cost > 0 and $row->smscount > 0) ? number_format(
            $row->sms_cost / $row->smscount,
            2
          ) : '0.00'
        )
        //->editColumn('api_web', fn($row) => $row->message->source??'')
        //->editColumn('campaign', fn($row) => $row->message->campaign_name??'')
        ->editColumn('message', fn($row) => Str::words($row->message, '12'))
        ->make();
    }

    $tableHeaders = $this->getTableHeader('archived-sms-list');
    $userLists = $this->reportRepository->gerUsers();
    return view('reports::archived-sms', compact('title', 'tableHeaders', 'ajaxUrl', 'userLists'));
  }

  //summeryLog
  public function summeryLog(Request $request)
  {
    $title = 'Summery Log';
    $datas = $this->reportRepository->getSummeryLog($request->all());
    $ajaxUrl = route('summery-log');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->editColumn('userID', fn($row) => $row->user->username)
        ->editColumn('name', fn($row) => $row->user->name)
        ->editColumn('totalsms', fn($row) => number_format($row->totalsms))
        ->editColumn('rate', fn($row) => 'BD ' . number_format($row->rate, 3))
        ->editColumn('total_amount_deduction', fn($row) => 'BDT ' . number_format($row->totalcost, '3'))
        ->make();
    }

    $tableHeaders = $this->getTableHeader('summery-log-list');

    return view('reports::summery-log', compact('title', 'tableHeaders', 'ajaxUrl'));
  }*/

  public function archivedSms_(Request $request)
  {
    $startDate = isset($request->from_date)
      ? Carbon::parse(urldecode($request->from_date))->format('Y-m-d')
      : Carbon::now()->subDays(90)->format('Y-m-d');

    $endDate = isset($request->to_date)
      ? Carbon::parse(urldecode($request->to_date))->format('Y-m-d')
      : Carbon::now()->format('Y-m-d H:i:s');
    if ($request->ajax()) {
      $users = Outbox::select([
        'outbox.mask',
        'outbox.destmn',
        'outbox.message',
        'outbox.last_updated',
        'outbox.write_time',
        'sentmessages.source',
        'outbox.status',
        'outbox.smscount',
        'sentmessages.campaign_name',
        'outbox.sms_cost',
        'outbox.error_message',
        'outbox.error_code',
        'users.name'
      ])
        ->leftJoin('sentmessages', 'outbox.reference_id', '=', 'sentmessages.id')
        ->leftJoin('users', 'users.id', '=', 'outbox.user_id');
      if ($request->message) {
        $users->where('outbox.message', 'like', "%{$request->message}%");
      }
      if ($request->mobile) {
        $users->where('outbox.destmn', 'like', "%{$request->mobile}%");
      }
      if ($request->source) {
        $users->where('sentmessages.source', 'like', "%{$request->source}%");
      }
      if ($startDate) {
        $users->whereDate('outbox.write_time', '>=', $startDate);
      }
      if ($endDate) {
        $users->whereDate('outbox.write_time', '<=', $endDate);
      }
      if ($request->user_id) {
        $users->where('outbox.user_id', $request->user_id);
      }
      if (Auth::user()->id_user_group != 1) {
        $users->where('outbox.user_id', Auth::user()->id);
      }
      $users->orderBy('write_time', 'desc');
      return DataTables::of($users)
        ->addIndexColumn()
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 2))
        ->editColumn('write_time', fn($row) => date("D jS \\of M Y h:i:s A", strtotime($row->write_time)))
        ->editColumn(
          'last_updated',
          fn($row) => $row->last_updated ? date("D jS \\of M Y h:i:s A", strtotime($row->last_updated)) : ''
        )
        ->editColumn(
          'sent_time',
          fn($row) => $row->sent_time ? date("D jS \\of M Y h:i:s A", strtotime($row->sent_time)) : ''
        )
        ->editColumn('rate', fn($row) => number_format($row->sms_rate, 2))
        ->editColumn('message', fn($row) => Str::words($row->message, '12'))
        ->editColumn('error_code', fn($row) => $row->dlr_status_code ?? '')
        ->editColumn('error_message', fn($row) => $row->dlr_status ?? '')
        ->editColumn('status', function ($row) {
          return $row->status == 'REJECTD'
            ? 'Failed'
            : 'Delivered';
        })
        ->make(true);
    }
    $title = 'Archived Message List';
    $tableHeaders = $this->getTableHeader('archived-sms-list');
    $userLists = $this->reportRepository->gerUsers();
    $ajaxUrl = route('archived-sms');
    return view('reports::archived-sms', compact('title', 'tableHeaders', 'ajaxUrl', 'userLists'));
  }

  //dayWiseLog
  public function dayWiseLog(Request $request)
  {
    $title = 'Day Wise Log';
    $filters = $request->all();
    $datas = $this->reportRepository->getDayWiseLog($filters);
    $ajaxUrl = route('day-wise-log');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('total_cost', function ($row) {
          return number_format($row->message_count * $row->nonmasking_rate, 2, '.', '');
        })
        ->addColumn('date_range', function ($row) use ($filters) {
          static $currentDate = null;

          if ($currentDate === null) {
            $currentDate = Carbon::now()->format('Y-m-d');
          }

          $fromDate = $filters['from_date'] ?? null;
          $toDate = $filters['to_date'] ?? null;

          if ($fromDate && $toDate) {
            return $fromDate . ' - ' . $toDate;
          } elseif ($fromDate) {
            return $fromDate;
          } elseif ($toDate) {
            return $toDate;
          } else {
            return $currentDate;
          }
        })
        ->make();
    }

    $tableHeaders = $this->getTableHeader('day-wise-log');
    $userLists = $this->reportRepository->gerUsers();
    return view('reports::day-wise-log', compact('title', 'tableHeaders', 'ajaxUrl', 'userLists'));
  }

  //totalSmsLog
  public function totalSmsLog(Request $request)
  {
    $title = 'Total Sms Log';
    $datas = $this->reportRepository->getTotalSmsLog($request->all());
    $ajaxUrl = route('total-sms-log');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->editColumn('operator_name', fn($row) => $row->operator_name ?? 'Default Operator')
        ->make();
    }

    $tableHeaders = $this->getTableHeader('total-sms-log');

    return view('reports::total-sms-log', compact('title', 'tableHeaders', 'ajaxUrl'));
  }

  public function clientDaywiseSms(Request $request)
  {
    if ($request->ajax()) {
      $query = Outbox::selectRaw(
        "
                SUM(outbox.smscount) as message_count,
                rates.nonmasking_rate,
                users.name,
                DATE(outbox.write_time) as date
            "
      )
        ->join('users', 'outbox.user_id', '=', 'users.id')
        ->join('rates', 'users.sms_rate_id', '=', 'rates.id')
        ->where('outbox.status', '!=', 'REJECTD')
        ->where('outbox.status', '!=', 'PENDING');

      if ($request->from_date) {
        $query->whereDate('outbox.write_time', '>=', Carbon::parse(urldecode($request->from_date))->format('Y-m-d'));
      }

      if ($request->to_date) {
        $query->whereDate('outbox.write_time', '<=', Carbon::parse(urldecode($request->to_date))->format('Y-m-d'));
      }
      if ($request->user_id) {
        $query->where('outbox.user_id', $request->user_id);
      }

      $query->where('users.id_user_group', 4);
      $query->groupBy('outbox.user_id', DB::raw('DATE(outbox.write_time)'));
      $query->orderBy(DB::raw('DATE(outbox.write_time)'), 'ASC');

      return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('total_cost', function ($row) {
          return number_format($row->message_count * $row->nonmasking_rate, 2, '.', '');
        })
        ->make(true);
    }

    $title = 'Client Day Wise SMS';
    $userLists = $this->reportRepository->gerUsers();
    $ajaxUrl = route('client-daywise-sms');

    return view('reports::client-day-wise-sms', compact('title', 'ajaxUrl', 'userLists'));
  }
}

<?php

namespace Modules\Reports\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Modules\Messages\App\Models\Outbox;
use Modules\Reports\App\Repositories\ReportRepositoryInterface;
use Modules\Reports\App\Trait\DataTableTrait;
use Modules\Users\App\Repositories\UserRepositoryInterface;
use Yajra\DataTables\DataTables;
use App\Exports\ArchivedSmsReportExport;
use App\Exports\InboxSmsExport;
use Modules\Messages\App\Models\Inbox;
use Modules\Users\App\Models\User;

class ReportsController extends Controller
{
  use DataTableTrait;

  protected ReportRepositoryInterface $reportRepository;
  protected UserRepositoryInterface $userRepository;

  public function __construct(ReportRepositoryInterface $reportRepository, UserRepositoryInterface $userRepository)
  {
    $this->reportRepository = $reportRepository;
    $this->userRepository = $userRepository;
  }

  public function last2DaysFailedSmsList(Request $request)
  {
    $title = '2 Days Failed Message List';
    $ajaxUrl = route('last-2days-failed-sms-list');

    if ($request->ajax()) {
      $reports = $this->reportRepository->getLast2DaysFailedSmsList($request);
            
      return datatables()->eloquent($reports)
        ->addIndexColumn()
        ->editColumn('name', fn($row) => $row->user?->name ?? '')
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 2))
        ->editColumn('write_time', fn($row) => $row->write_time ? date("Y-m-d H:i:s", strtotime($row->write_time)) : '')
        // ->editColumn('last_updated', fn($row) => $row->last_updated ? date("Y-m-d H:i:s", strtotime($row->last_updated)) : '')
        ->editColumn('sent_time', fn($row) => $row->sent_time ? date("Y-m-d H:i:s", strtotime($row->sent_time)) : '')
        ->editColumn('rate', function ($row) {
              // Check if mask length is 13
              if (strlen($row->mask) === 13) {
                  // Nonmasking rate
                  return number_format($row->user->smsRate->nonmasking_rate, 4);
              } else {
                  // Masking rate
                  return number_format($row->user->smsRate->masking_rate, 4);
              }
          })
        // ->editColumn('message', fn($row) => \Illuminate\Support\Str::words($row->message, 12))
        ->editColumn('error_code', fn($row) => $row->dlr_status_code ?? '')
        ->editColumn('error_message', fn($row) => $this->getDlrStatusInfo($row->dlr_status_code))
        ->editColumn('source', fn($row) => $row->sendMessage?->source ?? '')
        ->editColumn('campaign_name', fn($row) => $row->sendMessage?->campaign_name ?? '')
        ->make(true);
    }

    $operators = ['gp' => 'Grameenphone', 'bl' => 'Banglalink', 'rb' => 'Robi/Airtel', 'tt' => 'Teletalk'];
    $tableHeaders = $this->getTableHeader('last-2days-sms-list');
    $userLists = $this->reportRepository->gerUsers();
    $senderIds = $this->reportRepository->getSenderIds();

    return view('reports::last-2days-failed-sms-list', compact('operators', 'title', 'tableHeaders', 'ajaxUrl', 'userLists','senderIds'));
  }

  //FAILED ARCHIVE MESSAGE
  public function failedArchivedSms(Request $request)
  {
    $title = '2 Days Failed Message List';
    $ajaxUrl = route('failed-archived-sms');

    if ($request->ajax()) {
      $reports = $this->reportRepository->getFailedArchivedSms($request);

      return datatables()->eloquent($reports)
        ->addIndexColumn()
        ->editColumn('name', fn($row) => $row->user?->name ?? '')
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 2))
        ->editColumn('write_time', fn($row) => $row->write_time ? date("Y-m-d H:i:s", strtotime($row->write_time)) : '')
        // ->editColumn('last_updated', fn($row) => $row->last_updated ? date("Y-m-d H:i:s", strtotime($row->last_updated)) : '')
        ->editColumn('sent_time', fn($row) => $row->sent_time ? date("Y-m-d H:i:s", strtotime($row->sent_time)) : '')
        ->editColumn('rate', function ($row) {
              // Check if mask length is 13
              if (strlen($row->mask) === 13) {
                  // Nonmasking rate
                  return number_format($row->user->smsRate->nonmasking_rate, 4);
              } else {
                  // Masking rate
                  return number_format($row->user->smsRate->masking_rate, 4);
              }
          })
        // ->editColumn('message', fn($row) => \Illuminate\Support\Str::words($row->message, 12))
        ->editColumn('error_code', fn($row) => $row->dlr_status_code ?? '')
        ->editColumn('error_message', fn($row) => $this->getDlrStatusInfo($row->dlr_status_code))
        ->editColumn('source', fn($row) => $row->sendMessage?->source ?? '')
        ->make(true);
    }

    $tableHeaders = $this->getTableHeader('last-2days-sms-list');
    $operators = ['gp' => 'Grameenphone', 'bl' => 'Banglalink', 'rb' => 'Robi/Airtel', 'tt' => 'Teletalk'];
    $userLists = $this->reportRepository->gerUsers();
    $senderIds = $this->reportRepository->getSenderIds();

    return view('reports::failed-archived-sms', compact('operators', 'title', 'tableHeaders', 'ajaxUrl', 'userLists','senderIds'));
  }


  public function last2DaysSmsList(Request $request)
  {
    $title = '2 Days Details Report List';
    $ajaxUrl = route('last-2days-sms-list');

    if ($request->ajax()) {
      $reports = $this->reportRepository->getLast2DaysSmsList($request);

      // dd($reports->get());

      return datatables()->eloquent($reports)
        ->addIndexColumn()
        ->editColumn('name', fn($row) => $row->user?->name ?? '')
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 4))
        ->editColumn('write_time', fn($row) => $row->write_time ? date("Y-m-d H:i:s", strtotime($row->write_time)) : '')
        // ->editColumn('last_updated', fn($row) => $row->last_updated ? date("Y-m-d H:i:s", strtotime($row->last_updated)) : '')
        ->editColumn('sent_time', fn($row) => $row->sent_time ? date("Y-m-d H:i:s", strtotime($row->sent_time)) : '')
        ->editColumn('rate', function ($row) {
            // Check if mask length is 13
            if (strlen($row->mask) === 13) {
                // Nonmasking rate
                return number_format($row->user->smsRate->nonmasking_rate, 4);
            } else {
                // Masking rate
                return number_format($row->user->smsRate->masking_rate, 4);
            }
        })

        // ->editColumn('message', fn($row) => \Illuminate\Support\Str::words($row->message, 12))
        ->editColumn('error_code', fn($row) => $row->dlr_status_code ?? '')
        ->editColumn('error_message', fn($row) => $this->getDlrStatusInfo($row->dlr_status_code))
        ->editColumn('source', fn($row) => $row->sendMessage?->source ?? '')
        ->make(true);
    }

    $tableHeaders = $this->getTableHeader('last-2days-sms-list');
    $operators = ['gp' => 'Grameenphone', 'bl' => 'Banglalink', 'rb' => 'Robi/Airtel', 'tt' => 'Teletalk'];
    $userLists = $this->reportRepository->gerUsers();
    $senderIds = $this->reportRepository->getSenderIds();

    return view('reports::last-2days-sms-list', compact('operators', 'title', 'tableHeaders', 'ajaxUrl', 'userLists','senderIds'));
  }


  public function archivedSms(Request $request)
  {
    $title = 'Archived SMS Report List';
    $ajaxUrl = route('archived-sms');

    if ($request->ajax()) {
      $query = $this->reportRepository->getArchivedSms($request);

      return datatables()->eloquent($query)
        ->addIndexColumn()
        ->editColumn('name', fn($row) => $row->user?->name ?? '')
        ->editColumn('sms_cost', fn($row) => number_format($row->sms_cost, 2))
        ->editColumn('write_time', fn($row) => $row->write_time ? date("Y-m-d H:i:s", strtotime($row->write_time)) : '')
        // ->editColumn('last_updated', fn($row) => $row->last_updated ? date("Y-m-d H:i:s", strtotime($row->last_updated)) : '')
        ->editColumn('sent_time', fn($row) => $row->sent_time ? date("Y-m-d H:i:s", strtotime($row->sent_time)) : '')
        ->editColumn('rate', function ($row) {
              // Check if mask length is 13
              if (strlen($row->mask) === 13) {
                  // Nonmasking rate
                  return number_format($row->user->smsRate->nonmasking_rate, 4);
              } else {
                  // Masking rate
                  return number_format($row->user->smsRate->masking_rate, 4);
              }
          })
        // ->editColumn('message', fn($row) => \Illuminate\Support\Str::words($row->message, 12))
        ->editColumn('error_code', fn($row) => $row->dlr_status_code ?? '')
        ->editColumn('error_message', fn($row) => $this->getDlrStatusInfo($row->dlr_status_code))
        ->editColumn('source', fn($row) => $row->sendMessage?->source ?? '')
        ->make(true);
    }

    $tableHeaders = $this->getTableHeader('archived-sms-list');
    $userLists = $this->reportRepository->gerUsers();
    $operators = ['gp' => 'Grameenphone', 'bl' => 'Banglalink', 'rb' => 'Robi/Airtel', 'tt' => 'Teletalk'];
    $senderIds = $this->reportRepository->getSenderIds();

    return view('reports::archived-sms', compact('operators', 'title', 'tableHeaders', 'ajaxUrl', 'userLists', 'senderIds'));
  }


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

          $fromDate = $filters['from_date'] ?? Carbon::today()->startOfDay()->format('Y-m-d H:i');
          $toDate   = $filters['to_date']   ?? Carbon::now()->format('Y-m-d H:i');


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
    // dd($request->search['value']);
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
          // -------------------------
          // Common date filters
          // -------------------------
          $startDate = $request->from_date
              ? Carbon::parse(urldecode($request->from_date))->format('Y-m-d H:i:s')
              : null;

          $endDate = $request->to_date
              ? Carbon::parse(urldecode($request->to_date))->format('Y-m-d H:i:s')
              : null;

          // -------------------------
          // Function to build query for any table
          // -------------------------
          $buildQuery = function ($table) use ($request, $startDate, $endDate) {
              $query = DB::table($table)
                  ->join('users', "$table.user_id", '=', 'users.id')
                  ->join('rates', 'users.sms_rate_id', '=', 'rates.id')
                  ->selectRaw("
                      SUM($table.smscount) as message_count,
                      rates.nonmasking_rate,
                      users.name,
                      DATE($table.write_time) as date,
                      $table.user_id
                  ");

              if ($startDate) {
                  $query->where("$table.write_time", '>=', $startDate);
              }

              if ($endDate) {
                  $query->where("$table.write_time", '<=', $endDate);
              }

              if ($request->user_id) {
                  $query->where("$table.user_id", $request->user_id);
              }

              $query->where('users.id_user_group', 4);

              if (auth()->user()->id_user_group != 1) {
                  $userIds = User::where('created_by', auth()->user()->id)->pluck('id')->toArray();
                  $userIds[] = auth()->user()->id;
                  $query->whereIn("$table.user_id", $userIds);
              }

              $query->groupBy(
                  "$table.user_id",
                  'rates.nonmasking_rate',
                  'users.name',
                  DB::raw("DATE($table.write_time)")
              );

              return $query;
          };

          // -------------------------
          // Build queries for outbox & outbox_history
          // -------------------------
          $outboxQuery = $buildQuery('outbox');
          $historyQuery = $buildQuery('outbox_history');

          // -------------------------
          // Union both queries
          // -------------------------
          $unionQuery = $outboxQuery->unionAll($historyQuery);

          // -------------------------
          // Final aggregation to merge counts per user & date
          // -------------------------
          $finalQuery = DB::table(DB::raw("({$unionQuery->toSql()}) as combined"))
              ->mergeBindings($unionQuery)
              ->selectRaw("
                  SUM(message_count) as message_count,
                  nonmasking_rate,
                  name,
                  date,
                  user_id
              ")
              ->groupBy('user_id', 'nonmasking_rate', 'name', 'date')
              ->orderBy('date', 'ASC');

          // -------------------------
          // DataTables response
          // -------------------------
          return DataTables::of($finalQuery)
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


  // public function getBtrcReport(Request $request)
  // {
  //   $title = 'BTRC Report';
  //   $ajaxUrl = route('btrc-report');

  //   if ($request->ajax()) {
  //     $reports = $this->reportRepository->getBtrcReport($request);

  //     return DataTables::of($reports)
  //       ->addIndexColumn()
  //       ->editColumn('incoming', fn($row) => number_format($row->incoming))
  //       ->editColumn('outgoing', fn($row) => number_format($row->outgoing))
  //       ->make(true);
  //   }
  //   $fromDate = $request->from_date ?? now()->startOfMonth()->toDateString();
  //   $toDate = $request->to_date ?? now()->endOfMonth()->toDateString();
  //   $tableHeaders = $this->getTableHeader('btrc-report-list');
  //   return view('reports::btrc-report', compact('title', 'tableHeaders', 'ajaxUrl', 'fromDate', 'toDate'));
  // }

  public function getBtrcReport(Request $request)
  {
      $title = 'BTRC Report';
      $ajaxUrl = route('btrc-report');

      if ($request->ajax()) {
          $reports = $this->reportRepository->getBtrcReport($request);

          // Calculate totals
          $totals = [
              'Operator' => 'Total',
              'Delivered' => $reports->sum(fn($r) => (int)$r['Delivered']),
              'UnDelivered' => $reports->sum(fn($r) => (int)$r['UnDelivered']),
              'Pending' => $reports->sum(fn($r) => (int)$r['Pending']),
              'Total' => $reports->sum(fn($r) => (int)$r['Total']),
          ];

          // Append totals row
          $reports->push((object)$totals);

          return DataTables::of($reports)
              ->addIndexColumn()
              ->make(true);
      }

      $fromDate = $request->from_date ?? now()->startOfMonth()->toDateString();
      $toDate = $request->to_date ?? now()->endOfMonth()->toDateString();
      $tableHeaders = $this->getTableHeader('btrc-report-list');

      return view('reports::btrc-report', compact('title', 'tableHeaders', 'ajaxUrl', 'fromDate', 'toDate'));
  }


  public function archivedSmsReportExport(Request $request)
  {
    $fileName = $request->report_type .'_sms_report_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
    return app(Excel::class)->download(new ArchivedSmsReportExport($request), $fileName);
  }

  public function inboxReportExport(Request $request)
  {
    $fileName = $request->report_type .'inbox_report' . now()->format('Y_m_d_H_i_s') . '.xlsx';
    return app(Excel::class)->download(new InboxSmsExport($request), $fileName);
  }

  public function getInboxReport(Request $request)
  {
    
    $title = 'Inbox List';
    $ajaxUrl = route('inbox-report');

    if (env('APP_TYPE') == 'Aggregator') {
      $this->reportRepository->getUnreadIncomingMessage($request);
    }

    if ($request->ajax()) {
      $query = $this->reportRepository->getInboxReport($request);

      return datatables()->eloquent($query)
        ->addIndexColumn()
        ->editColumn('message', fn($row) => isset($row->message) ? \Illuminate\Support\Str::words($row->message, 12) : '')
        ->editColumn('created_at', fn($row) => $row->created_at ? date("Y-m-d H:i:s", strtotime($row->created_at)) : '')
        ->make(true);
    }

    // dd(Inbox::get()->toArray());

    $tableHeaders = $this->getTableHeader('inbox-list');
    $fromDate = $request->from_date ?? now()->subMonths(3)->startOfMonth()->toDateString();
    $toDate = $request->to_date ?? now()->endOfMonth()->toDateString();
    return view('reports::inbox-list', compact( 'title', 'tableHeaders', 'ajaxUrl','fromDate', 'toDate'));
  }

  private function getDlrStatusInfo($code): string
  {
    $map = [
      200 => ['status' => 'Delivered', 'meaning' => 'Delivered in Handset'],
      6 => ['status' => 'Absent subscriber for SM', 'meaning' => 'Subscriber handset is not logged onto the network...'],
      32 => ['status' => 'Undelivered', 'meaning' => 'No memory capacity on handset...'],
      31 => ['status' => 'Subscriber Busy', 'meaning' => 'MSC is busy handling an existing transaction...'],
      5 => ['status' => 'Unidentified subscriber', 'meaning' => 'MT number is unknown in the MT network’s MSC'],
      13 => ['status' => 'Barred subscriber', 'meaning' => 'A Barred Number is a number that cannot receive SMS...'],
      9 => ['status' => 'Illegal subscriber', 'meaning' => 'Sender ID Blocked by operators for Illegal SMS Traffic'],
      36 => ['status' => 'Sender ID Blocked', 'meaning' => 'Sender ID Blocked by operators for Illegal SMS Traffic'],
      34 => ['status' => 'System Failure', 'meaning' => 'Rejection due to SS7 protocol or network failure'],
      8 => ['status' => 'Network Failure', 'meaning' => 'Network failure in SMSC Link'],
      400 => ['status' => 'SMSC Timeout-abort', 'meaning' => 'SMSC timeout (Network problem)'],
      456 => ['status' => 'SMSC Timeout-abort', 'meaning' => 'SMSC timeout (Network problem)'],
      8001 => ['status' => 'SRI Response not found', 'meaning' => 'SRI Response not found'],
      9001 => ['status' => 'FSM Response not found', 'meaning' => 'FSM Response not found'],
      1 => ['status' => 'Message sent to Engine', 'meaning' => 'Message sent to Engine'],
      1001 => ['status' => 'Message received at Engine', 'meaning' => 'Message received at Engine'],
      1002 => ['status' => 'Message sent to Queue', 'meaning' => 'Message sent to Queue'],
      9005 => ['status' => 'Wrong encoding', 'meaning' => 'Wrong encoding'],
    ];

    return $map[$code]['status'] ?? 'SMS Submitted';
  }

}

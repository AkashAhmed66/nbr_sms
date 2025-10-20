<?php

namespace Modules\Reports\App\Repositories;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Models\Inbox;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;
use Modules\Messages\App\Models\OutboxHistory;
use Modules\Users\App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ReportRepository implements ReportRepositoryInterface
{
  protected $outbox;
  protected $outboxHistory;
  protected $message;
  private $user_id = '';
  private $user_group_id = '';

  public function __construct(Outbox $outbox, OutboxHistory $outboxHistory, Message $message, User $user)
  {
    $this->outbox = $outbox;
    $this->outboxHistory = $outboxHistory;
    $this->user = auth()->user()->id;
    $this->user_group_id = auth()->user()->id_user_group;
    $this->message = $message;
    $this->user = $user;
  }

  public function getLast2DaysFailedSmsList($request): Builder
  {
    $startDate = isset($request->from_date)
      ? Carbon::parse(urldecode($request->from_date))->format('Y-m-d H:i:s')
      : Carbon::now()->subDays(2)->startOfDay();

    $endDate = isset($request->to_date)
      ? Carbon::parse(urldecode($request->to_date))->format('Y-m-d H:i:s')
      : Carbon::now()->endOfDay();

    $reports = Outbox::with(['user.smsRate', 'sendMessage']);

    // $reports->where('dlr_status_code', '!=', 200);
    $reports->where('dlr_status', '!=', "Delivered");

    if ($request->operator == 'gp') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%17%')
          ->orWhere('operator_prefix', 'like', '%13%');
      });
    }

    if ($request->operator == 'bl') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%19%')
          ->orWhere('operator_prefix', 'like', '%14%');
      });
    }

    if ($request->operator == 'rb') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%16%')
          ->orWhere('operator_prefix', 'like', '%18%');
      });
    }

    if ($request->operator == 'tt') {
      $reports->where('operator_prefix', 'like', '%15%');
    }

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
      $reports->where('write_time', '>=', $startDate);
    }
    if ($endDate) {
      $reports->where('write_time', '<=', $endDate);
    }
    if ($request->senderId) {
      $reports->where('mask', $request->senderId);
    }

    if ($request->user_id) {
      $reports->where('user_id', $request->user_id);
    }
    if (Auth::user()->id_user_group != 1) {
      $reports->where('user_id', Auth::user()->id);
    }

    return $reports->orderBy('write_time', 'desc');
  }

  public function getFailedArchivedSms($request): Builder
  {
    $startDate = isset($request->from_date)
      ? Carbon::parse(urldecode($request->from_date))->format('Y-m-d H:i:s')
      : Carbon::now()->subDays(90)->startOfDay();

    $endDate = isset($request->to_date)
      ? Carbon::parse(urldecode($request->to_date))->format('Y-m-d H:i:s')
      : Carbon::now()->endOfDay();

    $reports = OutboxHistory::with(['user.smsRate', 'sendMessage']);


    // $reports->where('dlr_status_code', '!=', 200);

    $reports->where('dlr_status', '!=', "Delivered");

    if ($request->operator == 'gp') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%17%')
          ->orWhere('operator_prefix', 'like', '%13%');
      });
    }

    if ($request->operator == 'bl') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%19%')
          ->orWhere('operator_prefix', 'like', '%14%');
      });
    }

    if ($request->operator == 'rb') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%16%')
          ->orWhere('operator_prefix', 'like', '%18%');
      });
    }

    if ($request->operator == 'tt') {
      $reports->where('operator_prefix', 'like', '%15%');
    }

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
      $reports->where('write_time', '>=', $startDate);
    }
    if ($endDate) {
      $reports->where('write_time', '<=', $endDate);
    }

    if ($request->user_id) {
      $reports->where('user_id', $request->user_id);
    }

    if (Auth::user()->id_user_group != 1) {
      $reports->where('user_id', Auth::user()->id);
    }

    return $reports->orderBy('write_time', 'desc');
  }

  public function getLast2DaysSmsList($request): Builder
  {
    $startDate = isset($request->from_date)
      ? Carbon::parse(urldecode($request->from_date))->format('Y-m-d H:i:s')
      : Carbon::now()->subDays(2)->startOfDay();

    $endDate = isset($request->to_date)
      ? Carbon::parse(urldecode($request->to_date))->format('Y-m-d H:i:s')
      : Carbon::now()->endOfDay();

    $reports = Outbox::with(['user.smsRate', 'sendMessage']);

    //$reports->where('dlr_status_code', '=', 200);

    if ($request->operator == 'gp') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%17%')
          ->orWhere('operator_prefix', 'like', '%13%');
      });
    }

    if ($request->operator == 'bl') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%19%')
          ->orWhere('operator_prefix', 'like', '%14%');
      });
    }

    if ($request->operator == 'rb') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%16%')
          ->orWhere('operator_prefix', 'like', '%18%');
      });
    }

    if ($request->operator == 'tt') {
      $reports->where('operator_prefix', 'like', '%15%');
    }

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
      $reports->where('write_time', '>=', $startDate);
    }
    if ($endDate) {
      $reports->where('write_time', '<=', $endDate);
    }

    if ($request->senderId) {
      $reports->where('mask', $request->senderId);
    }

    if ($request->user_id) {
      $reports->where('user_id', $request->user_id);
    }
    if (Auth::user()->id_user_group != 1) {
      $reports->where('user_id', Auth::user()->id);
    }

    return $reports->orderBy('write_time', 'desc');
  }

  public function getArchivedSms($request): Builder
  {
    $startDate = isset($request->from_date)
      ? Carbon::parse(urldecode($request->from_date))->format('Y-m-d H:i:s')
      : Carbon::now()->subDays(90)->startOfDay()->toDateString();

    $endDate = isset($request->to_date)
      ? Carbon::parse(urldecode($request->to_date))->format('Y-m-d H:i:s')
      : Carbon::now()->endOfDay()->toDateString();

    $reports = OutboxHistory::with(['user.smsRate', 'sendMessage']);

    if ($request->operator == 'gp') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%17%')
          ->orWhere('operator_prefix', 'like', '%13%');
      });
    }

    if ($request->operator == 'bl') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%19%')
          ->orWhere('operator_prefix', 'like', '%14%');
      });
    }

    if ($request->operator == 'rb') {
      $reports->where(function ($q) {
        $q->where('operator_prefix', 'like', '%16%')
          ->orWhere('operator_prefix', 'like', '%18%');
      });
    }

    if ($request->operator == 'tt') {
      $reports->where('operator_prefix', 'like', '%15%');
    }

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
      $reports->where('write_time', '>=', $startDate);
    }

    if ($endDate) {
      $reports->where('write_time', '<=', $endDate);
    }

    if ($request->senderId) {
      $reports->where('mask', $request->senderId);
    }

    if ($request->user_id) {
      $reports->where('user_id', $request->user_id);
    }

    if (Auth::user()->id_user_group != 1) {
      $reports->where('user_id', Auth::user()->id);
    }

    return $reports->orderBy('write_time', 'desc');
  }


  public function getSummeryLog(array $filters = []): Collection
  {
    $filters['from_date'] = Carbon::now()->subDays(300)->format('Y-m-d');
    $filters['to_date'] = Carbon::now()->subDays(200)->format('Y-m-d');

    if ($this->user_group_id == 1 && $this->user_group_id == 2) {
      $query = DB::table('sentmessage_stats')
        ->selectRaw(
          'users.id as user_id, users.username, users.name, rates.selling_nonmasking_rate as rate, sum(sentmessages.sms_count) as totalsms,  sum(sentmessage_stats.user_cost) as totalcost'
        )
        ->join('sentmessages', 'sentmessage_stats.sentmessage_id', '=', 'sentmessages.id')
        ->join('users', 'users.id', '=', 'sentmessages.user_id')
        ->join('rates', 'users.sms_rate_id', '=', 'rates.id')
        ->whereDate('sentmessages.created_at', '>=', $filters['from_date'])
        ->whereDate('sentmessages.created_at', '<=', $filters['to_date'])
        ->groupBy('sentmessages.user_id')
        ->orderBy('sentmessages.id')
        ->get();
      return $query;
    } elseif (isset($filters['user_id'])) {
      $query = DB::table('sentmessage_stats')
        ->selectRaw(
          'users.id as user_id, users.username, users.name, rates.selling_nonmasking_rate as rate, sum(sentmessages.sms_count) as totalsms,  sum(sentmessage_stats.user_cost) as totalcost'
        )
        ->join('sentmessages', 'sentmessage_stats.sentmessage_id', '=', 'sentmessages.id')
        ->join('users', 'users.id', '=', 'sentmessages.user_id')
        ->join('rates', 'users.sms_rate_id', '=', 'rates.id')
        ->whereIn('user_id', $filters['user_id'])
        ->whereDate('sentmessages.created_at', '>=', $filters['from_date'])
        ->whereDate('sentmessages.created_at', '<=', $filters['to_date'])
        ->groupBy('sentmessages.user_id')
        ->orderBy('sentmessages.id')
        ->get();
      return $query;
    } else {
      $query = DB::table('sentmessage_stats')
        ->selectRaw(
          'users.id as user_id, users.username, users.name, rates.selling_nonmasking_rate as rate, sum(sentmessages.sms_count) as totalsms,  sum(sentmessage_stats.user_cost) as totalcost'
        )
        ->join('sentmessages', 'sentmessage_stats.sentmessage_id', '=', 'sentmessages.id')
        ->join('users', 'users.id', '=', 'sentmessages.user_id')
        ->join('rates', 'users.sms_rate_id', '=', 'rates.id')
        ->where('user_id', $this->user_id)
        ->whereDate('sentmessages.created_at', '>=', $filters['from_date'])
        ->whereDate('sentmessages.created_at', '<=', $filters['to_date'])
        ->groupBy('sentmessages.user_id')
        ->orderBy('sentmessages.id')
        ->get();
      return $query;
    }
  }

  public function getDayWiseLog(array $filters = []): Collection
  {
      $startDate = isset($filters['from_date'])
          ? Carbon::parse(urldecode($filters['from_date']))->format('Y-m-d H:i:s')
          : Carbon::now()->startOfDay()->format('Y-m-d H:i:s');

      $endDate = isset($filters['to_date'])
          ? Carbon::parse(urldecode($filters['to_date']))->format('Y-m-d H:i:s')
          : Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

      $userQuery = User::query()
          ->leftJoin('rates', 'users.sms_rate_id', '=', 'rates.id')
          ->leftJoin(DB::raw("
              (SELECT user_id, SUM(smscount) AS message_count
              FROM outbox
              WHERE created_at BETWEEN '$startDate' AND '$endDate'
              GROUP BY user_id) as outbox_data
          "), 'users.id', '=', 'outbox_data.user_id')
          ->leftJoin(DB::raw("
              (SELECT user_id, SUM(smscount) AS message_count
              FROM outbox_history
              WHERE created_at BETWEEN '$startDate' AND '$endDate'
              GROUP BY user_id) as history_data
          "), 'users.id', '=', 'history_data.user_id')
          ->select([
              'users.id as user_id',
              'users.name',
              'rates.nonmasking_rate',
              DB::raw('COALESCE(outbox_data.message_count, 0) + COALESCE(history_data.message_count, 0) as message_count')
          ]);

      // Optional user filter
      if (!empty($filters['user_id'])) {
          $userQuery->where('users.id', $filters['user_id']);
      }

      // Restrict based on user group
      if (auth()->user()->id_user_group != 1) {
          $userIds = User::where('created_by', auth()->user()->id)->pluck('id')->toArray();
          $userIds[] = auth()->user()->id;
          $userQuery->whereIn('users.id', $userIds);
      }

      return new Collection($userQuery->get());
  }

  public function getTotalSmsLog(array $filters = []): Collection
  {
      $search = $filters['search']['value'] ?? null;
      // -------------------------
      // Function to build query for any table
      // -------------------------
      $buildQuery = function ($table, $search = null) {
          $query = DB::table($table)
              ->selectRaw("$table.srcmn as senderID, $table.operator_prefix, SUM($table.smscount) as message_count")
              ->groupBy("$table.srcmn")
              ->groupBy("$table.operator_prefix");

          // Filter by auth user if not super admin
          if (auth()->user()->id_user_group != 1) {
              $query->where("$table.user_id", auth()->user()->id);
          }

          // Apply LIKE filter if $search is provided
          if (!empty($search)) {
              $query->where(function ($q) use ($table, $search) {
                  $q->where("$table.srcmn", 'like', "%$search%")
                    ->orWhere("$table.operator_prefix", 'like', "%$search%");
              });
          }

          return $query;
      };

      // -------------------------
      // Build queries for outbox & outbox_history
      // -------------------------
      $outboxQuery = $buildQuery('outbox', $search);
      $historyQuery = $buildQuery('outbox_history', $search);

      // -------------------------
      // Union both queries
      // -------------------------
      $unionQuery = $outboxQuery->unionAll($historyQuery);

      // -------------------------
      // Final aggregation to merge both
      // -------------------------
      $results = DB::table(DB::raw("({$unionQuery->toSql()}) as combined"))
          ->mergeBindings($unionQuery)
          ->selectRaw('senderID, operator_prefix, SUM(message_count) as message_count')
          ->groupBy('senderID', 'operator_prefix')
          ->get();

      // Wrap into Eloquent\Collection
      return new Collection($results);
  }

  public function gerUsers(array $filters = []): Collection
  {
    $query = $this->user->query();

    $query->where('id_user_group', '!=', 1);

    if (auth()->user()->id_user_group != 1) {
      $userIds = User::where('created_by', auth()->user()->id)->pluck('id')->toArray();
      $userIds[] = auth()->user()->id;
      $query->whereIn('id', $userIds);
    }

    return $query->get();
  }

  public function getBtrcReport($request)
  {
      $fromDate = isset($request->from_date)
          ? Carbon::parse($request->from_date)->toDateTimeString()
          : now()->startOfMonth()->startOfDay()->toDateTimeString();

      $toDate = isset($request->to_date)
          ? Carbon::parse($request->to_date)->toDateTimeString()
          : now()->endOfMonth()->endOfDay()->toDateTimeString();

      $user = auth()->user();

      // Define operators
      $operators = [
          17 => 'Grameenphone',
          18 => 'Robi',
          19 => 'Banglalink',
          15 => 'Teletalk'
      ];

      // Aggregate outbox data
      $outboxData = DB::table('outbox')
          ->selectRaw("
              operator_prefix,
              SUM(CASE WHEN dlr_status_code = 200 THEN 1 ELSE 0 END) as Delivered,
              SUM(CASE WHEN dlr_status_code NOT IN (200, -1) THEN 1 ELSE 0 END) as UnDelivered,
              SUM(CASE WHEN dlr_status_code = -1 THEN 1 ELSE 0 END) as Pending,
              COUNT(*) as Total
          ")
          ->whereBetween('created_at', [$fromDate, $toDate])
          ->when($user->id_user_group == 2, function ($query) use ($user) {
              $childUserIds = DB::table('users')
                  ->where('created_by', $user->id)
                  ->pluck('id')
                  ->toArray();
              $userIds = array_merge([$user->id], $childUserIds);
              $query->whereIn('user_id', $userIds);
          })
          ->when($user->id_user_group == 3, function ($query) use ($user) {
              $query->where('user_id', $user->id);
          })
          ->groupBy('operator_prefix');

      // Left join with the operators to ensure all exist
      $data = collect($operators)->map(function($operatorName, $prefix) use ($outboxData) {
          $row = $outboxData->get()->firstWhere('operator_prefix', $prefix);
          return [
              'Operator'    => $operatorName,
              'Delivered'   => $row->Delivered ?? 0,
              'UnDelivered' => $row->UnDelivered ?? 0,
              'Pending'     => $row->Pending ?? 0,
              'Total'       => $row->Total ?? 0,
          ];
      });

      return $data;
  }


  public function getSenderIds()
  {
    if (auth()->user()->id_user_group != 1) {
      $senderIds = DB::table('senderid')
        ->select('senderID')
        ->where('user_id', auth()->user()->id)
        ->get();
    } else {
      $senderIds = DB::table('senderid')
        ->select('senderID')
        ->where('user_id', '!=', 0)
        ->get();
    }


    if ($senderIds->isEmpty()) {
      return [];
    }
    return $senderIds->pluck('senderID')->toArray();
  }

  public function getInboxReport(Request $request)
  {
      $fromDate = $request->from_date ?? now()->startOfMonth()->toDateString();
      $toDate = $request->to_date ?? now()->endOfMonth()->toDateString();

      $query = Inbox::query()->whereBetween(DB::raw('DATE(created_at)'), [$fromDate, $toDate]);

      if (auth()->user()->id_user_group != 1) {
          $senderIds = DB::table('senderid')
              ->where('user_id', auth()->user()->id)
              ->pluck('senderID');

          if ($senderIds->isEmpty()) {
              // No sender IDs means no accessible inbox messages
              // Return an empty query result by forcing a false condition
              return Inbox::query()->whereRaw('0 = 1');
          }

          // from all the senderIds remove 880 from starting if exists and add 47000 in starting
          $senderIds = $senderIds->map(function ($id) {
              $id = preg_replace('/^880/', '', $id);
              return '47000' . $id;
          });

          $query->whereIn('receiver', $senderIds);
      }

      return $query->orderBy('created_at', 'desc');
  }



  public function getUnreadIncomingMessage($request)
  {
    try {
      $senderIds = DB::table('senderid')
        ->where('user_id', auth()->id())
        ->pluck('senderID');

      // dd($senderIds->toArray());

      if ($senderIds->isEmpty()) {
        return; // No sender IDs, nothing to fetch
      }

      $senderIds = $senderIds->map(function ($id) {
          $id = preg_replace('/^880/', '', $id);
          return '47000' . $id;
      });


      $receiverParam = $senderIds->implode(',');

      $client = app(Client::class);

      $response = $client->get('https://smsc.metro.net.bd/api/v1/inbox', [
        'query' => [
          'receiver' => $receiverParam,
        ],
        'timeout' => 10, // Optional: avoid curl timeout issues
      ]);

      
      
      if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody()->getContents(), true);
                
        if (!is_array($data)) {
          throw new \Exception("Invalid response format. Expected JSON array.");
        }

        $inboxData = [];
        if (!$data['error']) {
          foreach ($data['messages'] as $item) {
            $inboxData[] = [
              'message' => $item['message'] ?? '',
              'receiver' => $item['receiver'] ?? '',
              'sender' => $item['sender'] ?? '',
              'reference_no' => isset($item['reference_no']) && $item['reference_no'] !== '' ? $item['reference_no'] : 0,
              'operator_prefix' => $item['operator_prefix'] ?? null,
              'smscount' => isset($item['smscount']) && is_numeric($item['smscount']) ? (int)$item['smscount'] : 0,
              'read' => isset($item['read']) && is_numeric($item['read']) ? (int)$item['read'] : 0,
              'part_no' => isset($item['part_no']) && is_numeric($item['part_no']) ? (int)$item['part_no'] : 0,
              'total_parts' => isset($item['total_parts']) && is_numeric($item['total_parts']) ? (int)$item['total_parts'] : 0,
              'created_at' => $item['created_at'] ?? now(),
            ];
          }


          // use db facade to insert multiple rows
          if (!empty($inboxData)) {
            DB::table('inbox')->insert($inboxData);
          }
        }
      } else {
        Log::warning("Inbox API responded with status: " . $response->getStatusCode());
      }
    } catch (\Throwable $e) {
      //dd($e->getMessage());
      Log::error("Unread inbox fetch failed: " . $e->getMessage());
    }

  }


}

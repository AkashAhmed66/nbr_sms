<?php

namespace Modules\Campaign\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Campaign\App\Repositories\CampaignRepositoryInterface;
use Modules\Campaign\App\Trait\DataTableTrait;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Repositories\MessageRepositoryInterface;
use Yajra\DataTables\DataTables;

class CampaignController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;

  protected CampaignRepositoryInterface $campaignRepository;
  protected MessageRepositoryInterface $messageRepository;

  public function __construct(CampaignRepositoryInterface $campaignRepository, MessageRepositoryInterface $messageRepository)
  {
    $this->campaignRepository = $campaignRepository;
    $this->messageRepository = $messageRepository;
  }

  //SCHEDULE CAMPAIGN LIST
  public function scheduleCampaignList(Request $request)
  {
    $title = 'Campaign List';
    $datas = $this->campaignRepository->getScheduleCampaignList($request->all());
    $ajaxUrl = route('schedule-campaign-list');


    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('username', fn($row) => $row->user->username)
        ->addColumn('senderId', fn($row) => $row->senderID ?? '-')
        ->addColumn('recipientList', function ($row) {
          if ($row['sms_type'] == 'sendSms') {
            return $row['recipient'] ?? '-';
          }
          if ($row['sms_type'] == 'groupSms') {
            return $row['smsGroup']['name'] ?? 'Multiple Group';
          }
          if ($row['sms_type'] == 'fileSms') {
            return $row['file'] ?? '-';
          }
          return "-";
        })
        ->addColumn('message', function ($row) {
          return Str::limit($row['message'], 50);
        })
        ->editColumn('status', function ($row) {
          return match ($row->status) {
            'Queue' => 'Submitted',
            'Sent' => 'Processed',
            'Sending' => 'Processing',
            default => $row->status,
          };
        })
        ->editColumn('date', function ($row) {
          return $row->date ? date("D jS \\of M Y h:i:s A", strtotime($row->date)) : null;
        })
        ->editColumn('total_recipient', function ($row) {
          return number_format($row->total_recipient);
        })
        ->editColumn('recipient', function ($row) {
          return Str::limit($row['recipient'], 40);
        })
        ->rawColumns(['username', 'senderID'])
        ->make();
    }
    $tableHeaders = $this->getTableHeader('schedule-message-list');

    return view('campaign::schedule_campaign_list', compact('title', 'tableHeaders', 'ajaxUrl'));
  }

  public function runningCampaignList(Request $request)
  {  
    //echo date('Y-m-d', strtotime("-2 days")); exit;

    $title = 'Running Campaign List';
    
    // Add filter parameters to the request data
    $requestData = $request->all();
    if ($request->has('from_date')) {
      $requestData['from_date'] = $request->from_date;
    }
    if ($request->has('to_date')) {
      $requestData['to_date'] = $request->to_date;
    }
    if ($request->has('campaign_id')) {
      $requestData['campaign_id'] = $request->campaign_id;
    }
    if ($request->has('user_id')) {
      $requestData['user_id'] = $request->user_id;
    }
    
    $datas = $this->campaignRepository->getRunningCampaignList($requestData);
    $ajaxUrl = route('running-campaign-list');

    // dd($datas);

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('username', fn($row) => $row->user ? $row->user->username : '')
        ->addColumn('senderId', fn($row) => $row->senderID ?? '-')
        ->addColumn('recipientList', function ($row) {
          if ($row['sms_type'] == 'sendSms') {
            return $row['recipient'] ?? '-';
          }
          if ($row['sms_type'] == 'groupSms') {
            return $row['smsGroup']['name'] ?? 'Multiple Group';
          }
          if ($row['sms_type'] == 'fileSms') {
            return $row['file'] ?? '-';
          }
          return "-";
        })
        ->addColumn('message', function ($row) {
          return Str::limit($row['message'], 50);
        })
        ->editColumn('status', function ($row) {
          return match ($row->status) {
            'Queue' => 'Submitted',
            'Sent' => 'Processed',
            'Sending' => 'Processing',
            default => $row->status,
          };
        })
        ->editColumn('date', function ($row) {
          return $row->date ? date("D jS \\of M Y h:i:s A", strtotime($row->date)) : null;
        })
        ->editColumn('scheduleDateTime', function ($row) {
          return $row->scheduleDateTime ? date("D jS \\of M Y h:i:s A", strtotime($row->scheduleDateTime)) : null;
        })
        ->editColumn('total_recipient', function ($row) {
          return number_format($row->total_recipient);
        })
        ->editColumn('error', function ($row) {
          if (!$row['error']) return 'Success';
          return $row['error'];
        })
        ->editColumn('campaign_id', function ($row) {
          return $row['campaign_id'];
        })
        ->addColumn('action', function ($row) {
          return '<button type="button" class="btn btn-sm btn-outline-primary campaign-action-btn" 
                    data-id="' . $row->campaign_id . '" 
                    title="View Details">
                    <i class="ri-eye-line"></i>
                  </button>';
        })
        ->rawColumns(['status', 'action'])
        ->make();
    }

    
    // Get distinct campaign IDs for filter dropdown
    $campaignQuery = Message::select('campaign_id')
      ->whereNotNull('campaign_id')
      ->distinct();
      
    if (Auth::user()->id_user_group != 1) {
      $userIds = User::where('created_by', Auth::id())->pluck('id')->toArray();
      $userIds[] = Auth::id();
      
      $campaignQuery->whereIn('user_id', $userIds);
    }
    
    $campaigns = $campaignQuery->pluck('campaign_id')->filter()->values();

    // Get users for filter dropdown
    $authId = Auth::id();
    
    if (Auth::user()->id_user_group == 1) {
        // Admin users can see all users
        $users = User::select('id', 'name')
            ->orderBy('name')
            ->get();
    } else {
        // Regular users can only see users they created plus themselves
        $userIds = User::where('created_by', $authId)
            ->pluck('id')
            ->toArray();

        // Always include the authenticated user's own ID
        $userIds[] = $authId;

        // Fetch the final set of users
        $users = User::whereIn('id', $userIds)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    $tableHeaders = $this->getTableHeader('running-message-list');
    return view('campaign::running_campaign_list', compact(
      'title', 'tableHeaders', 'ajaxUrl', 'campaigns', 'users'
    ));
  }

  public function archiveCampaignList()
  {
    $title = 'Archive Campaign List';
    $datas = $this->campaignRepository->getArchiveCampaignList();
    $ajaxUrl = route('archive-campaign-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('username', fn($row) => $row->user ? $row->user->username : '')
        ->addColumn('senderId', fn($row) => $row->senderID ?? '-')
        ->addColumn('recipientList', function ($row) {
          if ($row['sms_type'] == 'sendSms') {
            return $row['recipient'] ?? '-';
          }
          if ($row['sms_type'] == 'groupSms') {
            return $row['smsGroup']['name'] ?? 'Multiple Group';
          }
          if ($row['sms_type'] == 'fileSms') {
            return $row['file'] ?? '-';
          }
          return "-";
        })
        ->addColumn('message', function ($row) {
          return Str::limit($row['message'], 50);
        })
        ->editColumn('status', function ($row) {
          return match ($row->status) {
            'Queue' => 'Submitted',
            'Sent' => 'Processed',
            'Sending' => 'Processing',
            default => $row->status,
          };
        })
        ->editColumn('date', function ($row) {
          return $row->date ? date("D jS \\of M Y h:i:s A", strtotime($row->date)) : null;
        })
        //->editColumn('scheduleDateTime', function ($row) {
          //return $row->scheduleDateTime ? date("D jS \\of M Y h:i:s A", strtotime($row->scheduleDateTime)) : null;
        //})
        ->editColumn('total_recipient', function ($row) {
          return number_format($row->total_recipient);
        })
        ->editColumn('recipient', function ($row) {
          return Str::limit($row['recipient'], 40);
        })
        ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
       // ->addColumn('action', fn($row) => $this->editButton('campaigns.edit', $row->id))
        ->rawColumns(['status'])
        ->make();
    }

    $tableHeaders = $this->getTableHeader('archive-message-list');
    return view('campaign::archive_campaign_list', compact('title', 'tableHeaders', 'ajaxUrl'));
  }

  public function runningCampaignRetry($id)
  {
    $this->messageRepository->retryCampaign($id);
    return redirect()->back()->with('success', 'Campaign has been retried successfully');
  }


  public function show($campaignId)
  {
    $title = 'Campaign Details';
    $ajaxUrl = route('campaign.details', $campaignId);

    if (request()->ajax()) {
      $request = request();
      $query = DB::table('outbox')
        ->leftJoin('sentmessages', 'outbox.reference_id', '=', 'sentmessages.id')
        ->leftJoin('users', 'outbox.user_id', '=', 'users.id')
        ->where('sentmessages.campaign_id', $campaignId)
        ->select([
          'outbox.id',
          'outbox.mask as senderId',
          'users.username',
          'outbox.destmn as mobile',
          'outbox.message',
          'outbox.write_time',
          'outbox.smscount',
          'outbox.sms_cost',
          'sentmessages.source',
          'sentmessages.campaign_id',
          'outbox.retry_count',
          'outbox.error_code',
          'outbox.error_message',
          'outbox.dlr_status',
          'outbox.operator_prefix',
        ]);

      // Filters similar to last2DaysSmsList
      if ($request->filled('message')) {
        $query->where('outbox.message', 'like', "%{$request->message}%");
      }
      if ($request->filled('mobile')) {
        $query->where('outbox.destmn', 'like', "%{$request->mobile}%");
      }
      if ($request->filled('source')) {
        $query->where('sentmessages.source', 'like', "%{$request->source}%");
      }
      if ($request->filled('user_id')) {
        $query->where('outbox.user_id', $request->user_id);
      }
      if ($request->filled('from_date')) {
        $query->where('outbox.write_time', '>=', $request->from_date);
      }
      if ($request->filled('to_date')) {
        $query->where('outbox.write_time', '<=', $request->to_date);
      }
      if ($request->filled('operator')) {
        $operator = $request->operator;
        if ($operator == 'gp') {
          $query->where(function ($q) {
            $q->where('outbox.operator_prefix', 'like', '%17%')
              ->orWhere('outbox.operator_prefix', 'like', '%13%');
          });
        } elseif ($operator == 'bl') {
          $query->where(function ($q) {
            $q->where('outbox.operator_prefix', 'like', '%19%')
              ->orWhere('outbox.operator_prefix', 'like', '%14%');
          });
        } elseif ($operator == 'rb') {
          $query->where(function ($q) {
            $q->where('outbox.operator_prefix', 'like', '%16%')
              ->orWhere('outbox.operator_prefix', 'like', '%18%');
          });
        } elseif ($operator == 'tt') {
          $query->where('outbox.operator_prefix', 'like', '%15%');
        }
      }
      if ($request->filled('senderId')) {
        $query->where('outbox.mask', $request->senderId);
      }

      // Apply user permissions
      if (Auth::user()->id_user_group != 1) {
        $query->where('outbox.user_id', Auth::user()->id);
      }

      $results = $query->orderBy('outbox.write_time', 'desc')->get();

      return DataTables::of($results)
        ->addIndexColumn()
        ->editColumn('senderId', fn($row) => $row->senderId ?? '-')
        ->editColumn('username', fn($row) => $row->username ?? '-')
        ->editColumn('mobile', fn($row) => $row->mobile ?? '-')
        ->editColumn('message', function ($row) {
          $message = $row->message ?? '';
          return $message;
        })
        ->editColumn('write_time', function ($row) {
          return $row->write_time ? date("D jS \\of M Y h:i:s A", strtotime($row->write_time)) : null;
        })
        ->editColumn('smscount', fn($row) => $row->smscount ?? 1)
        ->editColumn('rate', function ($row) {
          // If mask length is 13, nonmasking rate, else masking rate (if available)
          if (isset($row->senderId) && strlen($row->senderId) === 13) {
            // No user relation here, so just show 0.0000 or try to join if needed
            return number_format(0, 4);
          } else {
            return number_format(0, 4);
          }
        })
        ->editColumn('charge', fn($row) => number_format($row->sms_cost ?? 0, 4))
        ->editColumn('source', fn($row) => $row->source ?? 'API')
        ->editColumn('retry_count', fn($row) => $row->retry_count ?? 0)
        ->editColumn('error_code', fn($row) => $row->error_code ? $row->error_code : '-1')
        ->editColumn('error_message', fn($row) => $row->error_message ? $row->error_message : 'NULL')
        ->editColumn('dlr_status', fn($row) => $row->dlr_status ? $row->dlr_status : 'Message Submitted')
        ->rawColumns(['message'])
        ->make(true);
    }

    // For filter dropdowns
    $operators = ['gp' => 'Grameenphone', 'bl' => 'Banglalink', 'rb' => 'Robi/Airtel', 'tt' => 'Teletalk'];
    $users = User::select('id', 'name')->orderBy('name')->get();
    $senderIds = DB::table('outbox')
      ->where('sentmessages.campaign_id', $campaignId)
      ->leftJoin('sentmessages', 'outbox.reference_id', '=', 'sentmessages.id')
      ->distinct()
      ->pluck('mask');

    return view('campaign::campaign_details', compact('title', 'ajaxUrl', 'campaignId', 'operators', 'users', 'senderIds'));
  }
}

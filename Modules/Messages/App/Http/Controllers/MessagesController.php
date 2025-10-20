<?php

namespace Modules\Messages\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Trait\ActionButtonTrait;
use Illuminate\Support\Facades\Auth;
use Modules\Messages\App\Http\Requests\CreateDynamicMessageRequest;
use Modules\Messages\App\Repositories\MessageRepositoryInterface;
use Modules\Messages\App\Repositories\TemplateRepositoryInterface;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Phonebook\App\Repositories\GroupRepositoryInterface;
use Modules\Smsconfig\App\Repositories\MaskRepositoryInterface;
use Modules\Smsconfig\App\Repositories\SenderIdRepositoryInterface;
use Modules\Users\App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Messages\App\Trait\DataTableTrait;

class MessagesController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;
  use SmsCountTrait;
  protected MessageRepositoryInterface $messageRepository;
  private $user;

  public function __construct(
    MessageRepositoryInterface $messageRepository,
    SenderIdRepositoryInterface $senderIdRepository,
    MaskRepositoryInterface $maskRepository,
    TemplateRepositoryInterface $templateRepository,
    GroupRepositoryInterface $groupRepository
  ) {
    $this->user = Auth::user();
    $this->messageRepository = $messageRepository;
    $this->senderIdRepository = $senderIdRepository;
    $this->maskRepository = $maskRepository;
    $this->templateRepository = $templateRepository;
    $this->groupRepository = $groupRepository;
  }

  public function index()
  {
    $title = 'Messages Inbox List';
    $datas = $this->messageRepository->all();
    $ajaxUrl = route('inbox-list');
    //dd($datas);
    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
          ->editColumn('write_time', fn($row) => date("D jS \\of M Y h:i:s A", strtotime($row->created_at)))
          ->editColumn('sent_time', fn($row) => $row->sent_time ? date("D jS \\of M Y h:i:s A", strtotime($row->sent_time)) : '')
          ->addIndexColumn()
        //->rawColumns(['action'])
        ->make();
    }
    $tableHeaders = $this->getTableHeader('sms-inbox-list');

    return view('messages::message.index', compact('title', 'tableHeaders', 'ajaxUrl'));
  }

  public function storeRegularMessage(Request $request): RedirectResponse
  {
    if ($request->isScheduleMessage == '1') {
      $scheduleDateTime = \Carbon\Carbon::createFromFormat(
          'Y-m-d H:i',
          $request->scheduleDate . ' ' . $request->scheduleTime,
          'Asia/Dhaka' // Set BD timezone
      );
      if ($scheduleDateTime->isPast()) {
            return redirect()->route('messages.create')->with('error', 'Scheduled time must be in the future');
        }
    }

    $dndNumbers = [];
    if($request->dnd && $request->dnd == '1') {
      $dndNumbers = DB::table('dnds')->where('user_id', auth()->user()->id)->pluck('phone')->toArray();
    }

    $numbers = explode(',', $request->recipient_number); // split into array
    $numbers = array_map('trim', $numbers); // remove extra spaces

    // Normalize DND numbers (make sure they start with "88")
    $dndNumbers = array_map(function($num) {
        $num = preg_replace('/\s+/', '', $num); // remove spaces
        if (!str_starts_with($num, '88')) {
            $num = '88' . $num;
        }
        return $num;
    }, $dndNumbers);

    // Filter out numbers that match any in DND list (normalize before checking)
    $filteredNumbers = array_filter($numbers, function($num) use ($dndNumbers) {
        $normalized = str_starts_with($num, '88') ? $num : '88' . $num;
        return !in_array($normalized, $dndNumbers);
    });

    // Convert back to comma-separated string
    $request->merge(['recipient_number' => implode(',', $filteredNumbers)]);

    // dd($request->recipient_number);

    $totalPhoneNumber = count(explode(",", $request->recipient_number));

    if($request->masking_type == 'Masking'){
      $totalCost = $totalPhoneNumber * ($this->countSms($request->message_text)->count) ?? 0 * $this->user->smsRate->masking_rate ?? 0;
    }else{
      $totalCost = $totalPhoneNumber * ($this->countSms($request->message_text)->count) ?? 0 * $this->user->smsRate->nonmasking_rate ?? 0;
    }


    if(($totalCost > $this->user->available_balance)){
      return redirect()->route('messages.create')->with('error', 'Insufficient balance');
    }


    $request->merge(['totalPhoneNumber' => $totalPhoneNumber]);
    if ($this->messageRepository->saveRegularMessage($request->all())) {
      return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    } else {
      return redirect()->route('messages.create')->with('error', 'Failed to send message');
    }
  }

  public function storeGroupMessage(Request $request): RedirectResponse
  {
    if ($request->isScheduleMessage == '1') {
      $scheduleDateTime = \Carbon\Carbon::createFromFormat(
          'Y-m-d H:i',
          $request->scheduleDate . ' ' . $request->scheduleTime,
          'Asia/Dhaka' // Set BD timezone
      );
      if ($scheduleDateTime->isPast()) {
            return redirect()->route('messages.create')->with('error', 'Scheduled time must be in the future');
        }
    }
    $request->validate([
      'sender_id' => 'required'
    ]);

    $groupIds = $request->group_ids;

    $groupPhones = DB::table('contacts')
      ->whereIn('group_id', $groupIds)
      ->pluck('phone')
      ->toArray();

    $groupPhoneNo = implode(', ', $groupPhones);
    if($request->dnd && $request->dnd == '1') {
      $dndNumbers = DB::table('dnds')->where('user_id', auth()->user()->id)->pluck('phone')->toArray();
      // dd($groupPhoneNo);
      $numbers = explode(", ", $groupPhoneNo);
      $filteredNumbers = array_filter($numbers, function ($num) use ($dndNumbers) {
        return !in_array($num, $dndNumbers);
      });
      $groupPhoneNo = implode(", ", $filteredNumbers);
    }else{
      // merge into request
      $request->merge(['dnd' => '0']);
    }

    $request->merge(['recipient_number' => $groupPhoneNo]);
    $total_recipient = count($groupPhones);
    $request->merge(['total_recipient' => $total_recipient]);

    $user_id = Auth::user()->id;
    $currentBalance = User::where('id', $user_id)->first()->available_balance;

    $msmCount = $this->countSms($request->message_text);

    if($request->masking_type == 'Masking'){
      $balance = ($msmCount->count * $total_recipient) * intval($this->user->smsRate->masking_rate??0);
    }else{
      $balance = ($msmCount->count * $total_recipient) * intval($this->user->smsRate->nonmasking_rate??0);
    }

    if($balance > $currentBalance){
      return redirect()->route('messages.create')->with('error', 'Insufficient balance');
    }

    if ($this->messageRepository->saveGroupMessage($request->all())) {
      return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    } else {
      return redirect()->route('messages.create')->with('error', 'Failed to send message');
    }
  }

  public function create()
  {
    $title = 'Send New Message';
    $senderIds = $this->senderIdRepository->getSenderIds();
    $maskList = null;
    $templates = $this->templateRepository->getTemplates();
    $groups = $this->groupRepository->getGroups();
    $available_balance = User::where('id', auth()->id())->first()->available_balance;

    return view('messages::message.create', compact('title', 'senderIds', 'maskList', 'templates', 'groups', 'available_balance'));
  }

  public function storeFileMessage(Request $request)
  {
    if ($request->isScheduleMessage == '1') {
      $scheduleDateTime = \Carbon\Carbon::createFromFormat(
          'Y-m-d H:i',
          $request->scheduleDate . ' ' . $request->scheduleTime,
          'Asia/Dhaka' // Set BD timezone
      );
      if ($scheduleDateTime->isPast()) {
            return redirect()->route('messages.create')->with('error', 'Scheduled time must be in the future');
        }
    }
    $totalRecipients = $request->totalMobileNumbers ?? 0;
    $currentBalance = User::where('id', $this->user->id)->first()->available_balance;

    $smsCount = $this->countSms($request->message_text);

    if($request->masking_type == 'Masking'){
      $totalMessageCost = ($smsCount->count * $totalRecipients) * doubleval($this->user->smsRate->masking_rate ?? 0);
    }else{
      $totalMessageCost = ($smsCount->count * $totalRecipients) * doubleval($this->user->smsRate->nonmasking_rate ?? 0);
    }

    if ($totalMessageCost > $currentBalance) {
      return redirect()->route('messages.create')->with('error', 'Insufficient balance');
    }

    if ($this->messageRepository->createFileMessage($request)) {
      return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    } else {
      return redirect()->route('messages.create')->with('error', 'Failed to send message');
    }
  }

  public function storeDynamicMessageActual(Request $request)
  {
    if ($request->isScheduleMessage == '1') {
      $scheduleDateTime = \Carbon\Carbon::createFromFormat(
        'Y-m-d H:i',
        $request->scheduleDate . ' ' . $request->scheduleTime,
        'Asia/Dhaka' // Set BD timezone
      );
      if ($scheduleDateTime->isPast()) {
        return redirect()->route('messages.create')->with('error', 'Scheduled time must be in the future');
      }
    }
    $totalRecipients = $request->totalMobileNumbers ?? 0;
    $currentBalance = User::where('id', $this->user->id)->first()->available_balance;
    
    $smsCount = $this->countSms($request->message_text);
    
    if($request->masking_type == 'Masking'){
      $totalMessageCost = ($smsCount->count * $totalRecipients) * doubleval($this->user->smsRate->masking_rate ?? 0);
    }else{
      $totalMessageCost = ($smsCount->count * $totalRecipients) * doubleval($this->user->smsRate->nonmasking_rate ?? 0);
    }
    
    if ($totalMessageCost > $currentBalance) {
      return redirect()->route('messages.create')->with('error', 'Insufficient balance');
    }

    if ($this->messageRepository->createDynamicMessage($request)) {
      return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    } else {
      return redirect()->route('messages.create')->with('error', 'Failed to send message');
    }
  }

  public function show($id)
  {
    return view('messages::show');
  }

  public function createDynamicMessage()
  {
    $title = 'Send Dynamic Message';
    $senderIds = $this->senderIdRepository->getSenderIds($this->userId);
    $templates = $this->templateRepository->getTemplates($this->userId);
    $groups = $this->groupRepository->getGroups($this->userId);
    return view('messages::message.create-dynamic', compact('title', 'senderIds', 'templates', 'groups'));
  }

  public function storeDynamicMessage(CreateDynamicMessageRequest $request): RedirectResponse
  {
    $this->messageRepository->create($request->all());
    return redirect()->route('messages-inbox.index')->with('success', 'Message sent successfully');
  }
}

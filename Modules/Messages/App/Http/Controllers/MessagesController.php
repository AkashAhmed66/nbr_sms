<?php

namespace Modules\Messages\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Messages\App\Http\Requests\CreateDynamicMessageRequest;
use Modules\Messages\App\Repositories\MessageRepositoryInterface;
use Modules\Messages\App\Repositories\TemplateRepositoryInterface;
use Modules\Phonebook\App\Repositories\GroupRepositoryInterface;
use Modules\Smsconfig\App\Repositories\SenderIdRepositoryInterface;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MobileNumberImport;
use Illuminate\Support\Facades\Log;
use Modules\Messages\App\Trait\SmsCountTrait;
use Modules\Transactions\App\Models\UserWallet;
use Modules\Users\App\Models\User;

class MessagesController extends Controller
{
  protected MessageRepositoryInterface $messageRepository;
  private $user;
  use SmsCountTrait;

  public function __construct(
    MessageRepositoryInterface $messageRepository,
    SenderIdRepositoryInterface $senderIdRepository,
    TemplateRepositoryInterface $templateRepository,
    GroupRepositoryInterface $groupRepository
  ) {
    $this->user = Auth::user();
    $this->messageRepository = $messageRepository;
    $this->senderIdRepository = $senderIdRepository;
    $this->templateRepository = $templateRepository;
    $this->groupRepository = $groupRepository;
  }

  public function index()
  {
    $title = 'Messages Inbox List';
    $datas = $this->messageRepository->all();
    $ajaxUrl = route('messages-inbox.list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
        ->addColumn('action', fn($row) => $this->editButton('messages.edit', $row->id))
        ->rawColumns(['status', 'action'])
        ->make();
    }

    return view('messages::index', compact('title', $this->getTableHeader('messages-list'), 'ajaxUrl'));
  }

  /*public function storeRegularMessage(Request $request): RedirectResponse
  {

    $totalPhoneNumber = count(explode(",", rtrim($request->recipient_number, ',')));

    //$totalPhoneNumber = $request->totalPhoneNumber ?? ($request->number ? count(
      //explode(",", rtrim($request->number, ','))
    //) : 0);
    //$totalCost = $totalPhoneNumber * $request->totalMessageCount ?? 0 * $this->user->smsRate->nonmasking_rate ?? 0;

    if ($this->user->id_user_group == 3 && ($totalCost > $this->user->wallet->available_balance)) {
      return redirect()->route('messages.create')->with('error', 'Insufficient balance');
    }

    //if ($this->user->id_user_group == 4 && ($totalCost > $this->user->reseller->wallet->available_balance || $totalCost > $this->user->reseller->wallet->available_balance)) {
      //return redirect()->route('messages.create')->with('error', 'Insufficient balance');
    //}

    $user_id = Auth::user()->id;
    $currentBalance = User::where('id', $user_id)->first()->available_balance;

    $request->merge(['totalPhoneNumber' => $totalPhoneNumber]);
    $msmCount = $this->countSms($request->message_text);
    $balance = ($msmCount->count * $totalPhoneNumber) * $this->user->smsRate->nonmasking_rate;

    if($balance > $currentBalance){
      return redirect()->route('messages.create')->with('success', 'Insufficient balance');
    }

    if ($this->messageRepository->saveRegularMessage($request->all())) {

      $user = User::find(auth()->id());
      if ($user) {
          $user->available_balance -= $balance;
          $user->save();
      }

      return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    } else {
      return redirect()->route('messages.create')->with('error', 'Failed to send message');
    }
  }*/

  public function storeRegularMessage(Request $request): RedirectResponse
  {
    Log::info('Store Regular Message Request: ', ['request' => $request->all()]);
    $request->validate([
        'sender_id' => 'required',
        'recipient_number' => 'required',
    ]);

    // Continue with the rest of your logic
    $totalPhoneNumber = count(explode(",", rtrim($request->recipient_number, ',')));

    $user_id = Auth::user()->id;
    $currentBalance = User::where('id', $user_id)->first()->available_balance;

    $request->merge(['totalPhoneNumber' => $totalPhoneNumber]);
    $msmCount = $this->countSms($request->message_text);
    $balance = ($msmCount->count * $totalPhoneNumber) * $this->user->smsRate->nonmasking_rate;

    if($balance > $currentBalance){
        return redirect()->route('messages.create')->with('error', 'Insufficient balance');
    }
    $status = $this->messageRepository->saveRegularMessage($request->all());

    if ($status == true) {

        $user = User::find(auth()->id());
        if ($user) {
            $user->available_balance -= $balance;
            $user->save();
        }

        return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    } else {
        return redirect()->route('messages.create')->with('success', 'Failed to send message');
    }
  }


  public function storeGroupMessage(Request $request): RedirectResponse
  {

    $request->validate([
      'sender_id' => 'required'
    ]);

    $groupIds = $request->group_ids;

    $groupPhones = DB::table('contacts')
                    ->whereIn('group_id', $groupIds)
                    ->pluck('phone')
                    ->toArray();

    $groupPhoneNo = implode(', ', $groupPhones);

    $request->merge(['recipient_number' => $groupPhoneNo]);
    $total_recipient = count($groupPhones);
    $request->merge(['total_recipient' => $total_recipient]);

    $user_id = Auth::user()->id;
    $currentBalance = User::where('id', $user_id)->first()->available_balance;

    $msmCount = $this->countSms($request->message_text);
    $balance = ($msmCount->count * $total_recipient) * $this->user->smsRate->nonmasking_rate;
    if($balance > $currentBalance){
      return redirect()->route('messages.create')->with('success', 'Insufficient balance');
    }

    /*if ($this->messageRepository->saveGroupMessage($request->all())) {

        $user = User::find(auth()->id());
        if ($user) {
            $user->available_balance -= $balance;
            $user->save();
        }

        return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    } else {
        return redirect()->route('messages.create')->with('error', 'Failed to send message');
    }*/

    $status = $this->messageRepository->saveGroupMessage($request->all());

    /*if($status == 404){
      return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    }

    if($status == 500){
      return redirect()->route('messages.create')->with('success', 'Unknown error occurred');
    }*/
    if ($status == true) {

        $user = User::find(auth()->id());
        if ($user) {
            $user->available_balance -= $balance;
            $user->save();
        }

        return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    } else {
        return redirect()->route('messages.create')->with('success', 'Failed to send message');
    }
  }

  public function create()
  {
    $title = 'Send New Message';
    $senderIds = $this->senderIdRepository->getSenderIds();
    $templates = $this->templateRepository->getTemplates();
    $groups = $this->groupRepository->getGroups();

    $available_balance = User::where('id', auth()->id())->first()->available_balance;
    return view('messages::message.create', compact('title', 'senderIds', 'templates', 'groups', 'available_balance'));
  }

  public function storeFileMessage(Request $request)
  {

    $request->validate([
      'sender_id' => 'required'
    ]);

    $smsStatus = $this->messageRepository->createFileMessage($request->all());

    /*if($smsStatus == false){
      return redirect()->route('messages.create')->with('success', 'Insufficient balance');
    }

    return redirect()->route('messages.create')->with('success', 'Message sent successfully');*/
    $status = $this->messageRepository->saveGroupMessage($request->all());

    /*if($status == 404){
      return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    }

    if($status == 500){
      return redirect()->route('messages.create')->with('success', 'Unknown error occurred');
    }*/
    if ($status == true) {

        $user = User::find(auth()->id());
        if ($user) {
            $user->available_balance -= $balance;
            $user->save();
        }

        return redirect()->route('messages.create')->with('success', 'Message sent successfully');
    } else {
        return redirect()->route('messages.create')->with('success', 'Failed to send message');
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

  public function getTotalMobileNumbers(Request $request)
  {

	$groupIds = $request->input('group_ids');

	if ($groupIds) {

		$totalMobileNumbers = DB::table('contacts')
                                ->whereIn('group_id', $groupIds)
                                ->whereNotNull('phone')
                                ->count();
	} else {
		$totalMobileNumbers = 0;
	}

	return response()->json(['total_mobile_numbers' => $totalMobileNumbers]);
  }
}

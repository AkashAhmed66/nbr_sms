<?php

namespace Modules\Smsconfig\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Smsconfig\App\Http\Requests\CreateSenderIdRequest;
use Modules\Smsconfig\App\Http\Requests\UpdateSenderIdRequest;
use Modules\Smsconfig\App\Repositories\SenderIdRepositoryInterface;
use Modules\Smsconfig\App\Trait\DataTableTrait;
use Modules\Users\App\Repositories\UserRepositoryInterface;
use Yajra\DataTables\DataTables;
use Modules\Smsconfig\App\Models\SenderId;

class SenderIdController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;
    public function __construct(SenderIdRepositoryInterface $senderIdRepository, UserRepositoryInterface $userRepository)
    {
        $this->senderIdRepository = $senderIdRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $title = 'Sender ID List';
        $datas = $this->senderIdRepository->all();
        $ajaxUrl = route('sender-id-list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('username', fn($row) => isset($row->user) ? $row->user['name'].'('.$row->user['username'].')' :(($row->reseller)? ucfirst($row->reseller['name'].'('.$row->reseller['username'].')') : ""))
                //->addColumn('action', fn($row) => $this->editButton('sender-id-edit', $row->id) . ' ' . $this->deleteButton('sender-id-delete', $row->id))
                ->addColumn('action', function($row) {

					if (Auth::user()->id_user_group == 1) {
						return $this->editButton('sender-id-edit', $row->id) . ' ' . $this->deleteButton('sender-id-delete', $row->id);
					}
					return 'Not Applicable';
				})
				->rawColumns(['action'])
                ->make();
        }
        $tableHeaders = $this->getTableHeader('sender-id');
        $userInfo = Auth::user();
        $userLists = $this->userRepository->allUser();
        return view('smsconfig::sender_id.index', compact('title','tableHeaders','userLists' , 'ajaxUrl','userInfo'));
    }

    public function create()
    {
        $title = 'Assign Sender ID';
        $users = $this->userRepository->getUserByGroupId($this->userGroupId);
        $senderIds = $this->senderIdRepository->getSenderIdByUserId($this->userId);

        return view('smsconfig::senderid.create', compact('title', 'users', 'senderIds'));
    }

    public function store(CreateSenderIdRequest $request)
    {
        $this->senderIdRepository->create($request->validated());
        return response()->json(['status' => 'created', 'message' => 'Sender ID added successfully']);
    }

    public function edit($id)
    {
        $data = $this->senderIdRepository->find($id);
        echo $data;
    }

    public function update(UpdateSenderIdRequest $request, $id)
    {
        $validatedData = $request->validated();

        $SenderId = SenderId::find($id);

        if (!$SenderId) {

            return response()->json(['status' => 'updated', 'message' => 'Sender ID not found']);
        }

        // Update the operator with validated data
        $SenderId->update($validatedData);

        return response()->json(['status' => 'updated', 'message' => 'Sender ID updated successfully']);
    }

    public function destroy($id)
    {
        $this->senderIdRepository->delete($id);
        return response()->json(['status' => 'updated', 'message' => 'Sender ID deleted successfully']);
    }

    public function apiInfo(){
        $title = 'API Information';
        $APIEKY = Auth::user()->APIKEY;
        return view('smsconfig::api_information.apinfo', compact('title', 'APIEKY'));
    }
}

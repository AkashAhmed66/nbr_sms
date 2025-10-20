<?php

namespace Modules\Smsconfig\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Smsconfig\App\Http\Requests\CreateMaskRequest;
use Modules\Smsconfig\App\Http\Requests\UpdateMaskRequest;
use Modules\Smsconfig\App\Repositories\MaskRepositoryInterface;
use Modules\Smsconfig\App\Trait\DataTableTrait;
use Modules\Users\App\Repositories\UserRepositoryInterface;
use Yajra\DataTables\DataTables;
use Modules\Smsconfig\App\Models\Mask;

class MaskController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;

  public function __construct(MaskRepositoryInterface $maskRepository, UserRepositoryInterface $userRepository)
  {
    $this->maskRepository = $maskRepository;
    $this->userRepository = $userRepository;
  }

  public function index()
  {
    $title = 'Mask List';
    $datas = $this->maskRepository->all();
    $ajaxUrl = route('mask-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('username', fn($row) => isset($row->user) ? $row->user['name'] . '(' . $row->user['username'] . ')' : "")
        ->addColumn('action', function ($row) {
          if (Auth::user()->id_user_group == 1) {
            return $this->editButton('mask-edit', $row->id) . ' ' . $this->deleteButton('mask-delete', $row->id);
          }
          return 'Not Applicable';
        })
        ->rawColumns(['action'])
        ->make();
    }
    $tableHeaders = $this->getTableHeader('mask');
    $userInfo = Auth::user();
    return view('smsconfig::mask.index', compact('title', 'tableHeaders', 'ajaxUrl', 'userInfo'));
  }

  public function create()
  {
    $title = 'Create Mask';
    $users = $this->userRepository->getUserByGroupId($this->userGroupId);
    $maskList = $this->maskRepository->getMaskByUserId($this->userId);

    return view('smsconfig::mask.create', compact('title', 'users', 'maskList'));
  }

  public function store(CreateMaskRequest $request)
  {
    try {
      $this->maskRepository->create($request->validated());
      return response()->json(['status' => 'created', 'message' => 'Mask added successfully']);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to add mask: ' . $e->getMessage()
      ], 500);
    }
  }

  public function edit($id)
  {
    $data = $this->maskRepository->find($id);
    echo $data;
  }

  public function update(UpdateMaskRequest $request, $id)
  {
    $validatedData = $request->validated();

    $mask = Mask::find($id);

    if (!$mask) {

      return response()->json(['status' => 'updated', 'message' => 'Mask not found']);
    }

    // Update the operator with validated data
    $mask->update($validatedData);

    return response()->json(['status' => 'updated', 'message' => 'Mask updated successfully']);
  }

  public function destroy($id)
  {
    $this->maskRepository->delete($id);
    return response()->json(['status' => 'updated', 'message' => 'Mask deleted successfully']);
  }
}

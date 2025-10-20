<?php

namespace Modules\Users\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Users\App\Http\Requests\CreateUserGroupRequest;
use Modules\Users\App\Http\Requests\UpdateUserGroupRequest;
use Modules\Users\App\Repositories\UserGroupRepositoryInterface;
use Modules\Users\App\Trait\DataTableTrait;
use Yajra\DataTables\DataTables;
use Modules\Users\App\Models\UserGroup;

class UserGroupController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;
  protected $userGroupRepository;

    public function __construct(UserGroupRepositoryInterface $userGroupRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
    }

    public function index(Request $request)
    {
        $title = 'User Group List';
        $datas = $this->userGroupRepository->all($request->all());
        $ajaxUrl = route('user-group-list');
        $userGroup = Auth::user()->id_user_group;

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
              ->addColumn('action', function ($row) use ($userGroup) {
                if ($userGroup == 1) {
                  return $this->editButton('user-group-edit', $row->id) .
                    $this->deleteButton('user-group-delete', $row->id);
                }
                return ''; // No buttons for non-admin
              })                ->rawColumns(['status', 'action'])
                ->make();
        }

        $tableHeaders = $this->getTableHeader('user-group-list');

        return view('users::group.index', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    public function create()
    {
      $title = 'Create User Group';
      return view('users::group.create', compact('title'));
    }

    public function store(CreateUserGroupRequest $request)
    {
        $this->userGroupRepository->create($request->all());
        return response()->json(['status' => 'addedd', 'message' => 'User Group addedd successfully']);
    }

    public function edit($id)
    {

        $data = $this->userGroupRepository->find($id);
        echo $data;
    }

    public function update(UpdateUserGroupRequest $request, $id)
    {
        $validatedData = $request->validated();

        $userGroup = UserGroup::find($id);

        if (!$userGroup) {
            return response()->json(['status' => 'error', 'message' => 'User Group not found']);
        }

        // Update the operator with validated data
        $userGroup->update($validatedData);

        return response()->json(['status' => 'updated', 'message' => 'User Group updated successfully']);
    }

    public function destroy($id)
    {
        $this->userGroupRepository->delete($id);
        return response()->json(['status' => 'deleted', 'message' => 'User Group deleted successfully']);
    }
}

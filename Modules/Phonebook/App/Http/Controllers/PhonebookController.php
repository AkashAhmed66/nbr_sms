<?php

namespace Modules\Phonebook\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Modules\Phonebook\App\Http\Requests\CreatePhonebookRequest;
use Modules\Phonebook\App\Http\Requests\UpdatePhonebookRequest;
use Modules\Phonebook\App\Repositories\GroupRepositoryInterface;
use Modules\Users\App\Repositories\UserGroupRepositoryInterface;
use Modules\Phonebook\App\Repositories\PhoneBookRepositoryInterface;
use Modules\Phonebook\App\Trait\DataTableTrait;
use Yajra\DataTables\Facades\DataTables;
use Modules\Phonebook\App\Models\Phonebook;
use Modules\Phonebook\App\Models\Group;

class PhonebookController extends Controller
{
    use DataTableTrait;
    use ActionButtonTrait;
    protected PhoneBookRepositoryInterface $phoneBookRepository;

    public function __construct(PhoneBookRepositoryInterface $phoneBookRepository, GroupRepositoryInterface $groupRepository, UserGroupRepositoryInterface $userGroupRepository)
    {
        $this->phoneBookRepository = $phoneBookRepository;
        $this->groupRepository = $groupRepository;
        $this->userGroupRepository = $userGroupRepository;
    }

    public function index()
    {
        $title = 'Phonebook List';
        $ajaxUrl = route('contacts-list');

        if ($this->ajaxDatatable()) {
            $filters = request()->only(['name', 'phone']);
            $query = $this->phoneBookRepository->query($filters); // ✅ use query() for DataTables

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('group', fn($row) => $row->group?->name ?? '-')
                ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
                ->addColumn(
                    'action',
                    fn($row) =>
                    $this->editButton('contacts-edit', $row->id) . ' ' .
                    $this->deleteButton('contacts-delete', $row->id)
                )
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $tableHeaders = $this->getTableHeader('phonebook-list');
        $userGroups = $this->groupRepository->allGroups();

        return view('phonebook::phonebook.index', compact('title', 'tableHeaders', 'ajaxUrl', 'userGroups'));
    }

    public function store(CreatePhonebookRequest $request)
    {
        $this->phoneBookRepository->create($request->validated());
        return response()->json(['status' => 'added', 'message' => 'Phonebook added successfully']);
    }

    public function edit($id)
    {

        $phonebook = $this->phoneBookRepository->find($id);
        echo $phonebook;
    }

    public function update(UpdatePhonebookRequest $request, $id)
    {
        $validatedData = $request->validated();

        $phonebook = Phonebook::find($id);

        if (!$phonebook) {

            return response()->json(['status' => 'error', 'message' => 'Phonebook not found']);
        }

        $phonebook->update($validatedData);
        return response()->json(['status' => 'updated', 'message' => 'Phonebook updated successfully']);
    }

    public function destroy($id)
    {
        $this->phoneBookRepository->delete($id);
        return response()->json(['status' => 'deleted', 'message' => 'Phonebook deleted successfully']);
    }
}

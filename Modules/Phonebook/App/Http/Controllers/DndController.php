<?php

namespace Modules\Phonebook\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Phonebook\App\Http\Requests\CreateDndRequest;
use Modules\Phonebook\App\Http\Requests\UpdateDndRequest;
use Modules\Phonebook\App\Repositories\DndRepositoryInterface;
use Modules\Phonebook\App\Trait\DataTableTrait;
use Yajra\DataTables\DataTables;
use Modules\Phonebook\App\Models\Dnd;

class DndController extends Controller
{
    use DataTableTrait;
    use ActionButtonTrait;
    protected DndRepositoryInterface $dndRepository;

    public function __construct(DndRepositoryInterface $dndRepository)
    {
        $this->dndRepository = $dndRepository;
    }

    public function index()
    {
      $title = 'DND List';
      $datas = $this->dndRepository->all();
      $ajaxUrl = route('dnd-list');
        
      if ($this->ajaxDatatable()) {
        return DataTables::of($datas)
          ->addIndexColumn()
          ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
          ->addColumn('action', fn($row) => $this->editButton('dnd-edit', $row->id) . ' ' . $this->deleteButton('dnd-delete', $row->id))
          ->rawColumns(['status','action'])
          ->make();
      }

      $tableHeaders = $this->getTableHeader('dnd-list');

      
      return view('phonebook::dnd.index', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    public function store(CreateDndRequest $request)
    {
        $this->dndRepository->create($request->validated());
        return response()->json(['status' => 'added', 'message' => 'DND added successfully']);
    }

    public function edit($id)
    {
        $dnd = $this->dndRepository->find($id);
        echo $dnd;
    }

    public function update(UpdateDndRequest $request, $id)
    {
        $validatedData = $request->validated();

        $dnd = Dnd::find($id);
  
        if (!$dnd) {
            return redirect()->route('dnd-list')->with('error', 'DND not found');
        }

        $dnd->update($validatedData);
        return response()->json(['status' => 'updated', 'message' => 'DND updated successfully']);
    }

    public function destroy($id)
    {
        $this->dndRepository->delete($id);
        return response()->json(['status' => 'delected', 'message' => 'DND delected successfully']);
    }
}

<?php

namespace Modules\Smsconfig\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Modules\Smsconfig\App\Http\Requests\CreateBlackListedKeywordRequest;
use Modules\Smsconfig\App\Http\Requests\UpdateBlackListedKeywordRequest;
use Modules\Smsconfig\App\Repositories\BlackListedKeywordRepositoryInterface;
use Modules\Smsconfig\App\Trait\DataTableTrait;
use Yajra\DataTables\DataTables;
use Modules\Smsconfig\App\Models\BlacklistedKeyword;

class BlackListedKeywordController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;

  protected BlackListedKeywordRepositoryInterface $blackListedKeywordRepository;

  public function __construct(BlackListedKeywordRepositoryInterface $blackListedKeywordRepository)
  {
    $this->blackListedKeywordRepository = $blackListedKeywordRepository;
  }

  public function index()
  {
    $title = 'Black Listed Keyword List';
    $datas = $this->blackListedKeywordRepository->all();
    $ajaxUrl = route('keyword-list');
    $tableHeaders = $this->getTableHeader('black-listed-keyword');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
        ->addColumn(
          'action',
          fn($row) => $this->viewButton('keyword-list', $row->id) .
            $this->editButton('keyword-edit', $row->id).
            $this->deleteButton('keyword-delete', $row->id)
        )
        ->editColumn('keywords', fn($row) => Str::words($row->keywords, '10'))
        ->editColumn('user', fn($row) => $row->user->name)
        ->editColumn('userType', fn($row) => $row->user->userType->title)
        ->rawColumns(['status', 'action'])
        ->make();
    }

    return view('smsconfig::black_listed_keyword.index', compact('title', 'tableHeaders', 'ajaxUrl'));
  }

  public function store(CreateBlackListedKeywordRequest $request)
  {
    $this->blackListedKeywordRepository->create($request->validated());
    
    return response()->json(['status' => 'updated', 'message' => 'Black Listed Keyword added successfully']);
  }

  public function create()
  {
    $title = 'Black Listed Keyword Add';
    return view('smsconfig::black_listed_keyword.create', compact('title'));
  }

  public function show($id)
  {
    $title = 'Black Listed Keyword View';
    $data = $this->blackListedKeywordRepository->find($id);
    return view('smsconfig::black_listed_keyword.show', compact('title', 'data'));
  }

  public function edit($id)
  {
    $record = $this->blackListedKeywordRepository->find($id);
    echo $record;
  }

  public function update(UpdateBlackListedKeywordRequest $request, $id)
  {

    $validatedData = $request->validated();

    $keyword = BlacklistedKeyword::find($id);

    if (!$keyword) {
        
        return response()->json(['status' => 'updated', 'message' => 'Black Listed Keyword not found']);
    }

    // Update the operator with validated data
    $keyword->update($validatedData);
    return response()->json(['status' => 'updated', 'message' => 'Black Listed Keyword updated successfully']);
  }

  public function destroy($id)
  {
    $this->blackListedKeywordRepository->delete($id);
    return response()->json(['status' => 'updated', 'message' => 'Black Listed Keyword deleted successfully']);
  }
}

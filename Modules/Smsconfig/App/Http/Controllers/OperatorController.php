<?php

namespace Modules\Smsconfig\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Smsconfig\App\Http\Requests\CreateOperatorRequest;
use Modules\Smsconfig\App\Http\Requests\UpdateOperatorRequest;
use Modules\Smsconfig\App\Repositories\CountryRepositoryInterface;
use Modules\Smsconfig\App\Repositories\OperatorRepositoryInterface;
use Modules\Smsconfig\App\Trait\DataTableTrait;
use Yajra\DataTables\DataTables;
use DB;
use Modules\Smsconfig\App\Models\Operator;

class OperatorController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;
  protected OperatorRepositoryInterface $operatorRepository;

  public function __construct(OperatorRepositoryInterface $operatorRepository, CountryRepositoryInterface $countryRepository)
  {
    $this->operatorRepository = $operatorRepository;
    $this->countryRepository = $countryRepository;
  }

  public function index(Request $request)
  {
    $title = 'Operator List';
    $datas = $this->operatorRepository->all();
    $ajaxUrl = route('operator-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
        //->addColumn('action',fn($row) => $this->editButton('operator.edit', $row->id))
        ->addColumn('action', fn($row) => $this->editButton('operator.edit', $row->id) . ' ' . $this->deleteButton('operator-delete', $row->id))
        ->rawColumns(['status', 'action'])
        ->make();
    }
    $tableHeaders = $this->getTableHeader('operator');
    $countries = $this->countryRepository->all();
    return view('smsconfig::operator.index', compact('title', 'tableHeaders', 'ajaxUrl', 'countries'));
  }

  public function create()
  {
    $title = 'Operator Add';
    $countries = $this->countryRepository::all();
    return view('smsconfig.operator::create', compact('title', 'countries'));
  }

  public function store(CreateOperatorRequest $request)
  {
    $this->operatorRepository->create($request->validated());
    return response()->json(['status' => 'updated', 'message' => 'Operator added successfully']);
  }

  public function edit($id)
  {
    $title = 'Operator Edit';
    $operator = $this->operatorRepository->find($id);
    echo $operator;
  }

  public function update(UpdateOperatorRequest $request, $id)
  {

    $validatedData = $request->validated();

    $operator = Operator::find($id);

    if (!$operator) {
        return response()->json(['status' => 'updated', 'message' => 'Operator not found']);
    }

    $operator->update($validatedData);
    return response()->json(['status' => 'updated', 'message' => 'Operator updated successfully']);
  }

  public function destroy($id)
  {
    $this->operatorRepository->delete($id);
    
    return response()->json(['status' => 'updated', 'message' => 'Operator deleted successfully']);
  }

  public function changeStatus(Request $request, $id)
  {
    $this->operatorRepository->changeStatus($request->status, $id);
    return redirect()->route('operator.index')->with('success', 'Operator status changed successfully');
  }
  
  public function getCountries()
  {
	$countries = "1";
	//DB::table('countries')->get();
	return response()->json($countries);
  }
}

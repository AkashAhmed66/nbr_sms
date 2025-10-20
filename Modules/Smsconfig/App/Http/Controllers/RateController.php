<?php

namespace Modules\Smsconfig\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Smsconfig\App\Http\Requests\CreateRateRequest;
use Modules\Smsconfig\App\Http\Requests\UpdateRateRequest;
use Modules\Smsconfig\App\Repositories\RateRepositoryInterface;
use Modules\Smsconfig\App\Trait\DataTableTrait;
use Yajra\DataTables\DataTables;

class RateController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;
    protected RateRepositoryInterface $rateRepository;

    public function __construct(RateRepositoryInterface $rateRepository)
    {
        $this->rateRepository = $rateRepository;
    }

    public function index()
    {
        $title = 'Rate List';
        $datas = $this->rateRepository->all();
        $ajaxUrl = route('rate-list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                
					if (Auth::user()->id_user_group == 1) {
						return $this->editButton('rate-edit', $row->id) . ' ' . $this->deleteButton('rate-delete', $row->id);
					}elseif(Auth::user()->id_user_group == 2 && $row->created_by == Auth::user()->id) {
                        return $this->editButton('rate-edit', $row->id) . ' ' . $this->deleteButton('rate-delete', $row->id);
                    }
					return 'Not Applicable';
				})
                ->editColumn('masking_rate', fn($row) => number_format($row->masking_rate, 3))
                ->editColumn('nonmasking_rate', fn($row) => number_format($row->nonmasking_rate, 3))
                ->rawColumns(['action'])
                ->make();
        }
        $tableHeaders = $this->getTableHeader('rate');
        return view('smsconfig::rate.index', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    public function create()
    {
        $title = 'Rate Add';
        return view('smsconfig::rate.create', compact('title'));
    }

    public function store(CreateRateRequest $request)
    {
        $this->rateRepository->create($request->validated());
      
        return response()->json(['status' => 'updated', 'message' => 'Rate added successfully']);
    }

    public function edit($id)
    {
        $title = 'Rate Edit';
        $data = $this->rateRepository->find($id);
        echo $data;
        
    }

    public function update(UpdateRateRequest $request, $id)
    {
        $this->rateRepository->update($request->validated(), $id);
        return response()->json(['status' => 'updated', 'message' => 'Rate updated successfully']);
    }

    public function destroy($id)
    {
        $this->rateRepository->delete($id);
        return response()->json(['status' => 'updated', 'message' => 'Rate deleted successfully']);
    }
}

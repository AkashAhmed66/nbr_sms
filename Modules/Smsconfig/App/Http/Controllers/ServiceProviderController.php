<?php

namespace Modules\Smsconfig\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Modules\Smsconfig\App\Http\Requests\CreateServiceProviderRequest;
use Modules\Smsconfig\App\Http\Requests\UpdateServiceProviderRequest;
use Modules\Smsconfig\App\Repositories\OperatorRepositoryInterface;
use Modules\Smsconfig\App\Repositories\ServiceProviderRepositoryInterface;
use Modules\Smsconfig\App\Trait\DataTableTrait;
use Yajra\DataTables\DataTables;
use Modules\Smsconfig\App\Models\ServiceProvider;

class ServiceProviderController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;
    protected ServiceProviderRepositoryInterface $serviceProviderRepository;
    public function __construct(ServiceProviderRepositoryInterface $serviceProviderRepository, OperatorRepositoryInterface $operatorRepository)
    {
        $this->serviceProviderRepository = $serviceProviderRepository;
        $this->operatorRepository = $operatorRepository;
    }

    public function index()
    {
        $title = 'Service Provider List';
        $datas = $this->serviceProviderRepository->all();
        $ajaxUrl = route('service-provider-list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
                ->addColumn('action', fn($row) => $this->editButton('service-provider-edit', $row->id) . ' ' . $this->deleteButton('service-provider-delete', $row->id))
                ->rawColumns(['status','action'])
                ->make();
        }

       $tableHeaders = $this->getTableHeader('service-provider');
        return view('smsconfig::service_provider.index', compact('title','tableHeaders' , 'ajaxUrl'));
    }

    public function create()
    {
        $title = 'Service Provider Add';
        $operators = $this->operatorRepository->all();
        return view('smsconfig::serviceprovider.create', compact('title', 'operators'));
    }

    public function store(CreateServiceProviderRequest $request)
    {
        $this->serviceProviderRepository->create($request->validated());
        return response()->json(['status' => 'updated', 'message' => 'Service Provider added successfully']);
    }

    public function edit($id)
    {
    
        $data = $this->serviceProviderRepository->find($id);
        echo $data;
    }

    public function update(UpdateServiceProviderRequest $request, $id)
    {
        $validatedData = $request->validated();

        $serviceProvider = ServiceProvider::find($id);

        if (!$serviceProvider) {
            return response()->json(['status' => 'updated', 'message' => 'Service Provider not found']);
        }

        // Update the operator with validated data
        $serviceProvider->update($validatedData);
        return response()->json(['status' => 'updated', 'message' => 'Service Provider updated successfully']);
    }

    public function destroy($id)
    {
        $this->serviceProviderRepository->delete($id);
        return response()->json(['status' => 'updated', 'message' => 'Service Provider deleted successfully']);
    }
}

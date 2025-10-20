<?php

namespace Modules\Smsconfig\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Smsconfig\App\Http\Requests\CreateRateRequest;
use Modules\Smsconfig\App\Http\Requests\CreateRouteRequest;
use Modules\Smsconfig\App\Http\Requests\UpdateRouteRequest;
use Modules\Smsconfig\App\Repositories\OperatorRepositoryInterface;
use Modules\Smsconfig\App\Repositories\RouteRepositoryInterface;
use Modules\Smsconfig\App\Repositories\ServiceProviderRepositoryInterface;
use Modules\Smsconfig\App\Trait\DataTableTrait;
use Modules\Users\App\Repositories\UserRepositoryInterface;
use Yajra\DataTables\DataTables;
use Modules\Smsconfig\App\Models\Route;


class RouteController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;
  protected RouteRepositoryInterface $routeRepository;

  public function __construct(RouteRepositoryInterface $routeRepository, ServiceProviderRepositoryInterface $serviceProviderRepository)
  {
    $this->routeRepository = $routeRepository;
    $this->serviceProviderRepository = $serviceProviderRepository;
   
  }

  public function index()
  {
    $title = 'Route List';
    $datas = $this->routeRepository->all(request()->all());
    $ajaxUrl = route('route-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
        ->addColumn('action', fn($row) => $this->editButton('route-edit', $row->id) . ' ' . $this->deleteButton('route-delete', $row->id))
        //->addColumn('username', fn($row) => ($row->user)?$row->user->name:'-')
        //->addColumn('operator_prefix', fn($row) => is_array($row->operator_prefix)? implode(",", $row->operator_prefix) : '')
        ->addColumn('operator_prefix', fn($row) => $row->operator_prefix)
        //->addColumn('provider_name', fn($row) => $row->channel->name)
        ->addColumn('mask', fn($row) => ($row->has_mask == 2)? 'ALL' : (($row->has_mask == 1)? 'YES':'NO'))
        ->rawColumns(['status','action'])
        ->make();
    }

    $tableHeaders = $this->getTableHeader('route');
    $serviceProviders = $this->serviceProviderRepository->all();
    return view('smsconfig::route.index', compact('title', 'tableHeaders', 'ajaxUrl', 'serviceProviders'));
  }

  public function create()
  {
    $title = 'Route Add';
    return view('smsconfig.route::create', compact('title'));
  }

  public function store(CreateRouteRequest $request)
  {
    $this->routeRepository->create($request->validated());

    return response()->json(['status' => 'updated', 'message' => 'Route added successfully']);
  }

  public function edit($id)
  {
    $data = $this->routeRepository->find($id);
    echo $data;
  }

  public function update(UpdateRouteRequest $request, $id)
  {
    $validatedData = $request->validated();

        $route = Route::find($id);

        if (!$route) {
            //return redirect()->route('country-list')->with('error', 'Operator not found');
            return response()->json(['message' => 'Route not found'], 404);
        }
        $route->update($validatedData);
        return response()->json(['status' => 'updated', 'message' => 'Route updated successfully']);

  }

  public function destroy($id)
  {
    $this->routeRepository->delete($id);
    return response()->json(['status' => 'updated', 'message' => 'Route deleted successfully']);
  }
}

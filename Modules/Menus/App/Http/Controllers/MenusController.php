<?php

namespace Modules\Menus\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\MenuRepositoryInterface;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Menus\App\Http\Requests\CreateMenuRequest;
use Modules\Menus\App\Models\Menu;
use Modules\Users\App\Models\UserGroup;
use Yajra\DataTables\DataTables;

class MenusController extends Controller
{
  use ActionButtonTrait;

  protected MenuRepositoryInterface $menuRepository;

  public function __construct(MenuRepositoryInterface $menuRepository)
  {
    $this->menuRepository = $menuRepository;
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $title = 'Menu List';
    $filters = $request->only(['title', 'route_name', 'active_route', 'order_no', 'status']);
    $datas = $this->menuRepository->all($filters);
    $tableHeaders = [
      "id" => "#",
      'title' => "Menu Title",
      "route_name" => "Route Name",
      "active_route" => "Active Route",
      "order_no" => "Order No",
      "status" => "Status",
      'action' => 'Manage'
    ];
    $ajaxUrl = route('menu.list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
        ->addColumn(
          'action',
          fn($row) => $this->viewButton('menu.view', $row->id) . $this->editButton('menu.edit', $row->id)
        )
        ->rawColumns(['status', 'action'])
        ->make(true);
    }

    return view('menus::index', compact('title', 'tableHeaders', 'ajaxUrl'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $title = 'Add Menu';
    $menus = Menu::where('parent_id', 0)->get();
    $user_groups = UserGroup::all();
    $activeRoutes = routeDetails()['activeRoutes'];

    return view('menus::create', compact('title', 'menus', 'user_groups', 'activeRoutes'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CreateMenuRequest $request): RedirectResponse
  {
    //
  }

  /**
   * Show the specified resource.
   */
  public function show($id)
  {
    return view('menus::show');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    return view('menus::edit');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id): RedirectResponse
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    //
  }
}

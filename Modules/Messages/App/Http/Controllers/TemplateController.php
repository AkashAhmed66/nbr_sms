<?php

namespace Modules\Messages\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Modules\Messages\App\Http\Requests\CreateTemplateRequest;
use Modules\Messages\App\Http\Requests\UpdateTemplateRequest;
use Modules\Messages\App\Repositories\TemplateRepositoryInterface;
use Modules\Messages\App\Trait\DataTableTrait;
use Yajra\DataTables\DataTables;

class TemplateController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;

  protected TemplateRepositoryInterface $templateRepository;

  public function __construct(TemplateRepositoryInterface $templateRepository)
  {
    $this->templateRepository = $templateRepository;
  }

  public function index()
  {
    $title = 'Template List';
    $datas = $this->templateRepository->all();
    $ajaxUrl = route('templates-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn(
          'action',
          fn($row) => $this->viewButton('templates.show', $row->id) . ' ' . $this->editButton(
              'templates-edit',
              $row->id
            ) . ' ' . $this->deleteButton('templates-destroy', $row->id)
        )
        ->rawColumns(['action'])
        ->make();
    }

    $tableHeaders = $this->getTableHeader('templates-list');
    return view('messages::template.index', compact('title','tableHeaders' , 'ajaxUrl'));
  }

  public function store(CreateTemplateRequest $request): RedirectResponse
  {
    $this->templateRepository->create($request->except('id'));
    return redirect()->route('templates-list')->with('success', 'Template created successfully');
  }

  public function show($id)
  {
    $template = $this->templateRepository->find($id);
    return view('messages::template.show', compact('template'));
  }

  public function edit($id)
  {
    $title = 'Edit Template';
    $data = $this->templateRepository->find($id);
    echo $data;
  }

  public function update(UpdateTemplateRequest $request, $id)
  {
    $this->templateRepository->update($request->validated(), $id);
    return response()->json(['status' => 'updated', 'message' => 'Template updated successfully']);
  }

  public function destroy($id)
  {
    $this->templateRepository->delete($id);
    return response()->json(['success' => 'Template deleted successfully']);
  }
}

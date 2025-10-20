<?php

namespace Modules\Phonebook\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\ActionButtonTrait;
use Illuminate\Support\Facades\DB;
use Modules\Phonebook\App\Http\Requests\CreateGroupRequest;
use Modules\Phonebook\App\Http\Requests\UpdateGroupRequest;
use Modules\Phonebook\App\Repositories\GroupRepositoryInterface;
use Modules\Users\App\Repositories\ResellerRepositoryInterface;
use Modules\Phonebook\App\Trait\DataTableTrait;
use Yajra\DataTables\DataTables;
use Modules\Phonebook\App\Models\Group;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\ContactImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;


class GroupController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;

  protected GroupRepositoryInterface $groupRepository;

  public function __construct(GroupRepositoryInterface $groupRepository, ResellerRepositoryInterface $resellerRepository)
  {
    $this->groupRepository = $groupRepository;
    $this->resellerRepository = $resellerRepository;
  }

  public function index()
  {

    $title = 'Group List';
    $datas = $this->groupRepository->all();
    $ajaxUrl = route('group-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('username', fn($row) => ($row->user) ? $row->user->name : '-')
        ->addColumn('resellername', fn($row) => ($row->reseller) ? $row->reseller->reseller_name : '-')
        ->addColumn('status', fn($row) => $this->statusButton($row->status, $row->id))
        ->addColumn('action', fn($row) => $this->editButton('group-edit', $row->id) . ' ' . $this->deleteButton('group-delete', $row->id))
        ->rawColumns(['status', 'action'])
        ->make();
    }

    $tableHeaders = $this->getTableHeader('group-list');
    $resellers = $this->resellerRepository->all();
    return view('phonebook::group.index', compact('title', 'tableHeaders', 'ajaxUrl', 'resellers'));
  }

  public function store(CreateGroupRequest $request)
  {
    $this->groupRepository->create($request->all());
    return response()->json(['status' => 'added', 'message' => 'Group added successfully']);
  }

  public function edit($id)
  {
    $group = $this->groupRepository->find($id);
    echo $group;
  }

  public function update(UpdateGroupRequest $request, $id)
  {
    $validatedData = $request->validated();

    $group = Group::find($id);

    if (!$group) {
      return response()->json(['status' => 'error', 'message' => 'Group not found']);
    }

    $group->update($validatedData);
    return response()->json(['status' => 'updated', 'message' => 'Group updated successfully']);
  }

  public function destroy($id)
  {
    $this->groupRepository->delete($id);
    return response()->json(['status' => 'deleted', 'message' => 'Group deleted successfully']);
  }

  public function OLDimportGroup(Request $request)
  {
    try {

      $request->validate([
        'name' => 'required|string|max:255',
        'importFile' => 'required|file|mimes:xlsx,csv,xls|max:10240', // 10MB max
      ], [
        'importFile.required' => 'Please select a file to import.',
        'importFile.file' => 'The uploaded file is not valid.',
        'importFile.mimes' => 'File must be in Excel (.xlsx, .xls) or CSV (.csv) format.',
        'importFile.max' => 'File size must not exceed 10MB.',
      ]);

      DB::beginTransaction();
      try {
        $group = Group::create([
          'name' => $request->input('name'),
          'user_id' => auth()->id(),
        ]);

        // Use chunked import for large files
        Excel::queueImport(
          new ContactImport($group->id, auth()->id()),
          $request->file('importFile')
        );

        /*        Excel::import(
                  new ContactImport($group->id, auth()->id()),
                  $request->file('importFile')
                );*/

        DB::commit();

        return response()->json([
          'message' => 'Group and contacts imported successfully.',
          'group_id' => $group->id,
        ], 200);
      } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
      }
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }


  public function importGroup(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'importFile' => 'required|file|mimes:xlsx,csv,xls|max:10240', // 10MB max
    ], [
      'importFile.required' => 'Please select a file to import.',
      'importFile.file' => 'The uploaded file is not valid.',
      'importFile.mimes' => 'File must be in Excel (.xlsx, .xls) or CSV (.csv) format.',
      'importFile.max' => 'File size must not exceed 10MB.',
    ]);

    if (!isset($_FILES['importFile'])) {
      return response()->json(['error' => true, 'message' => 'No file part received'], 422);
    }
    if ($_FILES['importFile']['error'] !== UPLOAD_ERR_OK) {
      return response()->json(['error' => true, 'message' => 'Upload error code: ' . $_FILES['importFile']['error']], 422);
    }

    DB::beginTransaction();

    try {
      $group = Group::create([
        'name' => $request->input('name'),
        'user_id' => auth()->id(),
      ]);

      $file = $request->file('importFile');
      $extension = strtolower($file->getClientOriginalExtension());

      // Save original upload to storage
      $path = $file->storeAs('imports', uniqid() . '.' . $extension, 'local');

      // If XLSX/XLS, convert to CSV
      if (in_array($extension, ['xlsx', 'xls'])) {
        $csvPath = storage_path('app/imports/' . uniqid() . '.csv');
        $spreadsheet = IOFactory::load(storage_path('app/' . $path));
        $writer = IOFactory::createWriter($spreadsheet, 'Csv');
        $writer->setDelimiter(',');
        $writer->setEnclosure('"');
        $writer->setSheetIndex(0);
        $writer->save($csvPath);

        // replace import file with csv path
        $importFile = new \Illuminate\Http\File($csvPath);
      } else {
        $importFile = $file;
      }

      // Queue import job
      Excel::queueImport(
        new ContactImport($group->id, auth()->id()),
        $importFile
      )->onQueue('contactImports');

      DB::commit();

      return response()->json([
        'message' => 'Import started successfully. Contacts are being processed in the background.',
        'group_id' => $group->id,
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();
      return response()->json([
        'error' => true,
        'message' => $e->getMessage(),
      ], 500);
    }
  }



  public function downloadExcel()
  {
    $filePath = public_path('sample-file/samplefile.xlsx');
    $fileName = 'samplefile.xlsx';
    if (file_exists($filePath)) {
      return response()->download($filePath, $fileName);
    }

    return response()->json(['error' => 'File not found.'], 404);
  }

  public function downloadExcelDynamic()
  {
    $filePath = public_path('sample-file/samplefiledynamic.xlsx');
    $fileName = 'samplefile.xlsx';
    if (file_exists($filePath)) {
      return response()->download($filePath, $fileName);
    }

    return response()->json(['error' => 'File not found.'], 404);
  }
}

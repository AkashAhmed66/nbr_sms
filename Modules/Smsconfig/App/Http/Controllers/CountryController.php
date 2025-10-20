<?php

namespace Modules\Smsconfig\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Modules\Smsconfig\App\Http\Requests\CreateCountryRequest;
use Modules\Smsconfig\App\Http\Requests\UpdateCountryRequest;
use Modules\Smsconfig\App\Repositories\CountryRepositoryInterface;
use Modules\Smsconfig\App\Trait\DataTableTrait;
use Yajra\DataTables\DataTables;
use Modules\Smsconfig\App\Models\Country;

class CountryController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;
    protected CountryRepositoryInterface $countryRepository;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function index()
    {
        $title = 'Country List';
        $datas = $this->countryRepository->all();
        $ajaxUrl = route('country-list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                
                //->addColumn('action', fn($row) => $this->editButton('country.edit', $row->id))
                ->addColumn('action', fn($row) => $this->editButton('country.edit', $row->id) . ' ' . $this->deleteButton('country-delete', $row->id))
                ->rawColumns(['action'])
                ->make();
        }
      $tableHeaders =  $this->getTableHeader('country');
	  
	  //echo '<pre>';print_r($tableHeaders);exit;
        return view('smsconfig::country.index', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    public function store(CreateCountryRequest $request)
    {
        $this->countryRepository->create($request->validated());
       // return redirect()->route('country-list')->with('success', 'Country added successfully');
       return response()->json(['status' => 'updated', 'message' => 'Country added successfully']);
    }

    public function edit($id)
    {
        $title = 'Country Edit';
        $data = $this->countryRepository->find($id);
        echo $data;
    }

    public function update(UpdateCountryRequest $request, $id)
    {
        $validatedData = $request->validated();

        $country = Country::find($id);

        if (!$country) {
            //return redirect()->route('country-list')->with('error', 'Operator not found');
            return response()->json(['message' => 'Country not found'], 404);
        }

        // Update the operator with validated data
        $country->update($validatedData);
        //$this->countryRepository->update($request->validated(), $id);
        //return redirect()->route('country-list')->with('success', 'Country updated successfully');
        return response()->json(['status' => 'updated', 'message' => 'Country updated successfully']);
    }

    public function destroy($id)
    {
        $this->countryRepository->delete($id);
        //return response()->json(['message' => 'Country deleted successfully']);
        return response()->json(['status' => 'updated', 'message' => 'Country deleted successfully']);
    }
}

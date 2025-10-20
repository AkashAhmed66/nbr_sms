<?php

namespace Modules\Smsconfig\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Keyword List';
        // Fetch keywords from the database or any other source
        $keywords = []; // Replace with actual data fetching logic


        return view('smsconfig::keyword.index', compact('keywords', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('smsconfig::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // DD the request data for debugging
        dd([
            'request_data' => $request->all(),
            'title' => $request->input('title'),
            'keywords' => $request->input('keywords'),
            'status' => $request->input('status'),
            'user_id' => Auth::check() ? Auth::user()->id : null,
            'timestamp' => now(),
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('smsconfig::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('smsconfig::edit');
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

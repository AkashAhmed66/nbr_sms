<?php

namespace Modules\Developers\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Smsconfig\App\Models\SenderId;
use Modules\Users\App\Models\User;
use Illuminate\Support\Facades\Hash;

class DevelopersController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('developers::index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('developers::create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): RedirectResponse
  {
    //
  }

  /**
   * Show the specified resource.
   */
  public function show($id)
  {
    return view('developers::show');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    return view('developers::edit');
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

  public function apiInfo()
  {
    $api_key = Auth::user()->APIKEY;

    // $senderId = SenderId::where(['user_id' => Auth::user()->id, 'status' => 'Active'])->first();
    $senderId = SenderId::where(['status' => 'Active'])->first();
    if (!$senderId) {
        $senderId = 'No active sender ID found';
    } else {
        $senderId = $senderId->senderID;
    }
    return view('smsconfig::api_information.apinfo', compact('api_key', 'senderId'));
  }

  public function updateKey()
    {
        $userInfo = Auth::user();
        $user = User::find($userInfo->id);
        $APIKEY = Hash::make($user->password.$user->username);
        if ($user) {
            $user->APIKEY = $APIKEY;
            $user->save();

            return response()->json(['message' => 'API KEY updated successfully']);
        }

        return response()->json(['message' => 'API KEY not found'], 404);
    }
}

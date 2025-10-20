<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Modules\API\App\Http\Controllers\v1\CustomerApiController;
use Modules\API\App\Http\Controllers\v1\SmsStatusControlle;


/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

/*Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('api', fn (Request $request) => $request->user())->name('api');
});*/

Route::prefix('v2')->group(function () {

  Route::post('{type}/sendsms', [\Modules\API\App\Http\Controllers\v2\IptspController::class, 'sendMessage']);
  Route::post('balance', [\Modules\API\App\Http\Controllers\v2\IptspController::class, 'checkBalance']);

  // Load Test Routes using grouped prefix
  Route::prefix('load-test')->group(function () {
    Route::post('{type}/sendsms', [\Modules\API\App\Http\Controllers\v2\LoadTestController::class, 'sendMessage']);
    Route::post('balance', [\Modules\API\App\Http\Controllers\v2\LoadTestController::class, 'checkBalance']);
  });

});

Route::prefix('v1')->group(function () {

/*  Route::any('/single-message', [CustomerApiController::class, 'sendSingle']);
  Route::any('/single-number-multiple-message', [CustomerApiController::class, 'sendSingleMultipleMessages']);
  Route::any('/multiple-number-multiple-message', [CustomerApiController::class, 'sendMultipleMultipleMessages']);*/


  Route::post('smsapi', [CustomerApiController::class, 'sendMessage'])->middleware('throttle:1000,1');
  Route::get('smsapi', [CustomerApiController::class, 'sendMessage'])->middleware('throttle:1000,1');
  Route::get('/send-sms-status', [SmsStatusControlle::class, 'getSmsStatus']);
  Route::get('/balance', [CustomerApiController::class, 'getBalance'])->middleware('throttle:1000,1');
  Route::get('/inbox', [CustomerApiController::class, 'getInbox'])->middleware('throttle:1000,1');
  Route::post('incoming-messages', [CustomerApiController::class, 'incomingMessages'])->middleware('throttle:1000,1');
  Route::get('/user-info', [CustomerApiController::class, 'getUserInfo'])->middleware('throttle:1000,1');
  Route::post('user-create', [CustomerApiController::class, 'createUser'])->middleware('throttle:1000,1');
  Route::put('/user-update/{id}', [CustomerApiController::class, 'updateUser'])->middleware('throttle:1000,1');
  Route::get('/incoming-messages-list', [CustomerApiController::class,'incomingMessagesList'])->middleware('throttle:1000,1');
});



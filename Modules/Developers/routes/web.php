<?php

use Illuminate\Support\Facades\Route;
use Modules\Developers\App\Http\Controllers\DevelopersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
  Route::group(['prefix' => 'developer'], function () {
    // Route::get('api-info', [DevelopersController::class, 'apiInfo'])->name('api-info');
    Route::post('/update-api-key', [DevelopersController::class, 'updateKey'])->name('update.api.key');
  });
});

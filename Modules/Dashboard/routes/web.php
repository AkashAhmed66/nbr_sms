<?php

use App\Http\Controllers\ChartsController;
use Illuminate\Support\Facades\Route;
use Modules\Dashboard\App\Http\Controllers\DashboardController;

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
Route::middleware('auth')->group(function () {
  Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/get-status-wise-message-data', [DashboardController::class, 'getStatusWiseMessage'])->name('get-status-wise-message-data');
    Route::get('/line-chart', [ChartsController::class, 'lineChart'])->name('line.chart');
  });
});

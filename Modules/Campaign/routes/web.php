<?php

use Illuminate\Support\Facades\Route;
use Modules\Campaign\App\Http\Controllers\CampaignController;

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
  Route::group(['prefix' => 'campaign'], function () {
      Route::get('schedule-campaign-list', [CampaignController::class, 'scheduleCampaignList'])->name('schedule-campaign-list');
      
      Route::get('running-campaign-list', [CampaignController::class, 'runningCampaignList'])->name('running-campaign-list');
      
      
      Route::get('running-campaign/{id}/retry', [CampaignController::class, 'runningCampaignRetry'])->name('running-campaign-retry');
      
      Route::get('details/{campaignId}', [CampaignController::class, 'show'])->name('campaign.details');
      
      // Route::get('archive-campaign-list', [CampaignController::class, 'archiveCampaignList'])->name('archive-campaign-list');
  });
});

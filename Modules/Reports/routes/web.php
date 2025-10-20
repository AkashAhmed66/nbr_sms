<?php

use App\Http\Controllers\OutboxExportController;
use Illuminate\Support\Facades\Route;
use Modules\Reports\App\Http\Controllers\ReportsController;

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
  Route::group(['prefix' => 'reports'], function () {
    Route::get('last-2days-failed-sms-list', [ReportsController::class, 'last2DaysFailedSmsList'])->name('last-2days-failed-sms-list');
    Route::get('failed-archived-sms', [ReportsController::class, 'failedArchivedSms'])->name('failed-archived-sms');
    Route::get('last-2days-sms-list', [ReportsController::class, 'last2DaysSmsList'])->name('last-2days-sms-list');
    Route::get('archived-sms', [ReportsController::class, 'archivedSms'])->name('archived-sms');
    Route::get('summery-log', [ReportsController::class, 'summeryLog'])->name('summery-log');
    Route::get('day-wise-log', [ReportsController::class, 'dayWiseLog'])->name('day-wise-log');
    Route::get('total-sms-log', [ReportsController::class, 'totalSmsLog'])->name('total-sms-log');
    Route::get('client-daywise-sms', [ReportsController::class, 'clientDaywiseSms'])->name('client-daywise-sms');
    Route::get('btrc-report', [ReportsController::class, 'getBtrcReport'])->name('btrc-report');
    Route::get('inbox-report', [ReportsController::class, 'getInboxReport'])->name('inbox-report');

    Route::get('archived-sms-report-export/{param?}', [ReportsController::class, 'archivedSmsReportExport'])->name('archived-sms-report-export');

    Route::get('inbox-export/{param?}', [ReportsController::class, 'inboxReportExport'])->name('inbox-export');




    Route::get('/exports/outbox', [OutboxExportController::class, 'create'])->name('exports.outbox.form');
    Route::post('/exports/outbox', [OutboxExportController::class, 'store'])->name('exports.outbox.start');
    Route::get('/exports/{id}', [OutboxExportController::class, 'show'])->name('exports.show');
    Route::get('/exports/{id}/download', [OutboxExportController::class, 'download'])->name('exports.download');



  });
});


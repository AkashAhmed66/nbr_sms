<?php

use Illuminate\Support\Facades\Route;
use Modules\Messages\App\Http\Controllers\MessagesController;
use Modules\Messages\App\Http\Controllers\TemplateController;

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
  Route::group(['prefix' => 'messages'], function () {
    Route::get('create', [MessagesController::class, 'create'])->name('messages.create');
    Route::get('create/dynamic', [MessagesController::class, 'dynamic'])->name('messages.dynamic');
    Route::post('/store/regular-message', [MessagesController::class, 'storeRegularMessage'])->name('messages.store-regular-message');
    Route::post('/store/group-message', [MessagesController::class, 'storeGroupMessage'])->name('messages.store-group-message');
    Route::post('/store/file-message', [MessagesController::class, 'storeFileMessage'])->name('messages.store-file-message');
    Route::post('/store/dynamic-message', [MessagesController::class, 'storeDynamicMessageActual'])->name('messages.store-dynamic-message');

    //Route::get('inbox-list', [MessagesController::class, 'index'])->name('inbox-list');

    Route::get('templates-list', [TemplateController::class, 'index'])->name('templates-list');
    Route::post('templates-store', [TemplateController::class, 'store'])->name('templates-store');
    Route::get('template/{id}/edit', [TemplateController::class, 'edit'])->name('templates-edit');
    Route::get('template/{id}/show', [TemplateController::class, 'show'])->name('templates.show');
    Route::put('template-update/{id}', [TemplateController::class, 'update'])->name('templates-update');
    Route::delete('templates-delete/{id}', [TemplateController::class, 'destroy'])->name('templates-destroy');
  });
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Phonebook\App\Http\Controllers\DndController;
use Modules\Phonebook\App\Http\Controllers\GroupController;
use Modules\Phonebook\App\Http\Controllers\PhonebookController;

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
  /*Route::group(['prefix' => 'phonebook'], function () {
    Route::get('groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('group/{id}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('group/update/{id}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('group/delete/{id}', [GroupController::class, 'destroy'])->name('groups.destroy');

    Route::get('contacts', [PhonebookController::class, 'index'])->name('contacts.index');
    Route::post('contacts', [PhonebookController::class, 'store'])->name('contacts.store');
    Route::get('contact/{id}/edit', [PhonebookController::class, 'edit'])->name('contacts.edit');
    Route::put('contact/update/{id}', [PhonebookController::class, 'update'])->name('contacts.update');
    Route::delete('contact/delete/{id}', [PhonebookController::class, 'destroy'])->name('contacts.destroy');

    Route::get('dnd-list', [DndController::class, 'index'])->name('dnd-list');
    Route::post('dnd', [DndController::class, 'store'])->name('dnd.store');
    Route::get('dnd/{id}/edit', [DndController::class, 'edit'])->name('dnd.edit');
    Route::put('dnd/update/{id}', [DndController::class, 'update'])->name('dnd.update');
    Route::delete('dnd/delete/{id}', [DndController::class, 'destroy'])->name('dnd.destroy');
  });*/


  Route::group(['prefix' => 'phonebook'], function () {
    Route::get('group-list', [GroupController::class, 'index'])->name('group-list');
    Route::post('group-store', [GroupController::class, 'store'])->name('group-store');
    Route::get('group/{id}/edit', [GroupController::class, 'edit'])->name('group-edit');
    Route::put('group-update/{id}', [GroupController::class, 'update'])->name('group-update');
    Route::delete('group-delete/{id}', [GroupController::class, 'destroy'])->name('group-delete');

    Route::post('group-import', [GroupController::class, 'importGroup']);
    Route::get('/download-excel', [GroupController::class, 'downloadExcel'])->name('download.excel');
    Route::get('/download-excel-dynamic', [GroupController::class, 'downloadExcelDynamic'])->name('download.excel.dynamic');


    Route::get('contacts-list', [PhonebookController::class, 'index'])->name('contacts-list');
    Route::post('contacts-store', [PhonebookController::class, 'store'])->name('contacts-store');
    Route::get('contacts/{id}/edit', [PhonebookController::class, 'edit'])->name('contacts-edit');
    Route::put('contacts-update/{id}', [PhonebookController::class, 'update'])->name('contacts-update');
    Route::delete('contacts-delete/{id}', [PhonebookController::class, 'destroy'])->name('contacts-delete');

    Route::get('dnd-list', [DndController::class, 'index'])->name('dnd-list');
    Route::post('dnd-store', [DndController::class, 'store'])->name('dnd-store');
    Route::get('dnd/{id}/edit', [DndController::class, 'edit'])->name('dnd-edit');
    Route::put('dnd-update/{id}', [DndController::class, 'update'])->name('dnd-update');
    Route::delete('dnd-delete/{id}', [DndController::class, 'destroy'])->name('dnd-delete');

  });
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\Transactions\App\Http\Controllers\TransactionController;

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
  /*Route::group(['prefix' => 'transactions'], function () {
    Route::get('user-wallet-list', [TransactionController::class, 'userWallet'])->name('user-wallet-list');
    
    
    Route::get('reseller-wallet-list', [TransactionController::class, 'resellerWallet'])->name('reseller-wallet-list');


  });*/
  
  Route::group(['prefix' => 'transactions'], function () {
    Route::get('uwallet-list', [TransactionController::class, 'userWallet'])->name('uwallet-list');
    Route::post('uwallet-store', [TransactionController::class, 'store'])->name('uwallet-store');
    Route::get('uwallet/{id}/edit', [TransactionController::class, 'edit'])->name('uwallet-edit');
    Route::put('uwallet-update/{id}', [TransactionController::class, 'update'])->name('uwallet-update');
    Route::delete('uwallet-delete/{id}', [TransactionController::class, 'destroy'])->name('uwallet-delete');
    
    Route::get('online-list', [TransactionController::class, 'onlineList'])->name('online-list');

    Route::get('rwallet-list', [TransactionController::class, 'resellerWallet'])->name('rwallet-list');
    Route::post('rwallet-store', [TransactionController::class, 'reseller_wallet_store'])->name('rwallet-store');
    Route::get('rwallet/{id}/edit', [TransactionController::class, 'reseller_wallet_edit'])->name('rwallet-edit');
    Route::put('rwallet-update/{id}', [TransactionController::class, 'reseller_wallet_update'])->name('rwallet-update');
    Route::delete('rwallet-delete/{id}', [TransactionController::class, 'reseller_wallet_destroy'])->name('rwallet-delete');
    
    
    Route::get('user-transfer-list', [TransactionController::class, 'userTransferList'])->name('user-transfer-list');
    Route::post('user-balance-add', [TransactionController::class, 'addUserBalance'])->name('user-balance-add');
    Route::get('reseller-transfer-list', [TransactionController::class, 'resellerTransferList'])->name(
      'reseller-transfer-list'
    );
    Route::post('reseller-balance-add', [TransactionController::class, 'addResellerBalance'])->name(
      'reseller-balance-add'
    );
    Route::put('approve-transaction/{id}', [TransactionController::class, 'approveTransaction'])->name(
      'approve-transaction'
    );
  });
});

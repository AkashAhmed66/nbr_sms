<?php

use Illuminate\Support\Facades\Route;
use Modules\Smsconfig\App\Http\Controllers\CountryController;
use Modules\Smsconfig\App\Http\Controllers\BlackListedKeywordController;
use Modules\Smsconfig\App\Http\Controllers\OperatorController;
use Modules\Smsconfig\App\Http\Controllers\RateController;
use Modules\Smsconfig\App\Http\Controllers\RouteController;
use Modules\Smsconfig\App\Http\Controllers\SenderIdController;
use Modules\Smsconfig\App\Http\Controllers\MaskController;
use Modules\Smsconfig\App\Http\Controllers\ServiceProviderController;
use Modules\Users\App\Http\Controllers\UserGroupController;
use Modules\Users\App\Http\Controllers\ResellerController;
use Modules\Users\App\Http\Controllers\UsersController;
use Modules\Phonebook\App\Http\Controllers\GroupController;
use Modules\Phonebook\App\Http\Controllers\PhonebookController;
use Modules\Smsconfig\App\Http\Controllers\DndController;
use Modules\Smsconfig\App\Http\Controllers\KeywordController;
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
Route::middleware(['auth'])->group(function () {
  Route::group(['prefix' => 'sms-config'], function () {
    Route::get('keyword-list', [BlackListedKeywordController::class, 'index'])->name('keyword-list');
    Route::post('keyword-store', [BlackListedKeywordController::class, 'store'])->name('keyword-store');
    Route::get('keyword/{id}/edit', [BlackListedKeywordController::class, 'edit'])->name('keyword-edit');
    Route::put('keyword-update/{id}', [BlackListedKeywordController::class, 'update'])->name('keyword-update');
    Route::get('keyword-view/{id}', [BlackListedKeywordController::class, 'show'])->name('keyword-view');
    Route::delete('keyword-delete/{id}', [BlackListedKeywordController::class, 'destroy'])->name('keyword-delete');

    Route::get('country-list', [CountryController::class, 'index'])->name('country-list');
    Route::post('country-store', [CountryController::class, 'store'])->name('country-store');
    Route::get('country/{id}/edit', [CountryController::class, 'edit'])->name('country-edit');
    Route::get('country-view/{id}', [CountryController::class, 'show'])->name('country-show');
    Route::put('country-update/{id}', [CountryController::class, 'update'])->name('country-update');
    Route::delete('country-delete/{id}', [CountryController::class, 'destroy'])->name('country-delete');



    Route::get('operator-list', [OperatorController::class, 'index'])->name('operator-list');
	  Route::post('operator-store', [OperatorController::class, 'store'])->name('operator-store');

    //Route::post('operator-store', [OperatorController::class, 'store'])->name('operator-store');
    Route::get('operator/{id}/edit', [OperatorController::class, 'edit'])->name('operator-edit');
    Route::get('operator-view/{id}', [OperatorController::class, 'show'])->name('operator-show');
    Route::put('operator-update/{id}', [OperatorController::class, 'update'])->name('operator-update');
    Route::delete('operator-delete/{id}', [OperatorController::class, 'destroy'])->name('operator-delete');

    Route::get('service-provider-list', [ServiceProviderController::class, 'index'])->name('service-provider-list');
    Route::post('service-provider-store', [ServiceProviderController::class, 'store'])->name('service-provider-store');
    Route::get('service-provider/{id}/edit', [ServiceProviderController::class, 'edit'])->name('service-provider-edit');
    Route::put('service-provider-update/{id}', [ServiceProviderController::class, 'update'])->name('service-provider-update');
    Route::get('service-provider-view/{id}', [ServiceProviderController::class, 'show'])->name('service-provider-view');
    Route::delete('service-provider-delete/{id}', [ServiceProviderController::class, 'destroy'])->name('service-provider-delete');

    Route::get('route-list', [RouteController::class, 'index'])->name('route-list');
    Route::post('route-store', [RouteController::class, 'store'])->name('route-store');
    Route::get('route/{id}/edit', [RouteController::class, 'edit'])->name('route-edit');
    Route::get('route-view/{id}', [RouteController::class, 'show'])->name('route-view');
    Route::put('route-update/{id}', [RouteController::class, 'update'])->name('route-update');
    Route::delete('route-delete/{id}', [RouteController::class, 'destroy'])->name('route-delete');

    Route::get('rate-list', [RateController::class, 'index'])->name('rate-list');
    Route::post('rate-store', [RateController::class, 'store'])->name('rate-store');
    Route::get('rate/{id}/edit', [RateController::class, 'edit'])->name('rate-edit');
    Route::get('rate-view/{id}', [RateController::class, 'show'])->name('rate-show');
    Route::put('rate-update/{id}', [RateController::class, 'update'])->name('rate-update');
    Route::delete('rate-delete/{id}', [RateController::class, 'destroy'])->name('rate-delete');

    Route::get('sender-id-list', [SenderIdController::class, 'index'])->name('sender-id-list');
    Route::post('sender-id-store', [SenderIdController::class, 'store'])->name('sender-id-store');
    Route::get('sender-id/{id}/edit', [SenderIdController::class, 'edit'])->name('sender-id-edit');
    Route::get('sender-id-view/{id}', [SenderIdController::class, 'show'])->name('sender-id-view');
    Route::put('sender-id-update/{id}', [SenderIdController::class, 'update'])->name('sender-id-update');
    Route::delete('sender-id-delete/{id}', [SenderIdController::class, 'destroy'])->name('sender-id-delete');
    Route::get('api-info', [SenderIdController::class, 'apiInfo'])->name('api-info');

    Route::get('mask-list', [MaskController::class, 'index'])->name('mask-list');
    Route::post('mask-store', [MaskController::class, 'store'])->name('mask-store');
    Route::get('mask/{id}/edit', [MaskController::class, 'edit'])->name('mask-edit');
    Route::get('mask-view/{id}', [MaskController::class, 'show'])->name('mask-view');
    Route::put('mask-update/{id}', [MaskController::class, 'update'])->name('mask-update');
    Route::delete('mask-delete/{id}', [MaskController::class, 'destroy'])->name('mask-delete');


    Route::get('keyword-list', [KeywordController::class, 'index'])->name('keyword-list');
    Route::post('keyword-store', [KeywordController::class, 'store'])->name('keyword-store');
  });

  /*Route::group(['prefix' => 'users'], function () {
    Route::get('user-group-list', [UserGroupController::class, 'index'])->name('user-group-list');
    Route::post('user-group-store', [UserGroupController::class, 'store'])->name('user-group-store');
    Route::get('user-group/{id}/edit', [UserGroupController::class, 'edit'])->name('user-group-edit');
    Route::put('user-group-update/{id}', [UserGroupController::class, 'update'])->name('user-group-update');
    Route::delete('user-group-delete/{id}', [UserGroupController::class, 'destroy'])->name('user-group-delete');

    Route::get('reseller-list', [ResellerController::class, 'index'])->name('reseller-list');
    Route::post('reseller-store', [ResellerController::class, 'store'])->name('reseller-store');
    Route::get('reseller/{id}/edit', [ResellerController::class, 'edit'])->name('reseller-edit');
    Route::put('reseller-update/{id}', [ResellerController::class, 'update'])->name('reseller-update');
    Route::delete('reseller-delete/{id}', [ResellerController::class, 'destroy'])->name('reseller-delete');

    Route::get('users-list', [UsersController::class, 'index'])->name('users-list');
    Route::post('users-store', [UsersController::class, 'store'])->name('users-store');
    Route::get('users/{id}/edit', [UsersController::class, 'edit'])->name('users-edit');
    Route::put('users-update/{id}', [UsersController::class, 'update'])->name('users-update');
    Route::delete('users-delete/{id}', [UsersController::class, 'destroy'])->name('users-delete');

  });*/

  /*Route::group(['prefix' => 'phonebook'], function () {
    Route::get('group-list', [GroupController::class, 'index'])->name('group-list');
    Route::post('group-store', [GroupController::class, 'store'])->name('group-store');
    Route::get('group/{id}/edit', [GroupController::class, 'edit'])->name('group-edit');
    Route::put('group-update/{id}', [GroupController::class, 'update'])->name('group-update');
    Route::delete('group-delete/{id}', [GroupController::class, 'destroy'])->name('group-delete');

    Route::get('contacts-list', [PhonebookController::class, 'index'])->name('contacts-list');
    Route::post('contacts-store', [PhonebookController::class, 'store'])->name('contacts-store');
    Route::get('contacts/{id}/edit', [PhonebookController::class, 'edit'])->name('contacts-edit');
    Route::put('contacts-update/{id}', [PhonebookController::class, 'update'])->name('contacts-update');
    Route::delete('contacts-delete/{id}', [PhonebookController::class, 'destroy'])->name('contacts-delete');

    Route::get('dnd-list', [DndController::class, 'index'])->name('dnd-list');
    Route::post('dnd-store', [DndController::class, 'store'])->name('dnd-store');
    Route::get('dnd-export', [DndController::class, 'export'])->name('dnd-export');
    Route::get('dnd/{id}/edit', [DndController::class, 'edit'])->name('dnd-edit');
    Route::put('dnd-update/{id}', [DndController::class, 'update'])->name('dnd-update');
    Route::delete('dnd-delete/{id}', [DndController::class, 'destroy'])->name('dnd-delete');

  });*/

  /*Route::group(['prefix' => 'transactions'], function () {
    Route::get('uwallet-list', [TransactionController::class, 'userWallet'])->name('uwallet-list');
    Route::post('uwallet-store', [TransactionController::class, 'store'])->name('uwallet-store');
    Route::get('uwallet/{id}/edit', [TransactionController::class, 'edit'])->name('uwallet-edit');
    Route::put('uwallet-update/{id}', [TransactionController::class, 'update'])->name('uwallet-update');
    Route::delete('uwallet-delete/{id}', [TransactionController::class, 'destroy'])->name('uwallet-delete');

    Route::get('rwallet-list', [TransactionController::class, 'resellerWallet'])->name('rwallet-list');
    Route::post('rwallet-store', [TransactionController::class, 'reseller_wallet_store'])->name('rwallet-store');
    Route::get('rwallet/{id}/edit', [TransactionController::class, 'reseller_wallet_edit'])->name('rwallet-edit');
    Route::put('rwallet-update/{id}', [TransactionController::class, 'reseller_wallet_update'])->name('rwallet-update');
    Route::delete('rwallet-delete/{id}', [TransactionController::class, 'reseller_wallet_destroy'])->name('rwallet-delete');
  });*/
});


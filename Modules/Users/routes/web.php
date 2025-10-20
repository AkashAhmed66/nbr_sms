<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\App\Http\Controllers\ResellerController;
use Modules\Users\App\Http\Controllers\UserGroupController;
use Modules\Users\App\Http\Controllers\UsersController;

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

  //profile route
  Route::get('profile', [UsersController::class, 'profile'])->name('profile');
  Route::post('profile-update', [UsersController::class, 'profileUpdate'])->name('profile-update');

  /*Route::group(['prefix' => 'users'], function () {
    Route::get('group-list', [UserGroupController::class, 'index'])->name('user-group-list');
    Route::post('group-store', [UserGroupController::class, 'store'])->name('user-group-store');
    Route::get('group/{id}/edit', [UserGroupController::class, 'edit'])->name('user-group-edit');
    Route::put('group/update/{id}', [UserGroupController::class, 'update'])->name('user-group-update');
    Route::delete('group/delete/{id}', [UserGroupController::class, 'destroy'])->name('user-group-destroy');

    Route::get('user-list', [UsersController::class, 'index'])->name('user-list');
    Route::post('user-store', [UsersController::class, 'store'])->name('user-store');
    Route::get('user/{id}/edit', [UsersController::class, 'edit'])->name('user-edit');
    Route::put('user/update/{id}', [UsersController::class, 'update'])->name('user-update');
    Route::delete('user/delete/{id}', [UsersController::class, 'destroy'])->name('user-destroy');

    Route::get('reseller-list', [ResellerController::class, 'index'])->name('reseller-list');
    Route::post('reseller-store', [ResellerController::class, 'store'])->name('reseller-store');
    Route::get('reseller/{id}/edit', [ResellerController::class, 'edit'])->name('reseller-edit');
    Route::put('reseller/update/{id}', [ResellerController::class, 'update'])->name('reseller-update');
    Route::delete('reseller/delete/{id}', [ResellerController::class, 'destroy'])->name('reseller-destroy');
  });*/


  Route::group(['prefix' => 'users'], function () {
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
    Route::get('check-transection/{user_id}', [UsersController::class, 'checkTransection'])->name('check-transection');
    Route::get('login-as/{id}', [UsersController::class, 'loginAs'])->name('users-login-as');
    Route::get('users-redis-list', [UsersController::class, 'usersRedisList'])->name('users-redis-list');
    Route::get('users-redis-user-data/{username}', [UsersController::class, 'checkRedisUser'])->name('users-redis-user-data');
  });
});

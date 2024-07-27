<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VerifikatorContoller;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register'])->name('register');
Route::post('auth/login', [AuthController::class, 'login'])->name('login');
Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function() {
   Route::middleware(['is_admin'])->prefix('admin')->controller(AdminController::class)->group(function() {
      Route::get('list-user', 'listUser');
      Route::get('list-permission', 'listPermission');
      Route::post('add-verifikator', 'addVerifikator');
      Route::post('set-verifikator/{user}', 'setAsVerifikator');
      Route::post('reset-password/{user}', 'resetPasswordUser');
   });

   Route::middleware(['is_verifikator'])->prefix('verifikator')->controller(VerifikatorContoller::class)->group(function () {
      Route::get('list-user', 'listUser');
      Route::get('list-permission', 'listPermission');
      Route::post('verify-user/{user}', 'verifyUser');
      Route::post('acc-permission/{permission}', 'accPermission');
      Route::post('revision-permission/{permission}', 'revisionPermission');
      Route::post('reject-permission/{permission}', 'rejectPermission');
   });

   Route::middleware(['is_user', 'is_verified'])->prefix('user')->controller(UserController::class)->group(function() {
      Route::get('list-permission', 'listPermission');
      Route::get('permission/{permission}', 'showPermission');
      Route::post('submit-permission', 'submitPermission');
      Route::put('permission/{permission}/update', 'updatePermission');
      Route::post('permission/{permission}/cancel', 'cancelPermission');
      Route::delete('permission/{permission}', 'deletePermission');
      Route::post('reset-password', 'resetPassword');
   });
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Acl\UserController;

Route::post('store', [UserController::class, 'store']);
Route::get('show/{uuid}', [UserController::class, 'show']);
Route::put('update/{uuid}', [UserController::class, 'update']);
Route::delete('destroy/{uuid}', [UserController::class, 'destroy']);
Route::any('search', [UserController::class, 'search']);
Route::put('activate/{uuid}', [UserController::class, 'activate']);
Route::put('inactivate/{uuid}', [UserController::class, 'inactivate']);
Route::put('deleted/{uuid}', [UserController::class, 'deleted']);
Route::put('recover/{uuid}', [UserController::class, 'recover']);
Route::any('store-admin', [UserController::class, 'storeAdmin']);
Route::post('attach-company', [UserController::class, 'attachCompany']);
Route::post('detach-company', [UserController::class, 'detachCompany']);
Route::get('profile', [UserController::class, 'profile']);
Route::put('profile-update', [UserController::class, 'profileUpdate']);
Route::put('profile-update-password', [UserController::class, 'updatePassword']);
Route::post('profile-update-photo', [UserController::class, 'uploadPhotoProfile']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Acl\UserController;

Route::post('', [UserController::class, 'store']);
Route::get('/{uuid}', [UserController::class, 'show']);
Route::put('/{uuid}', [UserController::class, 'update']);
Route::delete('/{uuid}', [UserController::class, 'destroy']);
Route::any('search', [UserController::class, 'search']);
Route::put('activate/{uuid}', [UserController::class, 'activate']);
Route::put('inactivate/{uuid}', [UserController::class, 'inactivate']);
Route::put('deleted/{uuid}', [UserController::class, 'deleted']);
Route::put('recover/{uuid}', [UserController::class, 'recover']);
Route::any('create-admin', [UserController::class, 'storeAdmin']);
Route::get('profile', [UserController::class, 'profile']);
Route::put('profile-update', [UserController::class, 'profileUpdate']);
Route::put('profile-update-password', [UserController::class, 'updatePassword']);
Route::post('profile-upload-photo', [UserController::class, 'uploadPhotoProfile']);

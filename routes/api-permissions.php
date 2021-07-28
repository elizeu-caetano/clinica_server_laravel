<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Acl\PermissionController;

Route::post('store', [PermissionController::class, 'store']);
Route::get('show/{uuid}', [PermissionController::class, 'show']);
Route::put('update/{uuid}', [PermissionController::class, 'update']);
Route::delete('destroy/{uuid}', [PermissionController::class, 'destroy']);
Route::any('search', [PermissionController::class, 'search']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Acl\RoleController;

Route::post('', [RoleController::class, 'store']);
Route::get('/{uuid}', [RoleController::class, 'show']);
Route::put('/{uuid}', [RoleController::class, 'update']);
Route::delete('/{uuid}', [RoleController::class, 'destroy']);
Route::any('search', [RoleController::class, 'search']);
Route::put('activate/{uuid}', [RoleController::class, 'activate']);
Route::put('inactivate/{uuid}', [RoleController::class, 'inactivate']);
Route::put('deleted/{uuid}', [RoleController::class, 'deleted']);
Route::put('recover/{uuid}', [RoleController::class, 'recover']);
Route::get('role-permissions/{uuid}', [RoleController::class, 'rolePermissions']);
Route::post('role-permissions/attach', [RoleController::class, 'attachPermissions']);
Route::post('role-permissions/detach', [RoleController::class, 'detachPermissions']);

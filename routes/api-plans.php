<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Acl\PlanController;

Route::post('store', [PlanController::class, 'store']);
Route::get('show/{uuid}', [PlanController::class, 'show']);
Route::put('update/{uuid}', [PlanController::class, 'update']);
Route::delete('destroy/{uuid}', [PlanController::class, 'destroy']);
Route::any('search', [PlanController::class, 'search']);
Route::put('activate/{uuid}', [PlanController::class, 'activate']);
Route::put('inactivate/{uuid}', [PlanController::class, 'inactivate']);
Route::get('plan-permissions/{uuid}', [PlanController::class, 'planPermissions']);
Route::post('plan-permissions/attach', [PlanController::class, 'attachPermissions']);
Route::post('plan-permissions/detach', [PlanController::class, 'detachPermissions']);

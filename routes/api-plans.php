<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Acl\PlanController;

Route::post('', [PlanController::class, 'store']);
Route::get('/{uuid}', [PlanController::class, 'show']);
Route::put('/{uuid}', [PlanController::class, 'update']);
Route::delete('/{uuid}', [PlanController::class, 'destroy']);
Route::any('search', [PlanController::class, 'search']);
Route::put('activate/{uuid}', [PlanController::class, 'activate']);
Route::put('inactivate/{uuid}', [PlanController::class, 'inactivate']);
Route::get('plan-permissions/{uuid}', [PlanController::class, 'planPermissions']);
Route::post('plan-permissions/attach', [PlanController::class, 'attachPermissions']);
Route::post('plan-permissions/detach', [PlanController::class, 'detachPermissions']);

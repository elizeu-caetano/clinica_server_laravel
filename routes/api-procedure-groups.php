<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProcedureGroupController;

Route::post('store', [ProcedureGroupController::class, 'store']);
Route::get('show/{uuid}', [ProcedureGroupController::class, 'show']);
Route::put('update/{uuid}', [ProcedureGroupController::class, 'update']);
Route::delete('destroy/{uuid}', [ProcedureGroupController::class, 'destroy']);
Route::any('search', [ProcedureGroupController::class, 'search']);
Route::put('deleted/{uuid}', [ProcedureGroupController::class, 'deleted']);
Route::put('recover/{uuid}', [ProcedureGroupController::class, 'recover']);

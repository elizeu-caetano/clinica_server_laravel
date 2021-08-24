<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProcedureController;

Route::post('store', [ProcedureController::class, 'store']);
Route::get('show/{uuid}', [ProcedureController::class, 'show']);
Route::put('update/{uuid}', [ProcedureController::class, 'update']);
Route::delete('destroy/{uuid}', [ProcedureController::class, 'destroy']);
Route::any('search', [ProcedureController::class, 'search']);
Route::put('activate/{uuid}', [ProcedureController::class, 'activate']);
Route::put('inactivate/{uuid}', [ProcedureController::class, 'inactivate']);
Route::put('deleted/{uuid}', [ProcedureController::class, 'deleted']);
Route::put('recover/{uuid}', [ProcedureController::class, 'recover']);

<?php

use App\Http\Controllers\JobMed\DangerController;
use Illuminate\Support\Facades\Route;

Route::post('store', [DangerController::class, 'store']);
Route::get('show/{id}', [DangerController::class, 'show']);
Route::put('update/{id}', [DangerController::class, 'update']);
Route::delete('destroy/{id}', [DangerController::class, 'destroy']);
Route::any('search', [DangerController::class, 'search']);
Route::put('activate/{id}', [DangerController::class, 'activate']);
Route::put('inactivate/{id}', [DangerController::class, 'inactivate']);
Route::put('deleted/{id}', [DangerController::class, 'deleted']);
Route::put('recover/{id}', [DangerController::class, 'recover']);

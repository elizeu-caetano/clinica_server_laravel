<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobMed\OccupationController;

Route::post('store', [OccupationController::class, 'store']);
Route::get('show/{id}', [OccupationController::class, 'show']);
Route::put('update/{id}', [OccupationController::class, 'update']);
Route::delete('destroy/{id}', [OccupationController::class, 'destroy']);
Route::any('search', [OccupationController::class, 'search']);
Route::put('activate/{id}', [OccupationController::class, 'activate']);
Route::put('inactivate/{id}', [OccupationController::class, 'inactivate']);
Route::put('deleted/{id}', [OccupationController::class, 'deleted']);
Route::put('recover/{id}', [OccupationController::class, 'recover']);

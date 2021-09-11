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
Route::get('dangers-occupation/{id}', [OccupationController::class, 'dangersOccupation']);
Route::post('dangers-occupation/attach', [OccupationController::class, 'attachDangers']);
Route::post('dangers-occupation/detach', [OccupationController::class, 'detachDangers']);
Route::get('procedures-occupation/{id}', [OccupationController::class, 'proceduresOccupation']);
Route::post('procedures-occupation/attach', [OccupationController::class, 'attachProcedures']);
Route::post('procedures-occupation/detach', [OccupationController::class, 'detachProcedures']);

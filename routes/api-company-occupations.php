<?php

use App\Http\Controllers\JobMed\CompanyOccupationController;
use Illuminate\Support\Facades\Route;

Route::get('index/{id}', [CompanyOccupationController::class, 'index']);
Route::any('search', [CompanyOccupationController::class, 'search']);
Route::post('store', [CompanyOccupationController::class, 'store']);
Route::get('show/{id}', [CompanyOccupationController::class, 'show']);
Route::put('update/{id}', [CompanyOccupationController::class, 'update']);
Route::delete('destroy/{id}', [CompanyOccupationController::class, 'destroy']);

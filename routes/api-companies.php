<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CompanyController;

Route::post('store', [CompanyController::class, 'store']);
Route::get('show/{uuid}', [CompanyController::class, 'show']);
Route::put('update/{uuid}', [CompanyController::class, 'update']);
Route::delete('destroy/{uuid}', [CompanyController::class, 'destroy']);
Route::any('search', [CompanyController::class, 'search']);
Route::put('activate/{uuid}', [CompanyController::class, 'activate']);
Route::put('inactivate/{uuid}', [CompanyController::class, 'inactivate']);
Route::put('deleted/{uuid}', [CompanyController::class, 'deleted']);
Route::put('recover/{uuid}', [CompanyController::class, 'recover']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DiscountTableController;

Route::post('store', [DiscountTableController::class, 'store']);
Route::get('show/{uuid}', [DiscountTableController::class, 'show']);
Route::put('update/{uuid}', [DiscountTableController::class, 'update']);
Route::delete('destroy/{uuid}', [DiscountTableController::class, 'destroy']);
Route::any('search', [DiscountTableController::class, 'search']);
Route::put('activate/{uuid}', [DiscountTableController::class, 'activate']);
Route::put('inactivate/{uuid}', [DiscountTableController::class, 'inactivate']);
Route::put('deleted/{uuid}', [DiscountTableController::class, 'deleted']);
Route::put('recover/{uuid}', [DiscountTableController::class, 'recover']);
Route::get('procedures/{uuid}', [DiscountTableController::class, 'proceduresDiscountTable']);
Route::put('procedure-update/{uuid}', [DiscountTableController::class, 'updateProcedureDiscountTable']);

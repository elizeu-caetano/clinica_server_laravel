<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Acl\ContractorController;

Route::post('store', [ContractorController::class, 'store']);
Route::get('show/{uuid}', [ContractorController::class, 'show']);
Route::put('update/{uuid}', [ContractorController::class, 'update']);
Route::delete('destroy/{uuid}', [ContractorController::class, 'destroy']);
Route::post('search', [ContractorController::class, 'search']);
Route::put('activate/{uuid}', [ContractorController::class, 'activate']);
Route::put('inactivate/{uuid}', [ContractorController::class, 'inactivate']);
Route::put('deleted/{uuid}', [ContractorController::class, 'deleted']);
Route::put('recover/{uuid}', [ContractorController::class, 'recover']);
Route::post('upload-logo', [ContractorController::class, 'uploadLogo']);
Route::get('contractor-plans/{uuid}', [ContractorController::class, 'contractorPlans']);
Route::post('contractor-plans/attach', [ContractorController::class, 'attachPlans']);
Route::post('contractor-plans/detach', [ContractorController::class, 'detachPlans']);

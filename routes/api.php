<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Acl\{
    AuthUserController,
    ContractorController,
    PermissionController,
    PlanController,
    RoleController,
    UserController
};


Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::get('/authorized', [AuthUserController::class, 'authorized']);
    Route::post('/logout', [AuthUserController::class, 'logout']);

    Route::post('contractors/search', [ContractorController::class, 'search']);
    Route::post('contractors/update', [ContractorController::class, 'update']);
    Route::put('contractors/activate/{uuid}', [ContractorController::class, 'activate']);
    Route::put('contractors/inactivate/{uuid}', [ContractorController::class, 'inactivate']);
    Route::put('contractors/deleted/{uuid}', [ContractorController::class, 'deleted']);
    Route::put('contractors/recover/{uuid}', [ContractorController::class, 'recover']);
    Route::apiResource('contractors', ContractorController::class);

    Route::any('permissions/search', [PermissionController::class, 'search']);
    Route::apiResource('permissions', PermissionController::class);

    Route::any('plans/search', [PlanController::class, 'search']);
    Route::put('plans/activate/{uuid}', [PlanController::class, 'activate']);
    Route::put('plans/inactivate/{uuid}', [PlanController::class, 'inactivate']);
    Route::get('plans/plan-permissions/{uuid}', [PlanController::class, 'planPermissions']);
    Route::post('plans/plan-permissions/attach', [PlanController::class, 'attachPermissions']);
    Route::post('plans/plan-permissions/detach', [PlanController::class, 'detachPermissions']);
    Route::apiResource('plans', PlanController::class);

    Route::any('roles/search', [RoleController::class, 'search']);
    Route::put('roles/activate/{uuid}', [RoleController::class, 'activate']);
    Route::put('roles/inactivate/{uuid}', [RoleController::class, 'inactivate']);
    Route::put('roles/deleted/{uuid}', [RoleController::class, 'deleted']);
    Route::put('roles/recover/{uuid}', [RoleController::class, 'recover']);
    Route::get('roles/role-permissions/{uuid}', [RoleController::class, 'rolePermissions']);
    Route::post('roles/role-permissions/attach', [RoleController::class, 'attachPermissions']);
    Route::post('roles/role-permissions/detach', [RoleController::class, 'detachPermissions']);
    Route::apiResource('roles', RoleController::class);

    Route::any('users/search', [UserController::class, 'search']);
    Route::put('users/activate/{uuid}', [UserController::class, 'activate']);
    Route::put('users/inactivate/{uuid}', [UserController::class, 'inactivate']);
    Route::put('users/deleted/{uuid}', [UserController::class, 'deleted']);
    Route::put('users/recover/{uuid}', [UserController::class, 'recover']);
    Route::apiResource('users', UserController::class);

});

Route::post('/auth', [AuthUserController::class, 'auth']);
Route::get('/auth/user-email-confirmation/{uuid}/{token}', [AuthUserController::class, 'emailConfirmation']);


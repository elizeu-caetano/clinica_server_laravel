<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Acl\{
    AuthUserController,
    PermissionController
};

Route::post('/auth', [AuthUserController::class, 'auth']);
Route::get('/auth/user-email-confirmation/{uuid}/{token}', [AuthUserController::class, 'emailConfirmation']);

Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::get('/authorized', [AuthUserController::class, 'authorized']);

    Route::post('/logout', [AuthUserController::class, 'logout']);

    Route::any('permissions/search', [PermissionController::class, 'search']);
    Route::apiResource('permissions', PermissionController::class);

    Route::prefix('contractors')->group(base_path('routes/api-contractors.php'));

    Route::prefix('plans')->group(base_path('routes/api-plans.php'));

    Route::prefix('roles')->group(base_path('routes/api-roles.php'));

    Route::prefix('users')->group(base_path('routes/api-users.php'));

});



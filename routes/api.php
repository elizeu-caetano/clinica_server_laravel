<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Acl\AuthUserController;
use App\Http\Controllers\Admin\CepController;
use App\Http\Controllers\Admin\SearchCompanyRfController;

Route::post('/auth', [AuthUserController::class, 'auth']);
Route::get('/auth/user-email-confirmation/{uuid}/{token}', [AuthUserController::class, 'emailConfirmation']);

Route::prefix('v1')->middleware('auth:api')->group(function () {

    Route::get('/authorized', [AuthUserController::class, 'authorized']);

    Route::post('/logout', [AuthUserController::class, 'logout']);

    Route::get('/search-company-cnpj/{cnpj}', [SearchCompanyRfController::class, 'getCompanyByCnpj']);

    Route::get('/search-address/{cep}', [CepController::class, 'getAddress']);

    Route::prefix('audits')->group(base_path('routes/api-audits.php'));

    Route::prefix('companies')->group(base_path('routes/api-companies.php'));

    Route::prefix('contractors')->group(base_path('routes/api-contractors.php'));

    Route::prefix('occupations')->group(base_path('routes/api-occupations.php'));

    Route::prefix('dangers')->group(base_path('routes/api-dangers.php'));

    Route::prefix('discount-tables')->group(base_path('routes/api-discount-tables.php'));

    Route::prefix('permissions')->group(base_path('routes/api-permissions.php'));

    Route::prefix('procedures')->group(base_path('routes/api-procedures.php'));

    Route::prefix('procedure-groups')->group(base_path('routes/api-procedure-groups.php'));

    Route::prefix('plans')->group(base_path('routes/api-plans.php'));

    Route::prefix('roles')->group(base_path('routes/api-roles.php'));

    Route::prefix('users')->group(base_path('routes/api-users.php'));

});



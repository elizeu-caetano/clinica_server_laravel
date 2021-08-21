<?php

use App\Http\Controllers\Admin\AuditController;
use Illuminate\Support\Facades\Route;

Route::any('search', [AuditController::class, 'search']);
Route::get('show/{uuid}', [AuditController::class, 'show']);

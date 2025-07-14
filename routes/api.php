<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

use App\Http\Controllers\API\AuditController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\ClientHoldingController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api', 'throttle:60,1')->group(function () {

    Route::middleware('permission:manage clients')->group(function () {
        Route::apiResource('clients', ClientController::class);
        Route::apiResource('client-holdings', ClientHoldingController::class);
    });
    
    Route::middleware('permission:manage holdings')->group(function () {
        Route::post('client-holdings/import', [ClientHoldingController::class, 'import']);
        Route::post('/client-holdings/update-prices', [ClientHoldingController::class, 'updatePrices']);
    });
    
    Route::middleware('permission:view reports')->group(function () {
        Route::get('/reports/client', [ReportController::class, 'clientReport']);
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel']);
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf']);
    });

    Route::get('/audit-logs', [AuditController::class, 'index'])->middleware('permission:view audits');
    Route::post('/send-greeting-emails', [NotificationController::class, 'sendGreetingEmails'])->middleware('permission:manage schedules');

    Route::post('/logout', [AuthController::class, 'logout']);
    
});
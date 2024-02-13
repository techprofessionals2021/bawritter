<?php

use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Route;

Route::prefix('install')->group(function () {
    Route::get('/', [SystemController::class, 'index']);

    Route::get('/system-check', [SystemController::class, 'index'])
        ->name('installer_page');

    Route::get('/database', [SystemController::class, 'database_information'])
        ->name('run_installation_step_2_page');

    Route::post('/database', [SystemController::class, 'setup_database_connection'])
        ->name('run_installation_step_2');

    Route::get('/database/connected', [SystemController::class, 'db_connected'])
        ->name('db_connected');

    Route::post('/run', [SystemController::class, 'run_page'])
        ->name('run_installation_step_4_page');

    Route::post('/setup/db', [SystemController::class, 'setup_database'])
        ->name('run_installation_step_4');

    Route::get('/status', [SystemController::class, 'installation_result'])
        ->name('installation_result');

    Route::get('/failed', [SystemController::class, 'installation_failed'])
        ->name('installation_failed');

    Route::get('/download/{path}', [SystemController::class, 'download'])
        ->name('download_error_log');
});
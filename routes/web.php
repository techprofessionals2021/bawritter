<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// load_route('installer');

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

load_route('website');





Route::post('additional/services', [ServiceController::class, 'getAdditionalServicesByServiceId'])
    ->name('additional_services_by_service_id');

Route::get('writer/apply', [ApplicantController::class, 'create'])
    ->name('writer_application_page');

Route::post('writer/apply', [ApplicantController::class, 'store'])
    ->name('store_writer_application');


Auth::routes(['verify' => true]);

// Authenticated Users
Route::group(['middleware' => ['auth','verified'] ], function () {

    // Generic Routes (Admin and Staffs, both)
    load_route('generic');

    // For Admin Only
    Route::group(['middleware' => ['role:admin']], function () {
        load_route('admin');

    });
    // End of Admin only

    // For Staff Only
    Route::group(['middleware' => ['role:staff']], function () {
        load_route('staff');
    });
    // End of Staff Only


    // Admin and staff
    Route::group(['middleware' => ['role:admin|staff']], function () {

        Route::get('tasks', [TaskController::class, 'index'])->name('tasks_list');
        Route::post('/tasks/datatable/', [TaskController::class, 'datatable'])->name('tasks_datatable');
        Route::post('task/submit/{order}', [TaskController::class, 'submit_work'])->name('submit_work');
        Route::post('task/start/{order}', [TaskController::class, 'start_working'])->name('start_working');

    });
    // End of Admin and staff



    
});

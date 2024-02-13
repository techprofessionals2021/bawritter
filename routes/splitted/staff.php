<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('browse-work')->group(function () {  

	Route::get('/', [TaskController::class,'browse_work'])
	->name('browse_work');

	Route::post('/', [TaskController::class,'datatable_browse_work'])
	->name('browse_work_datatable');

	Route::post('details/{order}', [TaskController::class,'self_assign_task'])
	->name('accept_work');

});


Route::prefix('payments/requests')->group(function () {  

	Route::get('/', [BillController::class,'my_requests_for_payment'])
	->name('my_requests_for_payment');

	Route::post('/', [BillController::class,'datatable_my_requests_for_payment'])
	->name('datatable_my_requests_for_payment');

	Route::get('make', [BillController::class,'create'])
	->name('request_for_payment');

	Route::post('make', [BillController::class,'store'])
	->name('post_request_for_payment');

	Route::get('/details/{bill}', [BillController::class,'show'])
	->name('my_requests_bills_show')
	->where('bill', '[0-9]+');

});
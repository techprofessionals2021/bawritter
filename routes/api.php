<?php

use App\Http\Controllers\Api\v1\AttachmentController;
use App\Http\Controllers\Api\v1\DashboardApiController;
use App\Http\Controllers\Api\v1\JobApplicantController;
use App\Http\Controllers\Api\v1\RegisterApiController;
use App\Http\Controllers\Api\v1\LoginApiController;
use App\Http\Controllers\Api\v1\OrderApiController;
use App\Http\Controllers\Api\v1\UserApiController;
use App\Http\Controllers\Api\v1\WalletApiController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//  AUTH API

Route::prefix('auth')->group(function () {
    Route::post('registerr', [RegisterApiController::class, 'register'])->name('register');
    Route::post('login',[LoginApiController::class, 'login'])->name('login');
    Route::post('forgot-password', [LoginApiController::class, 'forgotPassword'])->name('forget.password');
    Route::post('verify-otp',[LoginApiController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('password/reset', [LoginApiController::class, 'resetPassword'])->name('password.reset');
    Route::get('delete-user/{id}', [UserApiController::class, 'deleteUser'])->name('deleteUser');
});

// Route::get('order/create', [OrderApiController::class, 'create']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout',[LoginApiController::class,'logout'])->name('logout');





    Route::prefix('order')->group(function () {

        Route::get('create', [OrderApiController::class, 'create']);
        Route::post('store', [OrderApiController::class,'storeApiOrderInSession']);
        Route::get('datatable', [OrderApiController::class, 'datatable']);//only clients's order
        Route::get('status_count',[OrderApiController::class, 'index']);
        Route::get('search',[OrderApiController::class, 'search']);
        Route::get('detail/{id}',[OrderApiController::class,'show']);
        Route::post('attachment_download',[OrderApiController::class,'download']);
        Route::post('rating_store',[OrderApiController::class,'rating_store']);

        Route::post('comment', [OrderApiController::class, 'postComment']);

        Route::post('orders/{order}/status/change', [OrderApiController::class, 'change_status'])
        ->name('order_change_status');
   });



      // Handle File Uploads and Downloads
      Route::prefix('attachments')->group(function () {
        Route::get('download', [AttachmentController::class, 'download'])
            ->name('download_attachment');

        Route::post('upload', [AttachmentController::class, 'upload'])
            ->name('order_upload_attachment');

        Route::post('remove', [AttachmentController::class, 'remove']);
    });


});


// Handle File Uploads and Downloads
     Route::prefix('attachments')->group(function () {
         Route::get('download', [AttachmentController::class, 'download'])
             ->name('download_attachment');


         Route::post('upload', [AttachmentController::class, 'upload'])
             ->name('api.order_upload_attachment');

         Route::post('remove', [AttachmentController::class, 'remove']);
     });



//  Order API



 //  Dashboard Api

 Route::prefix('dashboard')->group(function () {

     Route::get('status_counts',[DashboardApiController::class, 'statistics']);
     Route::get('income_graph',[DashboardApiController::class, 'income_graph']);
     Route::get('activity_logs',[DashboardApiController::class,'index']);

 });

  //  User Api

 Route::prefix('user')->group(function(){

      Route::get('/',[UserApiController::class, 'paginate']);
      Route::post('send_invitation',[UserApiController::class, 'send_invitation']);
      Route::get('profile',[UserApiController::class, 'userProfile']);
      Route::post('update_profile',[UserApiController::class,'update_profile']);
      Route::post('update_password',[UserApiController::class,'change_password']);
  });


  //User's wallet

  Route::prefix('wallet')->group(function () {

         Route::get('current_balance',[WalletApiController::class,'index']);
         Route::get('payments',[WalletApiController::class,'walletPayments']);

       });

  //job applicant Api

  Route::post('job_applicant/store',[JobApplicantController::class, 'store']);

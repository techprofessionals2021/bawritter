<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\WalletTransactionController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class,'index'])
    ->name('dashboard');

Route::post('cart/add', [CartController::class,'storeOrderInSession'])
    ->name('add_to_cart');


// Handle File Uploads and Downloads
Route::prefix('attachments')->group(function () {
    Route::get('download', [AttachmentController::class, 'download'])
        ->name('download_attachment');

    Route::post('upload', [AttachmentController::class, 'upload'])
        ->name('order_upload_attachment');

    Route::delete('upload', [AttachmentController::class, 'remove']);
});

// Checkout
Route::prefix('checkout')->middleware(['check_cart'])->group(function () {
    Route::get('payment/method', [CheckoutController::class, 'choosePaymentMethod'])
        ->name('choose_payment_method');

    Route::get('payment/online/success', [CheckoutController::class, 'handleSuccessfullOnlinePayment'])
        ->name('handle_successful_online_payment');

    Route::get('payment/offline/{paymentMethod}', [CheckoutController::class, 'payUsingOfflineMethod'])
        ->name('pay_with_offline_method');

    Route::post('payment/offline/{paymentMethod}', [CheckoutController::class, 'processOfflinePayment'])
        ->name('process_pay_with_offline_method');

    Route::get('payment/offline/status/success', [CheckoutController::class, 'offlinePaymentSuccess'])
        ->name('offline_payment_success');

    Route::get('payment/wallet', [CheckoutController::class, 'processWalletPayment'])
        ->name('pay_with_wallet');

    load_route('payment_gateways');
});

// My Account
Route::prefix('my-account')->group(function () {
    Route::get('/', [MyAccountController::class, 'index'])
        ->name('my_account');

    Route::patch('/edit-profile', [MyAccountController::class, 'update_profile'])
        ->name('update_my_profile');

    Route::patch('/change-password', [MyAccountController::class, 'change_password'])
        ->name('change_password');

    Route::post('/change-photo', [MyAccountController::class, 'change_photo'])
        ->name('change_photo');

    Route::get('orders', [MyAccountController::class, 'orders'])
        ->name('my_orders');

    Route::post('orders', [MyAccountController::class, 'my_orders_datatable'])
        ->name('my_orders_datatable');

    Route::post('wallet/topup', [MyAccountController::class, 'walletTopup'])
        ->name('my_wallet_topup');

    Route::post('wallet/transactions', [WalletTransactionController::class, 'myWalletTransactionsDatatable'])
        ->name('my_wallet_transactions');

    Route::post('payments', [PaymentController::class, 'myPaymentsdatatable'])
        ->name('my_payments');
});

Route::prefix('orders')->group(function () {
    Route::get('create', [OrderController::class, 'create'])
        ->name('order_page');

    Route::post('comment', [OrderController::class, 'post_comment'])
        ->name('post_comment');

    Route::post('{order}/deliverable/accept', [OrderController::class, 'accept_delivered_item'])
        ->name('accept_delivered_item')
        ->where('order', '[0-9]+');

    Route::get('{order}/revision/request', [OrderController::class, 'revision_request_page'])
        ->name('revision_request_page');

    Route::post('{order}/revision/request', [OrderController::class, 'revision_request'])
        ->name('post_revision_request')
        ->where('order', '[0-9]+');

    Route::get('{order}', [OrderController::class, 'show'])
        ->name('orders_show')
        ->where('order', '[0-9]+');

    Route::get('{order}/rating', [RatingController::class, 'create'])
        ->name('orders_rating')
        ->where('order', '[0-9]+');

    Route::post('{order}/rating', [RatingController::class, 'store'])
        ->name('ratings_store')
        ->where('order', '[0-9]+');

    Route::get('{order}/pay', [CartController::class, 'makePaymentForExistingOrder'])
        ->name('pay_for_existing_order');
});

Route::prefix('notifications')->group(function () {
    Route::get('unread', [NotificationController::class, 'get_unread_notifications'])
        ->name('get_unread_notifications');

    Route::get('count', [NotificationController::class, 'push_notification'])
        ->name('get_push_notification_count');

    Route::get('/notifications/redirect/{id}', [NotificationController::class, 'redirect_url'])
        ->name('notification_redirect_url');

    Route::get('/all', [NotificationController::class, 'index'])
        ->name('notifications_index');

    Route::post('/all', [NotificationController::class, 'paginate'])
        ->name('datatable_notifications');

    Route::get('/mark/read/all', [NotificationController::class, 'mark_all_notification_as_read'])
        ->name('notification_all_mark_as_read');
});

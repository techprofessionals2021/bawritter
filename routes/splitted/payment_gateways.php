<?php

use App\PaymentGateways\braintree\BraintreeController;
use App\PaymentGateways\paypal_express\PaypalCheckoutController;
use App\PaymentGateways\paystack\PaystackController;
use App\PaymentGateways\payu\PayUController;
use App\PaymentGateways\stripe\StripeController;
use App\PaymentGateways\two_checkout\TwoCheckoutController;
use Illuminate\Support\Facades\Route;

Route::namespace('\App\PaymentGateways')->group(function () {

    // Controllers Within The "App\Http\Controllers\PaymentGateways" Namespace

    // Paypal Checkout
    Route::prefix('paypal/checkout')->group(function () {

        Route::get('/', [PaypalCheckoutController::class,'index'])
            ->name('paypal_checkout');

        Route::post('process', [PaypalCheckoutController::class,'capturePayment'])
            ->name('paypal_checkout_process');

        Route::post('generate/token', [PaypalCheckoutController::class,'generateToken'])
            ->name('paypal_checkout_generate_token');
    });

    // Stripe
    Route::prefix('stripe')->group(function () {

        Route::get('/',[StripeController::class,'index'])
            ->name('stripe');

        Route::post('process',[StripeController::class,'capturePayment'])
            ->name('stripe_process');
    });

    //Braintree
    Route::prefix('braintree')->group(function () {

        Route::get('/', [BraintreeController::class,'index'])
            ->name('braintree');

        Route::post('process', [BraintreeController::class,'capturePayment'])
            ->name('braintree_process');
    });


    //Paystack
    Route::prefix('paystack')->group(function () {

        Route::get('/', [PaystackController::class,'index'])
            ->name('paystack');

        Route::post('/verify', [PaystackController::class,'verifyPayment'])
            ->name('paystack_verify_payment');
    });


    //2Checkout
    Route::prefix('two_checkout')->group(function () {
        Route::get('/', [TwoCheckoutController::class, 'index'])
            ->name('two_checkout');
    
        Route::post('process', [TwoCheckoutController::class, 'capturePayment'])
            ->name('two_checkout_process');
    });

     //Paystack
     Route::prefix('payu')->group(function () {
        Route::get('/', [PayUController::class, 'index'])
            ->name('payu');
    
        Route::post('/verify', [PayUController::class, 'verifyPayment'])
            ->name('payu_verify_payment');
    
        Route::post('/payment-captured', [PayUController::class, 'paymentCaptured'])
            ->name('payu_payment_captured');
    });

});

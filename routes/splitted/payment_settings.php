<?php

use App\Http\Controllers\Payments\OfflinePaymentMethodController;
use App\Http\Controllers\SettingsController;
use App\PaymentGateways\braintree\BraintreeSettingsController;
use App\PaymentGateways\paypal_express\PaypalCheckoutSettingsController;
use App\PaymentGateways\paystack\PaystackSettingsController;
use App\PaymentGateways\payu\PayUSettingsController;
use App\PaymentGateways\stripe\StripeSettingsController;
use App\PaymentGateways\two_checkout\TwoCheckoutSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('payment/gateways', [SettingsController::class,'paymentGateways'])
->name('settings_payment_gateways');


Route::namespace('Payments')->group(function () {   

	// Controllers Within The "App\Http\Controllers\Payments" Namespace

    // Offline Payment Methods   
	Route::prefix('payment/offline/methods')->group(function () {			

		Route::get('/', [OfflinePaymentMethodController::class,'index'])
    	->name('offline_payment_methods');

		Route::post('/paginate', [OfflinePaymentMethodController::class,'datatable'])
			->name('datatable_offline_payment_methods');

		Route::get('/create', [OfflinePaymentMethodController::class,'create'])
			->name('offline_payment_method_create');

		Route::post('/create', [OfflinePaymentMethodController::class,'store'])
			->name('offline_payment_method_store');

		Route::get('/{method}/edit', [OfflinePaymentMethodController::class,'edit'])
			->name('offline_payment_method_edit');

		Route::patch('/{method}/edit', [OfflinePaymentMethodController::class,'update'])
			->name('offline_payment_method_update');

		Route::get('/{method}', [OfflinePaymentMethodController::class,'destroy'])
			->name('offline_payment_method_delete');
	});
	// End of Work Levels  	    
});



Route::namespace('\App\PaymentGateways')->group(function () {  

	// Controllers Within The "App\Http\Controllers\Payments\Gateways\Settings" Namespace

    Route::patch('stripe/configure', [StripeSettingsController::class,'updateSettings'])
        ->name('patch_settings_stripe');   

    Route::patch('braintree/configure', [BraintreeSettingsController::class,'updateSettings'])
        ->name('patch_settings_braintree');

    Route::patch('paypal/checkout/configure', [PaypalCheckoutSettingsController::class,'updateSettings'])
        ->name('patch_settings_paypal_checkout');
    
    Route::patch('paystack/configure', [PaystackSettingsController::class,'updateSettings'])
		->name('patch_settings_paystack'); 
		

	Route::patch('two_checkout/configure', [TwoCheckoutSettingsController::class,'updateSettings'])
		->name('patch_settings_two_checkout'); 
	
		Route::patch('payu/configure', [PayUSettingsController::class,'updateSettings'])
		->name('patch_settings_payu'); 
		
    
});
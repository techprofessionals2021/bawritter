<?php

use App\Http\Controllers\AdditionalServiceController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Payments\PendingPaymentsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UrgencyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletTransactionController;
use App\Http\Controllers\WorkLevelController;
use Illuminate\Support\Facades\Route;

Route::post('dashboard/statistics', [DashboardController::class, 'statistics'])
->name('dashboard_statistics');

Route::get('/orders', [OrderController::class, 'index'])
->name('orders_list');

Route::get('orders/{order}/follow', [OrderController::class, 'follow'])
->name('orders_follow');

Route::get('orders/{order}/unfollow', [OrderController::class, 'unfollow'])
->name('orders_unfollow');

Route::get('orders/{order}/archive', [OrderController::class, 'archive'])
->name('orders_archive');

Route::get('orders/{order}/unarchive', [OrderController::class, 'unarchive'])
->name('orders_unarchive');

Route::post('/datatable/orders', [OrderController::class, 'datatable'])
->name('orders_datatable');

Route::post('orders/{order}/status/change', [OrderController::class, 'change_status'])
->name('order_change_status');

Route::post('task/assign/{order}', [TaskController::class, 'assign_task'])
->name('assign_task');

Route::get('orders/{order}/destroy', [OrderController::class, 'destroy'])
->name('orders_destroy');


Route::get('/report/wallet/balance', [ReportController::class, 'totalWalletBlance'])
->name('total_wallet_balance');

Route::get('/report/statement/income', [ReportController::class, 'income_statement'])
->name('income_statement');

Route::post('/report/graph/income', [ReportController::class, 'income_graph'])
->name('income_graph');

Route::get('/activity/log', [ReportController::class, 'activity_log'])
->name('activity_log');

Route::post('/activity/log', [ReportController::class, 'datatable_activity_log'])
->name('datatable_activity_log');

Route::get('/activity/log/delete', [ReportController::class, 'destroy_activity'])
->name('activity_log_delete');


Route::prefix('users')->group(function () {

Route::get('/', [UserController::class, 'index'])
	->name('users_list');

Route::post('/paginate', [UserController::class, 'paginate'])
	->name('datatable_users');

Route::get('/invitation', [UserController::class, 'invitation'])
	->name('user_invitation');

Route::post('/invitation', [UserController::class, 'send_invitation'])
	->name('send_invitation');

Route::get('/{user}/edit', [UserController::class, 'edit'])
	->name('users_edit')
	->where('user', '[0-9]+');

Route::get('/{user}', [UserController::class, 'show'])
	->name('user_profile')
	->where('user', '[0-9]+');

Route::post('/{user}/photo/change', [UserController::class, 'change_photo'])
	->name('users_change_photo')
	->where('user', '[0-9]+');

Route::patch('/{user}', [UserController::class, 'update'])
	->name('users_update')
	->where('user', '[0-9]+');

Route::delete('/{user}/delete', [UserController::class, 'destroy'])
	->name('users_destroy')
	->where('user', '[0-9]+');
});

Route::prefix('bills')->group(function () {
    Route::get('/', [BillController::class, 'index'])
        ->name('bills_list');

    Route::post('/paginate', [BillController::class, 'datatable'])
        ->name('datatable_bills');

    Route::get('/{bill}', [BillController::class, 'show'])
        ->name('bills_show');

    Route::post('/{bill}/status/change/paid', [BillController::class, 'mark_as_paid'])
        ->name('bill_mark_as_paid');

    Route::post('/{bill}/status/change/unpaid', [BillController::class, 'mark_as_unpaid'])
        ->name('bill_mark_as_unpaid');
});

Route::prefix('settings')->group(function () {

	Route::get('/cache', [SettingsController::class, 'clear_cache_page'])
    ->name('clear_cache_page');

Route::post('/cache', [SettingsController::class, 'clear_cache'])
    ->name('post_clear_cache');

Route::get('/', [SettingsController::class, 'general_information'])
    ->name('settings_main_page');

Route::patch('/', [SettingsController::class, 'update_general_information'])
    ->name('patch_general_information');

Route::get('email', [SettingsController::class, 'email'])
    ->name('settings_email_page');

Route::patch('email/update', [SettingsController::class, 'update_email'])
    ->name('patch_settings_email');

Route::get('/email/test', [SettingsController::class, 'test_email'])
    ->name('send_test_email');

Route::post('/email/test', [SettingsController::class, 'send_test_email'])
    ->name('post_test_email');

Route::get('google-analytics', [SettingsController::class, 'google_analytics'])
    ->name('google_analytics');

Route::patch('google-analytics', [SettingsController::class, 'update_google_analytics'])
    ->name('patch_google_analytics');

Route::get('seo', [SettingsController::class, 'seo'])
    ->name('seo_page');

Route::patch('seo', [SettingsController::class, 'update_seo'])
    ->name('patch_seo');

Route::get('/logo', [SettingsController::class, 'logo_page'])
    ->name('settings_logo_page');

Route::post('/logo', [SettingsController::class, 'update_logo'])
    ->name('update_logo');

Route::get('content/{slug}', [SettingsController::class, 'content'])
    ->name('settings_edit_content');

Route::patch('content/{slug}', [SettingsController::class, 'update_content'])
    ->name('settings_update_content');

Route::get('/homepage', [SettingsController::class, 'homepage'])
    ->name('settings_homepage');

Route::patch('/homepage', [SettingsController::class, 'update_homepage'])
    ->name('patch_settings_homepage');

Route::get('/currency', [SettingsController::class, 'currency'])
    ->name('settings_currency_page');

Route::patch('/currency', [SettingsController::class, 'update_currency'])
    ->name('patch_settings_currency');

Route::get('/staff', [SettingsController::class, 'staff'])
    ->name('settings_staff_page');

Route::patch('/staff', [SettingsController::class, 'update_staff'])
    ->name('patch_settings_staff');

Route::get('/social-links', [SettingsController::class, 'social_links'])
    ->name('settings_social_links');

Route::patch('/social-links', [SettingsController::class, 'update_social_links'])
    ->name('patch_settings_social_links');

Route::get('custom-script', [SettingsController::class, 'website_custom_scripts'])
    ->name('custom_script_page');

Route::patch('custom-script', [SettingsController::class, 'update_website_custom_scripts'])
    ->name('patch_custom_script');

Route::get('recruitment', [SettingsController::class, 'recruitment'])
    ->name('settings_recruitment');

Route::patch('recruitment', [SettingsController::class, 'updateRecruitment'])
    ->name('patch_settings_recruitment');

	// Services    
	Route::prefix('services')->group(function () {
		Route::get('/', [ServiceController::class, 'index'])
			->name('services_list');
	
		Route::post('/paginate', [ServiceController::class, 'datatable'])
			->name('datatable_services');
	
		Route::get('/create', [ServiceController::class, 'create'])
			->name('services_create');
	
		Route::post('/', [ServiceController::class, 'store'])
			->name('services_store');
	
		Route::get('/{service}/edit', [ServiceController::class, 'edit'])
			->name('services_edit')
			->where('service', '[0-9]+');
	
		Route::patch('/{service}/edit', [ServiceController::class, 'update'])
			->name('services_update');
	
		Route::get('/{service}', [ServiceController::class, 'destroy'])
			->name('services_delete')
			->where('service', '[0-9]+');
	
		// Additional Services
		Route::prefix('additional')->group(function () {
			Route::get('/', [AdditionalServiceController::class, 'index'])
				->name('additional_services_list');
	
			Route::post('/paginate', [AdditionalServiceController::class, 'datatable'])
				->name('datatable_additional_services');
	
			Route::get('/create', [AdditionalServiceController::class, 'create'])
				->name('additional_services_create');
	
			Route::post('/', [AdditionalServiceController::class, 'store'])
				->name('additional_services_store');
	
			Route::get('/{additional_service}/edit', [AdditionalServiceController::class, 'edit'])
				->name('additional_services_edit');
	
			Route::patch('/{additional_service}/edit', [AdditionalServiceController::class, 'update'])
				->name('additional_services_update')
				->where('additional_service', '[0-9]+');
	
			Route::get('/{additional_service}', [AdditionalServiceController::class, 'destroy'])
				->name('additional_services_delete')
				->where('additional_service', '[0-9]+');
		});
		// End of Additional Services
	});
	// End of Services 


	// Urgencies    
	Route::prefix('urgencies')->group(function () {
		Route::get('/', [UrgencyController::class, 'index'])
			->name('urgencies_list');
	
		Route::post('/paginate', [UrgencyController::class, 'datatable'])
			->name('datatable_urgencies');
	
		Route::get('/create', [UrgencyController::class, 'create'])
			->name('urgencies_create');
	
		Route::post('/', [UrgencyController::class, 'store'])
			->name('urgencies_store');
	
		Route::get('/{urgency}/edit', [UrgencyController::class, 'edit'])
			->name('urgencies_edit');
	
		Route::patch('/{urgency}/edit', [UrgencyController::class, 'update'])
			->name('urgencies_update');
	
		Route::get('/{urgency}', [UrgencyController::class, 'destroy'])
			->name('urgencies_delete');
	});
	// End of Services 


	// Work Levels    
	Route::prefix('work-levels')->group(function () {
		Route::get('/', [WorkLevelController::class, 'index'])
			->name('work_levels_list');
	
		Route::post('/paginate', [WorkLevelController::class, 'datatable'])
			->name('datatable_work_levels');
	
		Route::get('/create', [WorkLevelController::class, 'create'])
			->name('work_levels_create');
	
		Route::post('/create', [WorkLevelController::class, 'store'])
			->name('work_levels_store');
	
		Route::get('/{work_level}/edit', [WorkLevelController::class, 'edit'])
			->name('work_levels_edit');
	
		Route::patch('/{work_level}/edit', [WorkLevelController::class, 'update'])
			->name('work_levels_update');
	
		Route::get('/{work_level}', [WorkLevelController::class, 'destroy'])
			->name('work_levels_delete');
	});
	// End of Work Levels  	


	// Tags
	Route::prefix('tags')->group(function () {
		Route::get('/', [TagController::class, 'index'])
			->name('tags_list');
	
		Route::post('/paginate', [TagController::class, 'datatable'])
			->name('datatables_tags');
	
		Route::get('/create', [TagController::class, 'create'])
			->name('tags_create');
	
		Route::post('/', [TagController::class, 'store'])
			->name('tags_store');
	
		Route::get('/{tag}/edit', [TagController::class, 'edit'])
			->name('tags_edit');
	
		Route::patch('/{tag}/edit', [TagController::class, 'update'])
			->name('tags_update');
	
		Route::get('/{tag}', [TagController::class, 'destroy'])
			->name('tags_delete');
	});

	Route::get('system/update', [SettingsController::class, 'updateSystemPage'])
    ->name('update_system_page');

	Route::post('system/update', [SettingsController::class, 'updateSystem'])
    ->name('update_system');

	load_route('payment_settings');
});


Route::prefix('payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])
        ->name('payments_list');

    Route::post('/', [PaymentController::class, 'datatable'])
        ->name('datatable_payments');

    Route::get('pending/approvals', [PendingPaymentsController::class, 'index'])
        ->name('pending_payment_approvals');

    Route::post('pending/approval/paginate', [PendingPaymentsController::class, 'datatable'])
        ->name('datatable_pending_payment_approval');

    Route::get('pending/approvals/{approvedPayment}/approve', [PendingPaymentsController::class, 'approvePendingPayment'])
        ->name('pending_payment_approve');

    Route::get('pending/approvals/{disapprovedPayment}/disapprove', [PendingPaymentsController::class, 'disapprovePendingPayment'])
        ->name('pending_payment_disapprove');
});

Route::prefix('wallet/transactions')->group(function () {
    Route::get('/', [WalletTransactionController::class, 'index'])
        ->name('wallet_transactions');

    Route::post('/', [WalletTransactionController::class, 'datatable'])
        ->name('datatable_wallet_transactions');

    Route::get('pending/approvals', [PendingPaymentsController::class, 'index'])
        ->name('pending_payment_approvals');

    Route::post('pending/approval/paginate', [PendingPaymentsController::class, 'datatable'])
        ->name('datatable_pending_payment_approval');

    Route::get('pending/approvals/{approvedPayment}/approve', [PendingPaymentsController::class, 'approvePendingPayment'])
        ->name('pending_payment_approve');

    Route::get('pending/approvals/{disapprovedPayment}/disapprove', [PendingPaymentsController::class, 'disapprovePendingPayment'])
        ->name('pending_payment_disapprove');
});


Route::prefix('job')->group(function () {
    Route::get('applicants', [ApplicantController::class, 'index'])
        ->name('job_applicants');

    Route::post('applicants', [ApplicantController::class, 'datatable'])
        ->name('datatable_job_applicants');

    Route::post('applicants/{applicant}/status/change', [ApplicantController::class, 'changeStatus'])
        ->name('applicant_change_status')
        ->where('applicant', '[0-9]+');

    Route::delete('applicants/{applicant}/delete', [ApplicantController::class, 'destroy'])
        ->name('applicant_delete')
        ->where('applicant', '[0-9]+');

    Route::post('applicants/{applicant}/invite', [ApplicantController::class, 'inviteToJoin'])
        ->name('applicant_invite_to_join')
        ->where('applicant', '[0-9]+');

    Route::get('applicants/{applicant}', [ApplicantController::class, 'show'])
        ->name('job_applicant_profile')
        ->where('applicant', '[0-9]+');
});

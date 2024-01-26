<?php
// if(!env('DISABLE_WEBSITE'))
// {
//     Route::get('/', 'HomeController@index')->name('homepage');
// }
// else
// {

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SitemapController;

Route::get('/', [LoginController::class,'showLoginForm'])->name('homepage');

Route::get('pricing', [HomeController::class,'pricing'])->name('pricing');
Route::get('contact', [HomeController::class,'contact'])->name('contact');
Route::post('contact', [HomeController::class,'handle_email_query'])->name('handle_email_query');
Route::get('instant-quote', [OrderController::class,'@quote'])->name('instant_quote');
Route::get('faq', [HomeController::class,'content'])->name('faq');
Route::get('how-it-works', [HomeController::class,'content'])->name('how_it_works');
Route::get('privacy-policy', [HomeController::class,'content'])->name('privacy_policy');
Route::get('revision-policy', [HomeController::class,'content'])->name('revision_policy');
Route::get('disclaimer', [HomeController::class,'content'])->name('disclaimer');
Route::get('terms-and-conditions', [HomeController::class,'content'])->name('terms_and_conditions');
Route::get('money-back-guarantee', [HomeController::class,'content'])->name('money_back_guarantee');
Route::get('sitemap.xml', [SitemapController::class,'index'])->name('sitemap.xml');
Route::get('page-sitemap.xml',[SitemapController::class,'page'])->name('page-sitemap.xml');


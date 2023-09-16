<?php

use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => 'user.status'], function () {
    Auth::routes(['verify' => true]);
    Route::get('cookie/accept', 'GlobalController@cookie')->middleware('ajax.only');
    Route::get('popup/close', 'GlobalController@popup')->middleware('ajax.only');
    Route::namespace('Auth')->group(function () {
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login');
        Route::post('logout', 'LoginController@logout')->name('logout');
        Route::middleware(['registration.action'])->group(function () {
            Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
            Route::post('register', 'RegisterController@register');
        });
        Route::name('oauth.')->prefix('oauth')->group(function () {
            Route::get('{provider}', 'OAuthController@redirectToProvider')->name('login');
            Route::get('{provider}/callback', 'OAuthController@handleProviderCallback')->name('callback');
            Route::middleware(['auth'])->group(function () {
                Route::get('data/complete', 'OAuthController@showCompleteForm');
                Route::post('data/complete', 'OAuthController@complete')->name('data.complete');
            });
        });
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
        Route::middleware('oauth.complete')->group(function () {
            Route::get('email/verify', 'VerificationController@show')->name('verification.notice');
            Route::post('email/verify/email/change', 'VerificationController@changeEmail')->name('change.email');
            Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
            Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');
            Route::middleware(['auth', 'verified'])->group(function () {
                Route::get('2fa/verify', 'TwoFactorController@show2FaVerifyForm');
                Route::post('2fa/verify', 'TwoFactorController@verify2fa')->name('2fa.verify');
            });
        });
    });
    Route::prefix('user')->namespace('User')->middleware(['auth', 'oauth.complete', 'verified', '2fa.verify', 'role:user'])->group(function () {
        Route::get('/', function () {
            return redirect()->route('user.tickets.index');
        })->name('user');
        Route::name('user.')->group(function () {
            Route::name('tickets.')->prefix('tickets')->group(function () {
                Route::get('/', 'TicketController@index')->name('index');
                Route::get('create', 'TicketController@create')->name('create');
                Route::post('create', 'TicketController@store')->name('store');
                Route::get('{id}', 'TicketController@show')->name('show');
                Route::post('{id}', 'TicketController@reply')->name('reply');
                Route::get('{id}/{attachment_id}/download', 'TicketController@download')->name('download');
            });
            Route::name('settings.')->prefix('settings')->middleware('demo')->group(function () {
                Route::get('/', 'SettingsController@index')->name('index');
                Route::post('details', 'SettingsController@detailsUpdate')->name('details');
                Route::post('password', 'SettingsController@passwordUpdate')->name('password');
                Route::post('2fa/enable', 'SettingsController@towFactorEnable')->name('2fa.enable');
                Route::post('2fa/disabled', 'SettingsController@towFactorDisable')->name('2fa.disable');
            });
            Route::name('notifications.')->prefix('notifications')->group(function () {
                Route::get('/', 'NotificationController@index')->name('index');
                Route::get('view/{id}', 'NotificationController@view')->name('view');
                Route::get('read-all', 'NotificationController@readAll')->name('read.all');
                Route::delete('delete', 'NotificationController@destroyAll')->name('destroy.all');
            });
        });
    });
    Route::middleware(['oauth.complete', 'verified', '2fa.verify'])->group(function () {
        Route::get('/', 'HomeController@index')->name('home')->middleware('home.action');
        Route::middleware('knowledgebase.action')->group(function () {
            Route::name('knowledgebase.')->prefix('knowledgebase')->group(function () {
                Route::get('/', 'KnowledgeBaseController@index')->name('index');
                Route::post('search', 'KnowledgeBaseController@search')->name('search');
                Route::get('search', 'KnowledgeBaseController@searchPage')->name('search.page');
                Route::get('categories', 'KnowledgeBaseController@categories');
                Route::get('categories/{slug}', 'KnowledgeBaseController@category')->name('category');
                Route::get('articles', 'KnowledgeBaseController@articles');
                Route::get('articles/{slug}', 'KnowledgeBaseController@article')->name('article');
                Route::post('articles/{slug}', 'KnowledgeBaseController@react');
            });
        });
        Route::get('{slug}', 'GlobalController@page')->name('page');
        Route::post('{slug}', 'GlobalController@sendMessage')->name('page.contact.send');
    });
});

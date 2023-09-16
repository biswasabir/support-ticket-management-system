<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Agent Routes
|--------------------------------------------------------------------------
|
| Here is where you can register agent routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::middleware(['demo', 'auth', 'role:agent', '2fa.verify'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('agent.tickets.index');
    })->name('agent');
    Route::name('agent.')->group(function () {
        Route::name('tickets.')->prefix('tickets')->group(function () {
            Route::get('/', 'TicketController@index')->name('index');
            Route::get('{id}', 'TicketController@show')->name('show');
            Route::post('{id}', 'TicketController@reply')->name('reply');
            Route::post('{id}/close', 'TicketController@close')->name('close');
            Route::post('{id}/transfer', 'TicketController@transfer')->name('transfer');
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

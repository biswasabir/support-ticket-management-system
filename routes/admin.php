<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::middleware(['demo', 'auth', 'role:admin', '2fa.verify'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('admin');
    Route::name('admin.')->group(function () {
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', 'DashboardController@index')->name('dashboard');
        });
        Route::name('members.')->prefix('members')->namespace('Members')->group(function () {
            Route::post('users/{id}/sendmail', 'UserController@sendMail')->name('users.sendmail');
            Route::resource('users', 'UserController')->except(['show']);
            Route::resource('agents', 'AgentController')->except(['show']);
            Route::resource('admins', 'AdminController')->except(['show']);
        });
        Route::name('tickets.')->prefix('tickets')->group(function () {
            Route::get('/', 'TicketController@index')->name('index');
            Route::get('create', 'TicketController@create')->name('create');
            Route::post('create', 'TicketController@store')->name('store');
            Route::get('{ticket}', 'TicketController@show')->name('show');
            Route::post('{ticket}', 'TicketController@reply')->name('reply');
            Route::post('{ticket}/close', 'TicketController@close')->name('close');
            Route::post('{ticket}/transfer', 'TicketController@transfer')->name('transfer');
            Route::delete('{ticket}/delete', 'TicketController@destroy')->name('destroy');
            Route::get('{ticket}/{attachment}/download', 'TicketController@download')->name('download');
        });
        Route::resource('departments', 'DepartmentController')->except(['show']);;
        Route::prefix('navigation')->namespace('Navigation')->group(function () {
            Route::post('navbar-menu/nestable', 'NavbarMenuController@nestable')->name('navbar-menu.nestable');
            Route::resource('navbar-menu', 'NavbarMenuController');
            Route::post('footer-menu/sort', 'FooterMenuController@sort')->name('footer-menu.sort');
            Route::resource('footer-menu', 'FooterMenuController');
        });
        Route::name('knowledgebase.')->prefix('knowledgebase')->namespace('KnowledgeBase')->middleware('knowledgebase.action')->group(function () {
            Route::get('categories/slug', 'CategoryController@slug')->name('categories.slug');
            Route::resource('categories', 'CategoryController')->except(['show']);
            Route::get('articles/slug', 'ArticleController@slug')->name('articles.slug');
            Route::resource('articles', 'ArticleController')->except(['show']);
        });
        Route::name('settings.')->prefix('settings')->namespace('Settings')->group(function () {
            Route::get('general', 'GeneralController@index')->name('general');
            Route::post('general/update', 'GeneralController@update')->name('general.update');
            Route::name('storage.')->prefix('storage')->group(function () {
                Route::get('/', 'StorageController@index')->name('index');
                Route::get('edit/{storageProvider}', 'StorageController@edit')->name('edit');
                Route::post('edit/{storageProvider}', 'StorageController@update')->name('update');
                Route::post('connect/{storageProvider}', 'StorageController@storageTest')->name('test');
                Route::post('default/{storageProvider}', 'StorageController@setDefault')->name('default');
            });
            Route::name('oauth-providers.')->prefix('oauth-providers')->group(function () {
                Route::get('/', 'OAuthProviderController@index')->name('index');
                Route::get('{oauthProvider}/edit', 'OAuthProviderController@edit')->name('edit');
                Route::post('{oauthProvider}', 'OAuthProviderController@update')->name('update');
            });
            Route::name('smtp.')->prefix('smtp')->group(function () {
                Route::get('/', 'SmtpController@index')->name('index');
                Route::post('update', 'SmtpController@update')->name('update');
                Route::post('test', 'SmtpController@test')->name('test');
            });
            Route::get('pages/slug', 'PageController@slug')->name('pages.slug');
            Route::resource('pages', 'PageController')->except(['show']);
            Route::name('extensions.')->prefix('extensions')->group(function () {
                Route::get('/', 'ExtensionController@index')->name('index');
                Route::get('{extension}/edit', 'ExtensionController@edit')->name('edit');
                Route::post('{extension}', 'ExtensionController@update')->name('update');
            });
            Route::name('translates.')->prefix('translates')->group(function () {
                Route::get('/', 'TranslateController@index')->name('index');
                Route::post('/', 'TranslateController@update')->name('update');
                Route::get('{group}', 'TranslateController@index')->name('group');
            });
            Route::name('mail-templates.')->prefix('mail-templates')->group(function () {
                Route::get('/', 'MailTemplateController@index')->name('index');
                Route::get('{mailTemplate}/edit', 'MailTemplateController@edit')->name('edit');
                Route::post('{mailTemplate}', 'MailTemplateController@update')->name('update');
            });
        });
        Route::name('extra.')->prefix('extra')->namespace('Extra')->group(function () {
            Route::get('custom-css', 'CustomCssController@index');
            Route::post('custom-css', 'CustomCssController@update')->name('css');
            Route::get('popup-notice', 'PopupNoticeController@index');
            Route::post('popup-notice', 'PopupNoticeController@update')->name('notice');
        });
        Route::name('system.')->namespace('System')->prefix('system')->group(function () {
            Route::get('info', 'InfoController@index')->name('info.index');
            Route::get('info/cache', 'InfoController@cache')->name('info.cache');
            Route::resource('plugins', 'PluginController')->except(['create', 'show']);
            Route::get('editor-files', 'EditorFileController@index')->name('editor-files.index');
            Route::post('editor-files/upload', 'EditorFileController@upload');
            Route::delete('editor-files/{editorFile}', 'EditorFileController@destroy')->name('editor-files.destroy');
            Route::get('panel-style', 'PanelStyleController@index');
            Route::post('panel-style', 'PanelStyleController@update')->name('panel-style');
        });
        Route::name('account.')->prefix('account')->group(function () {
            Route::get('/', 'AccountController@index')->name('index');
            Route::post('details', 'AccountController@updateDetails')->name('details');
            Route::post('password', 'AccountController@updatePassword')->name('password');
            Route::post('2fa/enable', 'AccountController@enable2FA')->name('2fa.enable');
            Route::post('2fa/disable', 'AccountController@disable2FA')->name('2fa.disable');
        });
        Route::name('notifications.')->prefix('notifications')->group(function () {
            Route::get('/', 'NotificationController@index')->name('index');
            Route::get('view/{id}', 'NotificationController@view')->name('view');
            Route::get('read-all', 'NotificationController@readAll')->name('read.all');
            Route::delete('delete', 'NotificationController@destroyAll')->name('destroy.all');
        });
    });
});

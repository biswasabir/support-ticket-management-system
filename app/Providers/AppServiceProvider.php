<?php

namespace App\Providers;

use App\Blade\Directives;
use App\Models\Category;
use App\Models\FooterMenu;
use App\Models\NavbarMenu;
use App\Models\Notification;
use App\Rules\BlockPatterns;
use Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        Directives::render();
    }

    public function boot()
    {
        $this->validationExtends();

        Paginator::useBootstrap();

        if (systemInfo()->status) {

            if (settings('actions')->force_ssl_status) {
                $this->app['request']->server->set('HTTPS', true);
            }

            view()->composer('*', function ($view) {
                $view->with(['settings' => settings()]);
            });

            view()->composer(['user.includes.navbar', 'agent.includes.navbar', 'admin.includes.navbar'], function ($view) {
                $notifications['list'] = Notification::where('user_id', Auth::user()->id)->orderbyDesc('id')->limit(20)->get();
                $notifications['unread'] = Notification::where('user_id', Auth::user()->id)->unread()->get()->count();
                $view->with('notifications', $notifications);
            });

            if (request()->segment(1) != "admin" && request()->segment(1) != "agent") {

                view()->composer(['includes.navbar', 'layouts.docs'], function ($view) {
                    $navbarMenuLinks = NavbarMenu::whereNull('parent_id')
                        ->with(['children' => function ($query) {
                            $query->byOrder();
                        }])->byOrder()->get();
                    $view->with('navbarMenuLinks', $navbarMenuLinks);
                });

                view()->composer('layouts.docs', function ($view) {
                    $categories = Category::with('articles')->get();
                    $view->with('categories', $categories);
                });

                view()->composer('includes.footer', function ($view) {
                    $footerMenuLinks = FooterMenu::orderBy('sort_id', 'asc')->get();
                    $view->with('footerMenuLinks', $footerMenuLinks);
                });

            }

        }
    }

    public function validationExtends()
    {
        Validator::extend('block_patterns', function ($attribute, $value, $parameters, $validator) {
            $rule = new BlockPatterns;
            return $rule->passes($attribute, $value);
        });
    }
}
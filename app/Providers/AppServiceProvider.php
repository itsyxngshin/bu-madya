<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // This makes $visitorCount available to ALL views (or you can specify 'partials.footer')
        View::composer('*', function ($view) {
            $view->with('visitorCount', Cache::get('global_visitor_count', 0));
        });
    }
}

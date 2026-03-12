<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        Blade::if('role', function (...$roles) {
            if (!auth()->check()) {
                return false;
            }
            // If the first argument is an array, use it directly (e.g. @role(['admin', 'editor']))
            if (isset($roles[0]) && is_array($roles[0])) {
                return auth()->user()->hasRole($roles[0]);
            }
            // Otherwise treat arguments as the list of roles (e.g. @role('admin', 'editor'))
            return auth()->user()->hasRole($roles);
        });
    }
}

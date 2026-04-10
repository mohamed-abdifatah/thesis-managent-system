<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        $this->ensureAppKey();
        Schema::defaultStringLength(191);

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

    private function ensureAppKey(): void
    {
        if (config('app.key')) {
            return;
        }

        $key = 'base64:' . base64_encode(random_bytes(32));
        $envPath = base_path('.env');
        if (is_file($envPath) && is_writable($envPath)) {
            $contents = file_get_contents($envPath) ?: '';

            if (preg_match('/^APP_KEY=.*/m', $contents)) {
                $contents = preg_replace('/^APP_KEY=.*/m', 'APP_KEY=' . $key, $contents);
            } else {
                $contents .= "\nAPP_KEY=" . $key;
            }

            file_put_contents($envPath, $contents);
        }

        config(['app.key' => $key]);
    }
}

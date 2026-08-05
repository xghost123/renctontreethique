<?php

namespace App\Providers;

use App\Encryption\NoOpEncrypter;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Use custom encrypter that handles missing OpenSSL gracefully
        if (!extension_loaded('openssl')) {
            $this->app->singleton('encrypter', function ($app) {
                return new NoOpEncrypter($app['config']['app']['key']);
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Disable CSRF validation for specific routes
        ValidateCsrfToken::except(['mails/proposals']);
        Vite::prefetch(concurrency: 3);
    }
}

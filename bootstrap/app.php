<?php

// Disable encryption if OpenSSL is not loaded
if (!extension_loaded('openssl')) {
    putenv('APP_CIPHER=');
}

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Auth\AdminAuthenticate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            require __DIR__.'/../routes/mosque.php';
            require __DIR__.'/../routes/admin.php';
            require __DIR__.'/../routes/panel.php';
            require __DIR__.'/../routes/members.php';
            require __DIR__.'/../routes/app.php';
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // AdminAuthenticate removed - use it only on specific routes that need it
        // $middleware->web(append: [
        //     AdminAuthenticate::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

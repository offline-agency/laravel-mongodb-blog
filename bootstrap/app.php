<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Same behaviour as the old App\Http\Middleware\RedirectIfAuthenticated.
        $middleware->redirectUsersTo('/home');

        // Same behaviour as the old "api" group ('throttle:60,1').
        $middleware->appendToGroup('api', 'throttle:60,1');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

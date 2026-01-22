<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Force canonical host FIRST (before any other redirects)
        $middleware->web(prepend: [
            \App\Http\Middleware\ForceCanonicalHost::class,
        ]);
        // Then handle URL normalization (trailing slash, lowercase, legacy URLs)
        $middleware->web(append: [
            \App\Http\Middleware\CanonicalRedirect::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


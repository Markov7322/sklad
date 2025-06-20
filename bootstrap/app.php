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
        $middleware->alias([
            'auth' => Illuminate\Auth\Middleware\Authenticate::class,
            'role' => App\Http\Middleware\RoleMiddleware::class,
        ]);
        $middleware->prependToGroup('web', App\Http\Middleware\PreloadImage::class);
        $middleware->appendToGroup('web', Spatie\ResponseCache\Middlewares\CacheResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

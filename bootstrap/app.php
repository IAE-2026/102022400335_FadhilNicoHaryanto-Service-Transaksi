<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'apikey' => \App\Http\Middleware\CheckApiKey::class,
<<<<<<< HEAD
            'sso' => \App\Http\Middleware\FederatedSsoMiddleware::class,
=======
>>>>>>> 2d3a04638b2499e38ca6897529c1c4a8fa88b97a
        ]);
    }) 
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use App\Http\Middleware\EnsureAccountIsActive;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
            'role' => RoleMiddleware::class,
            'ibalong.auth' => \App\Http\Middleware\IbalongAuth::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\EnsureAccountIsActive::class, // <--- Add this line
        ]);
        $middleware->alias([
            'ibalong.auth' => \App\Http\Middleware\IbalongAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

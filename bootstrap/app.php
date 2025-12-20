<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsTechnicien;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        // api: __DIR__ . '/../routes/api.php', // décommente si tu as des routes API
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias de middlewares custom
        $middleware->alias([
            'is_admin'      => IsAdmin::class,
            'is_technicien' => IsTechnicien::class,
        ]);
    })
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\EventServiceProvider::class, // envoi du lien de vérification après inscription
        // 🚫 Ne pas ajouter App\Providers\RouteServiceProvider::class en Laravel 11
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

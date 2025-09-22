<?php

use App\Http\Middleware\AddAcceptTypeJson;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use KeycloakGuard\Exceptions\TokenException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        apiPrefix: '/api/v1',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(AddAcceptTypeJson::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (TokenException $e) {

            return response()->json([
                'message' => 'Expired token',
            ], 401);
        });
    })->create();

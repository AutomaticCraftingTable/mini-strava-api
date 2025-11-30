<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Exception $e, Request $request) {
            function get_status_code(Exception $e)
            {
                if (method_exists($e, 'getStatusCode')) {
                    return $e->getStatusCode();
                }
                return 500;
            }
            return response()->json([
                'status_code' => get_status_code($e),
                'message' => $e->getMessage() ?: 'Not Found',
            ], get_status_code($e));
        });
    })->create();

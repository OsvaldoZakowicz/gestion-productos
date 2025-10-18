<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Manejador global de errores no esperados
        $exceptions->render(function (Throwable $e, Request $request) {
            // Registrar el error completo para los desarrolladores
            Log::error($e);

            // Mostrar mensaje genérico al usuario
            return redirect()
                ->route('/')
                ->with('exception', 'Ocurrió un error inesperado. El equipo técnico fue notificado.');
        });
    })->create();

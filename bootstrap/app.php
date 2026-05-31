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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Unauthenticated visitors to the admin area go to the admin login;
        // everyone else (community member routes) goes to the member login.
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            return $request->is('admincpanel/*') ? route('login') : route('public.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

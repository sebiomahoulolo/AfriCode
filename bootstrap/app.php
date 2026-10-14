<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Hostinger sert www.africode.tech via son propre CDN (proxy) — sans ceci,
        // Laravel peut mal détecter le schéma https derrière le proxy.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'formateur' => \App\Http\Middleware\FormateurMiddleware::class,
            'apprenant' => \App\Http\Middleware\ApprenantMiddleware::class,
            'profile.completed' => \App\Http\Middleware\ProfileCompletedMiddleware::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'mobile.redirect.cours' => \App\Http\Middleware\RedirectMobileToCours::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ModelNotFoundException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Ressource introuvable.'], 404);
            }
        });

        $exceptions->render(function (HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 403 && $request->expectsJson()) {
                return response()->json(['error' => 'Accès non autorisé.'], 403);
            }
        });

        // Session expirée sur logout → rediriger vers login silencieusement
        $exceptions->render(function (
            \Illuminate\Session\TokenMismatchException $e,
            \Illuminate\Http\Request $request
        ) {
            if ($request->is('logout') || $request->routeIs('logout')) {
                return redirect()->route('login');
            }
            // Pour les autres pages : retour arrière avec message
            return redirect()->back()
                ->withInput($request->except('_token', 'password', 'password_confirmation'))
                ->withErrors(['_token' => 'Votre session a expiré. Veuillez réessayer.']);
        });
    })->create();

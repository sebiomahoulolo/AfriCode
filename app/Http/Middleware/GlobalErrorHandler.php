<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class GlobalErrorHandler
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (\Exception $e) {
            // Log l'erreur avec le contexte
            Log::error('Erreur globale non gérée', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'trace' => $e->getTraceAsString()
            ]);

            // Si c'est une requête API, retourner une réponse JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'Une erreur interne est survenue',
                    'message' => app()->environment('local') ? $e->getMessage() : 'Erreur interne du serveur'
                ], 500);
            }

            // Pour les requêtes web, rediriger avec un message d'erreur
            return redirect()->back()
                ->with('error', 'Une erreur inattendue est survenue. Veuillez réessayer.')
                ->withInput();
        }
    }
}

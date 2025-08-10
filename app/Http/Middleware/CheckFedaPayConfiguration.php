<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFedaPayConfiguration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier que FedaPay est correctement configuré
        $apiKey = config('services.fadapay.api_key');
        $publicKey = config('services.fadapay.public_key');

        if (!$apiKey || !$publicKey) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'FedaPay non configuré. Veuillez contacter l\'administrateur.'
                ], 503);
            }

            return redirect()->back()->with('error', 'Service de paiement temporairement indisponible.');
        }

        return $next($request);
    }
}

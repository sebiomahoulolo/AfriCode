<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
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
        // Récupérer la locale depuis la session ou utiliser la locale par défaut
        $locale = Session::get('locale', config('app.locale'));
        
        // Vérifier si la locale est valide
        if (!in_array($locale, ['en', 'fr', 'de', 'es', 'ja'])) {
            $locale = config('app.locale');
        }
        
        // Définir la locale pour l'application
        App::setLocale($locale);
        
        return $next($request);
    }
} 
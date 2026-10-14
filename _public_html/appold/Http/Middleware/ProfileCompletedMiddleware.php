<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCompletedMiddleware
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
        if (Auth::check()) {
            $user = Auth::user();
            
            // Vérifiez si l'utilisateur a besoin de compléter son profil
            // Par exemple, s'il s'est inscrit via OAuth et n'a pas choisi de rôle spécifique
            if (empty($user->role) || $user->role === 'undefined') {
                return redirect()->route('profile.complete');
            }
        }

        return $next($request);
    }
}

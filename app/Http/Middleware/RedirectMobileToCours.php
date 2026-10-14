<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sur mobile uniquement : rediriger la page d'accueil (/) vers /cours (liste des cours).
 * Les utilisateurs desktop gardent l'index comme page principale.
 */
class RedirectMobileToCours
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->is('/') || !$request->isMethod('GET')) {
            return $next($request);
        }

        if (!$this->isMobile($request)) {
            return $next($request);
        }

        return redirect()->route('cours.index');
    }

    private function isMobile(Request $request): bool
    {
        $ua = $request->userAgent() ?? '';

        $mobileKeywords = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'iPod', 'webOS', 'BlackBerry',
            'IEMobile', 'Opera Mini', 'Opera Mobi', 'Windows Phone', 'Symbian',
            'Kindle', 'Silk', 'UCBrowser', 'SamsungBrowser', 'MiuiBrowser',
        ];

        foreach ($mobileKeywords as $keyword) {
            if (stripos($ua, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}

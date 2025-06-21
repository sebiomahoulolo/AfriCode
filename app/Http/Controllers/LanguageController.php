<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function switchLang($locale)
    {
        // Liste des langues supportées
        $supported = ['en', 'fr', 'de', 'es', 'ja'];
        
        if (!in_array($locale, $supported)) {
            $locale = config('app.locale');
        }
        
        // Stocker la locale dans la session
        Session::put('locale', $locale);
        
        // Définir la locale pour l'application
        App::setLocale($locale);
        
        return redirect()->back();
    }
} 
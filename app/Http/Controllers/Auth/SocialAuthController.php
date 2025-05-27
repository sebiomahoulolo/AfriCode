<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirige l'utilisateur vers le fournisseur OAuth.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtient les informations de l'utilisateur depuis le fournisseur OAuth.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Vérifier si l'utilisateur existe déjà avec cet email
            $existingUser = User::where('email', $socialUser->getEmail())->first();
            
            if ($existingUser) {
                // Connecter l'utilisateur existant
                Auth::login($existingUser);
                return redirect()->route('dashboard');
            }
            
            // Déterminer le rôle par défaut (les utilisateurs devront le changer plus tard si nécessaire)
            $defaultRole = 'apprenant';
            
            // Créer un nouvel utilisateur
            $nameParts = explode(' ', $socialUser->getName());
            $firstName = $nameParts[0] ?? '';
            $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';
            
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $socialUser->getEmail(),
                'role' => $defaultRole,
                'is_active' => true,
                'email_verified_at' => now(), // Les emails OAuth sont déjà vérifiés
                'password' => Hash::make(rand(1000000, 9999999)), // Mot de passe aléatoire
                'profile_image_path' => $socialUser->getAvatar(),
            ]);
            
            Auth::login($user);
            return redirect()->route('dashboard');
            
        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Une erreur s\'est produite lors de la connexion via ' . ucfirst($provider) . ': ' . $e->getMessage());
        }
    }
}

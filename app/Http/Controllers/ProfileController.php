<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->role === 'administrateur' || $user->role === 'admin';
        $isFormateur = $user->role === 'formateur';
        
        // Utiliser des vues séparées pour éviter les conflits et duplications
        if ($isAdmin) {
            return view('profile.edit-admin', [
                'user' => $user,
            ]);
        } elseif ($isFormateur) {
            return view('profile.edit-formateur', [
                'user' => $user,
            ]);
        }
        
        // Vue par défaut pour les autres utilisateurs
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Pour les formateurs : vérifier si l'onboarding peut être marqué comme complété
        if ($user->role === 'formateur' && !$user->onboarding_completed) {
            $this->checkAndCompleteOnboarding($user);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Afficher le profil public d'un utilisateur
     */
    public function show($id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        // Si l'utilisateur connecté consulte son propre profil, rediriger vers la page d'édition
        if (Auth::check() && Auth::id() == $user->id) {
            return redirect()->route('profile.edit');
        }
        
        // Afficher le profil public
        return view('profile.show', compact('user'));
    }

    /**
     * Vérifier et marquer l'onboarding comme complété si toutes les informations sont remplies
     */
    private function checkAndCompleteOnboarding($user)
    {
        // Vérifier que toutes les informations requises sont remplies
        $requiredFields = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'bio',
            'city',
            'country',
            'payment_method',
            'payment_account'
        ];

        $allFieldsFilled = true;
        foreach ($requiredFields as $field) {
            if (empty($user->$field)) {
                $allFieldsFilled = false;
                break;
            }
        }

        // Si toutes les informations sont remplies, marquer l'onboarding comme complété
        if ($allFieldsFilled) {
            $user->onboarding_completed = true;
            $user->onboarding_completed_at = now();
            $user->save();
        }
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $request->validate([
            'push_new_courses'  => 'nullable|in:0,1',
            'push_reminders'    => 'nullable|in:0,1',
            'push_updates'      => 'nullable|in:0,1',
            'email_new_courses' => 'nullable|in:0,1',
            'email_newsletter'  => 'nullable|in:0,1',
        ]);

        $request->user()->update([
            'notification_preferences' => [
                'push_new_courses'  => (bool)$request->input('push_new_courses', 0),
                'push_reminders'    => (bool)$request->input('push_reminders', 0),
                'push_updates'      => (bool)$request->input('push_updates', 0),
                'email_new_courses' => (bool)$request->input('email_new_courses', 0),
                'email_newsletter'  => (bool)$request->input('email_newsletter', 0),
            ],
        ]);

        return Redirect::route('profile.edit')->with('notif_success', 'Préférences enregistrées.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

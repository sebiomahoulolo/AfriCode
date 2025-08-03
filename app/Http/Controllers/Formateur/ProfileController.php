<?php

namespace App\Http\Controllers\Formateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Afficher le formulaire de profil du formateur
     */
    public function edit(): View
    {
        return view('formateurs.profile.edit');
    }

    /**
     * Mettre à jour les informations du profil
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Gestion de l'upload de l'image de profil
        if ($request->hasFile('profile_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($user->profile_image_path && Storage::disk('public')->exists(str_replace('storage/', '', $user->profile_image_path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->profile_image_path));
            }

            $image = $request->file('profile_image');
            $imageName = time() . '_' . Str::slug($user->first_name . '-' . $user->last_name) . '.' . $image->extension();
            $image->move(public_path('storage/profiles'), $imageName);
            $validated['profile_image_path'] = 'storage/profiles/' . $imageName;
        }

        // Mettre à jour les informations
        $user->fill($validated);

        // Si l'email a changé, réinitialiser la vérification
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('formateur.profile.edit')->with('profile-updated', true);
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('formateur.profile.edit')->with('password-updated', true);
    }

    /**
     * Supprimer le compte du formateur
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Supprimer l'image de profil si elle existe
        if ($user->profile_image_path && Storage::disk('public')->exists(str_replace('storage/', '', $user->profile_image_path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $user->profile_image_path));
        }

        Auth::logout();

        // Soft delete de l'utilisateur (garde les données pour l'intégrité référentielle)
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Votre compte a été supprimé avec succès.');
    }
}

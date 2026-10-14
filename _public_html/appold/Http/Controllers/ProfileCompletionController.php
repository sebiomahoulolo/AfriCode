<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileCompletionController extends Controller
{
    /**
     * Afficher le formulaire pour compléter le profil
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('auth.profile-completion');
    }

    /**
     * Traiter le formulaire pour compléter le profil
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'role' => ['required', Rule::in(['apprenant', 'formateur'])],
        ]);

        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->role = $request->role;
        $user->save();

        // Rediriger vers le tableau de bord approprié
        return redirect()->route('dashboard');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Stocker les paramètres d'intention en session pour l'inscription aussi
        if ($request->has('intended') && $request->has('course_id')) {
            session([
                'register_intended_action' => $request->get('intended'),
                'register_intended_course_id' => $request->get('course_id'),
                'register_intended_course_type' => $request->get('course_type', 'unknown')
            ]);
        }
        
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:apprenant,formateur'],
            'terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Vérifier s'il y a une intention de s'inscrire à un cours
        if (session('register_intended_action') === 'enroll_course' && session('register_intended_course_id')) {
            $courseId = session('register_intended_course_id');
            $courseType = session('register_intended_course_type');
            
            // Nettoyer la session
            session()->forget(['register_intended_action', 'register_intended_course_id', 'register_intended_course_type']);
            
            // Récupérer le cours
            $course = \App\Models\Course::find($courseId);
            if ($course) {
                // Nouveau compte, donc pas d'inscription existante
                
                // Rediriger selon le type de cours
                if ($courseType === 'free' || $course->price <= 0) {
                    // Cours gratuit : inscription directe et redirection vers le cours
                    \App\Models\Enrollment::create([
                        'user_id' => Auth::id(),
                        'course_id' => $courseId,
                        'enrolled_at' => now(),
                        'progress_percentage' => 0
                    ]);
                    
                    return redirect()->route('apprenant.course.access', ['courseId' => $courseId])
                        ->with('success', 'Compte créé et inscription réussie ! Vous pouvez maintenant commencer la formation.');
                } else {
                    // Cours payant : rediriger vers la page de paiement
                    return redirect()->route('enrollment.show', $course)
                        ->with('info', 'Compte créé avec succès ! Veuillez procéder au paiement pour vous inscrire à cette formation.');
                }
            }
        }

        // La redirection sera gérée par DashboardController
        return redirect(route('dashboard'));
    }
}

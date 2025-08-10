<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // Stocker les paramètres d'intention en session
        if ($request->has('intended') && $request->has('course_id')) {
            session([
                'login_intended_action' => $request->get('intended'),
                'login_intended_course_id' => $request->get('course_id'),
                'login_intended_course_type' => $request->get('course_type', 'unknown')
            ]);
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Vérifier s'il y a une intention de s'inscrire à un cours
        if (session('login_intended_action') === 'enroll_course' && session('login_intended_course_id')) {
            $courseId = session('login_intended_course_id');
            $courseType = session('login_intended_course_type');
            
            // Nettoyer la session
            session()->forget(['login_intended_action', 'login_intended_course_id', 'login_intended_course_type']);
            
            // Récupérer le cours
            $course = \App\Models\Course::find($courseId);
            if ($course) {
                // Vérifier si l'utilisateur est déjà inscrit
                $existingEnrollment = \App\Models\Enrollment::where('user_id', Auth::id())
                    ->where('course_id', $courseId)
                    ->first();
                
                if ($existingEnrollment) {
                    // Déjà inscrit, rediriger vers le cours
                    return redirect()->route('apprenant.course.access', ['courseId' => $courseId])
                        ->with('info', 'Vous êtes déjà inscrit à ce cours.');
                }
                
                // Pas encore inscrit, rediriger selon le type de cours
                if ($courseType === 'free' || $course->price <= 0) {
                    // Cours gratuit : inscription directe et redirection vers le cours
                    \App\Models\Enrollment::create([
                        'user_id' => Auth::id(),
                        'course_id' => $courseId,
                        'enrolled_at' => now(),
                        'progress_percentage' => 0
                    ]);
                    
                    return redirect()->route('apprenant.course.access', ['courseId' => $courseId])
                        ->with('success', 'Inscription réussie ! Vous pouvez maintenant commencer la formation.');
                } else {
                    // Cours payant : rediriger vers la page de paiement
                    return redirect()->route('enrollment.show', $course)
                        ->with('info', 'Veuillez procéder au paiement pour vous inscrire à cette formation.');
                }
            }
        }

        // Rediriger vers le tableau de bord central qui gérera ensuite la redirection spécifique
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

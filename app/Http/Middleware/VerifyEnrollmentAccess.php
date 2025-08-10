<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerifyEnrollmentAccess
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
        $user = Auth::user();
        
        // Protection contre les boucles de redirection
        $referer = $request->header('Referer');
        $currentUrl = $request->url();
        
        // Protection plus robuste contre les boucles de redirection
        if ($referer && str_contains($referer, '/enrollment/')) {
            Log::warning('Redirect loop detected - allowing access to break loop', [
                'referer' => $referer,
                'current_url' => $currentUrl,
                'user_id' => $user ? $user->id : 'not authenticated'
            ]);
            // Passer directement sans vérification pour éviter la boucle
            return $next($request);
        }

        // Vérifier aussi si on vient d'une page d'enrollment via session
        $previousUrl = session()->previousUrl();
        if ($previousUrl && str_contains($previousUrl, '/enrollment/')) {
            Log::warning('Session-based redirect loop detected - allowing access', [
                'previous_url' => $previousUrl,
                'current_url' => $currentUrl,
                'user_id' => $user ? $user->id : 'not authenticated'
            ]);
            return $next($request);
        }
        
        // Ajouter des logs pour debug
        Log::info('VerifyEnrollmentAccess middleware called', [
            'user_id' => $user ? $user->id : 'not authenticated',
            'route' => $request->route()->getName(),
            'url' => $request->url(),
            'method' => $request->method(),
            'params' => $request->route()->parameters()
        ]);
        
        // Extraire l'ID du cours depuis les paramètres de route
        $courseId = $request->route('courseId') ?? $request->route('lessonId') ?? $request->route('quizId');
        
        // Si c'est une leçon ou un quiz, récupérer l'ID du cours
        if ($request->route('lessonId')) {
            $lesson = \App\Models\Lesson::find($request->route('lessonId'));
            $courseId = $lesson ? $lesson->module->course_id : null;
        } elseif ($request->route('quizId')) {
            $quiz = \App\Models\Quiz::find($request->route('quizId'));
            $courseId = $quiz ? $quiz->course_id : null;
        }
        
        if (!$courseId) {
            Log::warning('Course ID not found in middleware', [
                'route_name' => $request->route()->getName(),
                'route_params' => $request->route()->parameters()
            ]);
            return redirect()->route('apprenant.dashboard')
                ->with('error', 'Cours introuvable.');
        }
        
        $course = Course::find($courseId);
        if (!$course) {
            Log::warning('Course not found', ['courseId' => $courseId]);
            return redirect()->route('apprenant.dashboard')
                ->with('error', 'Cours introuvable.');
        }
        
        // Vérifier si l'utilisateur est inscrit au cours
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();
            
        if (!$enrollment) {
            Log::info('User not enrolled', ['user_id' => $user->id, 'course_id' => $courseId]);
            // Rediriger vers la page de détail du cours en utilisant le slug si disponible
            if ($course->slug) {
                return redirect()->route('courses.show', $course->slug)
                    ->with('error', 'Vous devez vous inscrire à ce cours pour y accéder.');
            } else {
                return redirect('/cours/' . $course->id)
                    ->with('error', 'Vous devez vous inscrire à ce cours pour y accéder.');
            }
        }
        
        // Si le cours est payant, vérifier le paiement
        if ($course->price > 0) {
            $payment = Payment::where('enrollment_id', $enrollment->id)
                ->where('status', 'completed')
                ->first();
                
            if (!$payment) {
                Log::info('Payment not completed - redirecting to enrollment', [
                    'enrollment_id' => $enrollment->id,
                    'course_id' => $courseId,
                    'course_price' => $course->price
                ]);
                
                // Éviter la boucle de redirection - rediriger vers la page d'enrollment
                return redirect()->route('enrollment.show', $course)
                    ->with('warning', 'Vous devez finaliser le paiement pour accéder à ce cours.');
            }
        }
        
        Log::info('Access granted', ['user_id' => $user->id, 'course_id' => $courseId]);
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Enrollment;
use App\Models\Payment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProtectCourseFiles
{
    /**
     * Handle an incoming request.
     * Protège les fichiers de cours (vidéos, PDF, etc.) contre le téléchargement non autorisé
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            abort(403, 'Accès non autorisé');
        }

        $user = Auth::user();
        
        // Obtenir le chemin du fichier depuis l'URL
        $filePath = $request->route('filepath') ?? $request->path();
        
        // Vérifier si c'est un fichier de cours (basé sur le chemin)
        if (!$this->isCourseFile($filePath)) {
            return $next($request);
        }
        
        // Extraire l'ID du cours depuis le chemin du fichier
        $courseId = $this->extractCourseIdFromPath($filePath);
        
        if (!$courseId) {
            abort(403, 'Cours non identifiable');
        }
        
        // Vérifier si l'utilisateur a accès à ce cours
        if (!$this->hasAccessToCourse($user->id, $courseId)) {
            abort(403, 'Vous n\'avez pas accès à ce contenu. Veuillez vous inscrire au cours.');
        }
        
        return $next($request);
    }
    
    /**
     * Vérifier si le fichier est un fichier de cours
     */
    private function isCourseFile($filePath)
    {
        // Patterns pour identifier les fichiers de cours
        $courseFilePatterns = [
            '/courses/',
            '/lessons/',
            '/modules/',
            '/course-videos/',
            '/course-resources/',
            '/course-materials/'
        ];
        
        foreach ($courseFilePatterns as $pattern) {
            if (strpos($filePath, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Extraire l'ID du cours depuis le chemin du fichier
     */
    private function extractCourseIdFromPath($filePath)
    {
        // Pattern pour extraire l'ID du cours : /courses/{courseId}/...
        if (preg_match('/\/courses\/(\d+)\//', $filePath, $matches)) {
            return (int) $matches[1];
        }
        
        // Pattern alternatif : /course-{courseId}-...
        if (preg_match('/\/course-(\d+)-/', $filePath, $matches)) {
            return (int) $matches[1];
        }
        
        return null;
    }
    
    /**
     * Vérifier si l'utilisateur a accès au cours
     */
    private function hasAccessToCourse($userId, $courseId)
    {
        // Récupérer l'inscription
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
            
        if (!$enrollment) {
            return false;
        }
        
        // Récupérer le cours pour vérifier s'il est payant
        $course = \App\Models\Course::find($courseId);
        if (!$course) {
            return false;
        }
        
        // Si le cours est gratuit, l'inscription suffit
        if ($course->price <= 0) {
            return true;
        }
        
        // Si le cours est payant, vérifier le paiement
        $payment = Payment::where('enrollment_id', $enrollment->id)
            ->where('status', 'completed')
            ->first();
            
        return $payment !== null;
    }
}

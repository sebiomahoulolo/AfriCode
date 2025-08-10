<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecureContentController extends Controller
{
    /**
     * Servir une vidéo de cours de manière sécurisée
     */
    public function serveVideo(Request $request, $courseId, $lessonId)
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Authentification requise');
        }
        
        // Vérifier l'accès au cours
        if (!$this->hasAccessToCourse($user->id, $courseId)) {
            abort(403, 'Accès non autorisé à ce cours');
        }
        
        // Récupérer la leçon
        $lesson = Lesson::where('id', $lessonId)
            ->whereHas('module', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->first();
            
        if (!$lesson || !$lesson->video_path) {
            abort(404, 'Vidéo non trouvée');
        }
        
        $videoPath = storage_path('app/' . $lesson->video_path);
        
        if (!file_exists($videoPath)) {
            abort(404, 'Fichier vidéo non trouvé');
        }
        
        return $this->streamVideo($videoPath, $request);
    }
    
    /**
     * Servir un fichier de ressource de cours
     */
    public function serveResource(Request $request, $courseId, $filename)
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Authentification requise');
        }
        
        // Vérifier l'accès au cours
        if (!$this->hasAccessToCourse($user->id, $courseId)) {
            abort(403, 'Accès non autorisé à ce cours');
        }
        
        $resourcePath = storage_path("app/courses/{$courseId}/resources/{$filename}");
        
        if (!file_exists($resourcePath)) {
            abort(404, 'Ressource non trouvée');
        }
        
        // Déterminer le type MIME
        $mimeType = mime_content_type($resourcePath);
        
        return response()->file($resourcePath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }
    
    /**
     * Servir une vidéo avec token temporaire sécurisé
     */
    public function serveVideoWithToken(Request $request, $token)
    {
        $payload = \App\Helpers\SecureUrlHelper::verifySecureToken($token);
        
        if (!$payload || !isset($payload['course_id'], $payload['lesson_id'])) {
            abort(403, 'Token invalide ou expiré');
        }
        
        return $this->serveVideo($request, $payload['course_id'], $payload['lesson_id']);
    }
    
    /**
     * Servir une ressource avec token temporaire sécurisé
     */
    public function serveResourceWithToken(Request $request, $token)
    {
        $payload = \App\Helpers\SecureUrlHelper::verifySecureToken($token);
        
        if (!$payload || !isset($payload['course_id'], $payload['filename'])) {
            abort(403, 'Token invalide ou expiré');
        }
        
        return $this->serveResource($request, $payload['course_id'], $payload['filename']);
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
        $course = Course::find($courseId);
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
    
    /**
     * Streamer une vidéo avec support du range (pour permettre la recherche dans la vidéo)
     */
    private function streamVideo($videoPath, Request $request)
    {
        $fileSize = filesize($videoPath);
        $start = 0;
        $end = $fileSize - 1;
        
        // Gestion des requêtes Range pour le streaming
        if ($request->header('Range')) {
            $range = $request->header('Range');
            
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = (int) $matches[1];
                $end = $matches[2] ? (int) $matches[2] : $fileSize - 1;
            }
        }
        
        $length = $end - $start + 1;
        
        return new StreamedResponse(function() use ($videoPath, $start, $length) {
            $file = fopen($videoPath, 'rb');
            fseek($file, $start);
            
            $buffer = 8192;
            $bytesRemaining = $length;
            
            while ($bytesRemaining > 0 && !feof($file)) {
                $bytesToRead = min($buffer, $bytesRemaining);
                echo fread($file, $bytesToRead);
                $bytesRemaining -= $bytesToRead;
                flush();
            }
            
            fclose($file);
        }, 206, [
            'Content-Type' => 'video/mp4',
            'Content-Length' => $length,
            'Content-Range' => "bytes {$start}-{$end}/{$fileSize}",
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff'
        ]);
    }
}

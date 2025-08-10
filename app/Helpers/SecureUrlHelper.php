<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SecureUrlHelper
{
    /**
     * Générer une URL sécurisée temporaire pour une vidéo de cours
     */
    public static function generateSecureVideoUrl($courseId, $lessonId, $expiresInMinutes = 60)
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        
        $payload = [
            'user_id' => $user->id,
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'expires_at' => now()->addMinutes($expiresInMinutes)->timestamp
        ];
        
        $token = Crypt::encrypt($payload);
        
        return route('secure.video.token', [
            'token' => urlencode($token)
        ]);
    }
    
    /**
     * Générer une URL sécurisée temporaire pour une ressource de cours
     */
    public static function generateSecureResourceUrl($courseId, $filename, $expiresInMinutes = 60)
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        
        $payload = [
            'user_id' => $user->id,
            'course_id' => $courseId,
            'filename' => $filename,
            'expires_at' => now()->addMinutes($expiresInMinutes)->timestamp
        ];
        
        $token = Crypt::encrypt($payload);
        
        return route('secure.resource.token', [
            'token' => urlencode($token)
        ]);
    }
    
    /**
     * Vérifier et décoder un token sécurisé
     */
    public static function verifySecureToken($token)
    {
        try {
            $payload = Crypt::decrypt(urldecode($token));
            
            // Vérifier l'expiration
            if (isset($payload['expires_at']) && $payload['expires_at'] < now()->timestamp) {
                return null;
            }
            
            // Vérifier que l'utilisateur est le même
            if (isset($payload['user_id']) && $payload['user_id'] !== Auth::id()) {
                return null;
            }
            
            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }
}

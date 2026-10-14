<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageOptimizationService
{
    /**
     * Sauvegarde une image uploadée sans optimisation
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $options
     * @return string Chemin de l'image sauvegardée
     */
    public function optimizeAndStore(UploadedFile $file, string $directory = 'images', array $options = []): string
    {
        // Créer le chemin de destination dans public/storage
        $destinationPath = public_path('storage/' . $directory);
        
        // S'assurer que le répertoire existe
        try {
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du répertoire', [
                'directory' => $directory,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Impossible de créer le répertoire de stockage: ' . $directory);
        }

        // Générer un nom de fichier unique
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(10) . '.' . $extension;
        
        try {
            // Déplacer le fichier vers public/storage/{directory}
            $file->move($destinationPath, $filename);
            
            // Retourner le chemin relatif pour la base de données
            return 'storage/' . $directory . '/' . $filename;
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la sauvegarde de l\'image', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'directory' => $directory,
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e; // Relancer l'exception pour que le contrôleur puisse la gérer
        }
    }

    /**
     * Sauvegarde une image de couverture de cours
     *
     * @param UploadedFile $file
     * @return string
     */
    public function optimizeCourseCover(UploadedFile $file): string
    {
        return $this->optimizeAndStore($file, 'courses');
    }

    /**
     * Sauvegarde une image de profil utilisateur
     *
     * @param UploadedFile $file
     * @return string
     */
    public function optimizeProfileImage(UploadedFile $file): string
    {
        return $this->optimizeAndStore($file, 'profile_images');
    }

    /**
     * Sauvegarde une image de leçon
     *
     * @param UploadedFile $file
     * @return string
     */
    public function optimizeLessonImage(UploadedFile $file): string
    {
        return $this->optimizeAndStore($file, 'lesson_images');
    }

    /**
     * Supprime une image optimisée
     *
     * @param string $path
     * @return bool
     */
    public function deleteImage(string $path): bool
    {
        try {
            // Construire le chemin complet dans public/
            $fullPath = public_path($path);
            
            if (File::exists($fullPath)) {
                return File::delete($fullPath);
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::channel('structured')->error('Erreur lors de la suppression de l\'image', [
                'error' => $e->getMessage(),
                'path' => $path,
                'user_id' => auth()->id()
            ]);
            
            return false;
        }
    }
}

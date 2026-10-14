<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminImageController extends Controller
{
    /**
     * Handle image upload for WYSIWYG editors (CKEditor/TinyMCE)
     *
     * Accepts either 'upload' (CKEditor 5) or 'file' (TinyMCE) as the file field.
     * Returns both 'url' (CKEditor 5) and 'location' (TinyMCE) for compatibility.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        // Normalize the file input to support both CKEditor ('upload') and TinyMCE ('file')
        $fileKey = $request->hasFile('upload') ? 'upload' : 'file';

        $validator = Validator::make($request->all(), [
            $fileKey => 'required|image|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'url' => '',
                'location' => '',
                'error' => $validator->errors()->first($fileKey)
            ], 422);
        }

        try {
            // Store the uploaded file
            $file = $request->file($fileKey);
            $path = $file->store('lesson_images', 'public');
            
            // Get the filename from the path
            $filename = basename($path);
            
            // Generate public URL accessible to apprenants
            $url = route('lesson.images', ['filename' => $filename]);
            
            // Get file information
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();
            
            // Store image information in session for later use
            $imageData = [
                'filename' => $filename,
                'original_name' => $originalName,
                'path' => $path,
                'url' => $url,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'uploaded_at' => now()->toISOString()
            ];
            
            // Store in session temporarily
            session()->push('temp_lesson_images', $imageData);
            
            // Return payload compatible with both CKEditor ('url') and TinyMCE ('location')
            return response()->json([
                'url' => $url,
                'location' => $url,
                'image_data' => $imageData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'url' => '',
                'location' => '',
                'error' => 'Une erreur est survenue lors du téléchargement de l\'image: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminImageController extends Controller
{
    /**
     * Handle image upload for TinyMCE editor
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'location' => '',
                'error' => $validator->errors()->first('file')
            ], 422);
        }

        try {
            // Store the uploaded file
            $path = $request->file('file')->store('lesson_images', 'public');
            
            // Return the url for TinyMCE
            return response()->json([
                'location' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'location' => '',
                'error' => 'Une erreur est survenue lors du téléchargement de l\'image.'
            ], 500);
        }
    }
}

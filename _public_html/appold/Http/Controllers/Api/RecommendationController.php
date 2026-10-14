<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class RecommendationController extends Controller
{
    public function recommendCourses(Request $request)
    {
        $level = $request->query('level', 'debutant');
        $courses = Course::where('level', $level)
            ->where('status', 'published')
            ->take(5)
            ->get(['id', 'title', 'slug', 'short_description', 'cover_image_path']);
        return response()->json($courses);
    }
} 
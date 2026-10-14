<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class MarketingController extends Controller
{
    /**
     * Obtenir les cours en promotion pour les popups marketing
     */
    public function getPromotionalCourses()
    {
        // Récupérer les cours en promotion active
        $promotionalCourses = Course::where('status', 'published')
            ->where('price', '>', 0)
            ->where('is_promotional', true)
            ->where(function($query) {
                $query->where(function($subQuery) {
                    $subQuery->whereNotNull('promotion_start_date')
                             ->whereNotNull('promotion_end_date')
                             ->where('promotion_start_date', '<=', now())
                             ->where('promotion_end_date', '>=', now());
                })->orWhere(function($subQuery) {
                    $subQuery->whereNull('promotion_start_date')
                             ->whereNull('promotion_end_date');
                });
            })
            ->with(['formateur', 'category', 'ratings'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Si pas de cours en promotion spécifique, prendre les cours les plus populaires
        if ($promotionalCourses->isEmpty()) {
            $promotionalCourses = Course::where('status', 'published')
                ->where('price', '>', 0)
                ->with(['formateur', 'category', 'ratings'])
                ->withCount('enrollments')
                ->orderBy('enrollments_count', 'desc')
                ->take(3)
                ->get();
        }

        return response()->json([
            'courses' => $promotionalCourses->map(function($course) {
                $basePrice = (float) $course->price;
                $displayPrice = $basePrice;
                $originalPrice = $basePrice;
                $discountPercent = 0;

                if ($course->isCurrentlyOnPromotion() && $course->discount_percentage > 0) {
                    $discountPercent = (int) $course->getSavingsPercentage();
                    $displayPrice = (float) $course->getDiscountedPrice();
                    $originalPrice = $course->original_price ? (float) $course->original_price : $basePrice;
                } elseif ($basePrice > 0) {
                    // Cadre marketing : afficher une réduction pour les cours du fallback
                    $originalPrice = $basePrice * 1.5;
                    $discountPercent = (int) round((1 - $basePrice / $originalPrice) * 100);
                }

                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->short_description,
                    'slug' => $course->slug,
                    'price' => $displayPrice,
                    'original_price' => $originalPrice,
                    'discount_percentage' => $discountPercent,
                    'instructor' => $course->formateur ? $course->formateur->first_name . ' ' . $course->formateur->last_name : 'Équipe AfriCode',
                    'rating' => $course->ratings->avg('rating') ?? 4.5,
                    'modules_count' => $course->modules->count(),
                    'cover_image' => $course->cover_image_path ? asset($course->cover_image_path) : null,
                    'category' => $course->category ? $course->category->name : 'Développement',
                    'enrollments_count' => $course->enrollments_count ?? $course->enrollments->count(),
                    'is_promotional' => $course->isCurrentlyOnPromotion()
                ];
            })
        ]);
    }

    /**
     * Vérifier si l'utilisateur a déjà passé le test de niveau
     */
    public function checkTestEligibility()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['eligible' => true, 'reason' => 'guest']);
        }

        // Vérifier si l'utilisateur a déjà passé le test récemment (dans les 30 derniers jours)
        $recentTest = Cache::get("level_test_taken_{$user->id}");
        
        if ($recentTest) {
            return response()->json(['eligible' => false, 'reason' => 'recently_taken']);
        }

        // Vérifier si l'utilisateur a déjà des inscriptions (peut-être qu'il n'a pas besoin du test)
        $hasEnrollments = Enrollment::where('user_id', $user->id)
            ->whereHas('course', function($query) {
                $query->where('status', 'published');
            })
            ->where(function($query) {
                $query->whereHas('payment', function($paymentQuery) {
                    $paymentQuery->where('status', 'succeeded');
                })
                ->orWhereHas('course', function($courseQuery) {
                    $courseQuery->where('price', 0);
                });
            })
            ->exists();

        if ($hasEnrollments) {
            return response()->json(['eligible' => false, 'reason' => 'has_enrollments']);
        }

        return response()->json(['eligible' => true, 'reason' => 'eligible']);
    }

    /**
     * Marquer que l'utilisateur a vu/fermé le popup de test
     */
    public function markTestSeen(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            Cache::put("test_popup_seen_{$user->id}", true, now()->addDays(7));
        }

        return response()->json(['success' => true]);
    }

    /**
     * Marquer que l'utilisateur a vu/fermé le popup de promotion
     */
    public function markPromotionSeen(Request $request)
    {
        $user = Auth::user();
        $courseId = $request->input('course_id');
        
        if ($user && $courseId) {
            Cache::put("promotion_seen_{$user->id}_{$courseId}", true, now()->addDays(3));
        }

        return response()->json(['success' => true]);
    }

    /**
     * Obtenir les statistiques de marketing
     */
    public function getMarketingStats()
    {
        $stats = [
            'total_courses' => Course::where('status', 'published')->count(),
            'total_students' => Enrollment::whereHas('course', function($query) {
                $query->where('status', 'published');
            })->distinct('user_id')->count(),
            'success_rate' => 95, // Taux de réussite des étudiants
            'average_rating' => Course::where('status', 'published')
                ->with('ratings')
                ->get()
                ->avg(function($course) {
                    return $course->ratings->avg('rating');
                }) ?? 4.5
        ];

        return response()->json($stats);
    }
}

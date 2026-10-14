<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Ajouter ou retirer un cours de la wishlist
     */
    public function toggle(Request $request, Course $course)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour ajouter des favoris'
            ], 401);
        }

        $user = Auth::user();
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($wishlist) {
            // Retirer de la wishlist
            $wishlist->delete();
            $isInWishlist = false;
            $message = 'Cours retiré de vos favoris';
        } else {
            // Ajouter à la wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);
            $isInWishlist = true;
            $message = 'Cours ajouté à vos favoris';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'isInWishlist' => $isInWishlist
        ]);
    }

    /**
     * Vérifier si un cours est dans la wishlist
     */
    public function check(Course $course)
    {
        if (!Auth::check()) {
            return response()->json([
                'isInWishlist' => false
            ]);
        }

        $isInWishlist = Wishlist::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->exists();

        return response()->json([
            'isInWishlist' => $isInWishlist
        ]);
    }

    /**
     * Vérifier l'état de la wishlist pour plusieurs cours (batch)
     */
    public function checkBatch(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'wishlistItems' => []
            ]);
        }

        $courseIds = explode(',', $request->get('ids', ''));
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->whereIn('course_id', $courseIds)
            ->pluck('course_id')
            ->toArray();

        return response()->json([
            'wishlistItems' => $wishlistItems
        ]);
    }
}

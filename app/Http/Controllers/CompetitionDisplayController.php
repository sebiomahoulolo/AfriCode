<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Challenge;
use App\Models\Leaderboard;
use App\Models\User;
use App\Models\UserScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompetitionDisplayController extends Controller
{
    public function index()
    {
        try {
            // Récupérer les défis actifs
            $challenges = Challenge::where('is_active', true)
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get()
                ->map(function ($challenge) {
                    return [
                        'id' => $challenge->id,
                        'title' => $challenge->name,
                        'description' => $challenge->description,
                        'difficulty' => ucfirst($challenge->difficulty ?? 'débutant'),
                        'points' => $this->getChallengePoints($challenge),
                        'timeLeft' => $this->calculateTimeLeft($challenge->end_date)
                    ];
                });

            // Récupérer le classement global
            $globalLeaderboard = Leaderboard::where('type', 'global')->first();
            $leaderboardData = [];
            
            if ($globalLeaderboard) {
                $leaderboardData = UserScore::with('user')
                    ->where('leaderboard_id', $globalLeaderboard->id)
                    ->topRanked(10)
                    ->get()
                    ->map(function ($score) {
                        return [
                            'rank' => $score->rank,
                            'id' => $score->user->id,
                            'name' => $score->user->name,
                            'avatar' => $this->getUserAvatar($score->user),
                            'score' => $score->score,
                            'recentBadges' => $this->getRecentBadges($score->user->id)
                        ];
                    });
            }

            // Récupérer les badges
            $badges = Badge::where('is_active', true)
                ->orderBy('unlock_order')
                ->get()
                ->map(function ($badge) {
                    $userHasBadge = Auth::check() ? 
                        $this->userHasBadge(Auth::id(), $badge->id) : false;
                    
                    return [
                        'id' => $badge->id,
                        'name' => $badge->name,
                        'icon' => $badge->icon,
                        'locked' => !$userHasBadge,
                        'description' => $badge->description,
                        'color' => $badge->color
                    ];
                });

            // Données de l'utilisateur connecté
            $currentUser = null;
            if (Auth::check()) {
                $user = Auth::user();
                $userScore = $globalLeaderboard ? 
                    UserScore::where('user_id', $user->id)
                        ->where('leaderboard_id', $globalLeaderboard->id)
                        ->first() : null;

                $currentUser = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $this->getUserAvatar($user),
                    'score' => $userScore ? $userScore->score : 0,
                    'rank' => $userScore ? $userScore->rank : null
                ];
            }

            return view('pages.compdisp', compact('challenges', 'leaderboardData', 'badges', 'currentUser'));
        } catch (\Exception $e) {
            // En cas d'erreur, retourner des données par défaut
            \Log::error('Erreur dans CompetitionDisplayController: ' . $e->getMessage());
            
            return view('pages.compdisp', [
                'challenges' => [],
                'leaderboardData' => [],
                'badges' => [],
                'currentUser' => null
            ]);
        }
    }

    public function getLeaderboard(Request $request)
    {
        try {
            $type = $request->get('type', 'global');
            
            $leaderboard = Leaderboard::where('type', $type)->first();
            
            if (!$leaderboard) {
                return response()->json(['error' => 'Classement non trouvé'], 404);
            }

            $leaderboardData = UserScore::with('user')
                ->where('leaderboard_id', $leaderboard->id)
                ->topRanked(10)
                ->get()
                ->map(function ($score) {
                    return [
                        'rank' => $score->rank,
                        'id' => $score->user->id,
                        'name' => $score->user->name,
                        'avatar' => $this->getUserAvatar($score->user),
                        'score' => $score->score,
                        'recentBadges' => $this->getRecentBadges($score->user->id)
                    ];
                });

            return response()->json($leaderboardData);
        } catch (\Exception $e) {
            \Log::error('Erreur dans getLeaderboard: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    private function calculateTimeLeft($endDate)
    {
        if (!$endDate) {
            return 'Pas de limite';
        }

        $now = now();
        $end = \Carbon\Carbon::parse($endDate);

        if ($now->gt($end)) {
            return 'Terminé';
        }

        $diff = $now->diff($end);
        
        if ($diff->days > 0) {
            return $diff->days . 'j ' . $diff->h . 'h';
        } elseif ($diff->h > 0) {
            return $diff->h . 'h ' . $diff->i . 'm';
        } else {
            return $diff->i . 'm';
        }
    }

    private function getUserAvatar($user)
    {
        // Si l'utilisateur a un avatar, l'utiliser
        if (method_exists($user, 'profile_photo_url') && $user->profile_photo_url) {
            return $user->profile_photo_url;
        }

        // Sinon, créer un avatar avec les initiales
        $initials = strtoupper(substr($user->name, 0, 2));
        $colors = ['#1EA38B', '#FF8E2A', '#E32D31', '#27B371', '#9B59B6'];
        $color = $colors[array_rand($colors)];
        
        return "https://via.placeholder.com/40/{$color}/FFFFFF?text=" . urlencode($initials);
    }

    private function getRecentBadges($userId)
    {
        try {
            $recentBadges = DB::table('badge_user')
                ->join('badges', 'badge_user.badge_id', '=', 'badges.id')
                ->where('badge_user.user_id', $userId)
                ->orderBy('badge_user.awarded_at', 'desc')
                ->limit(3)
                ->pluck('badges.icon')
                ->toArray();

            return $recentBadges;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function userHasBadge($userId, $badgeId)
    {
        try {
            return DB::table('badge_user')
                ->where('user_id', $userId)
                ->where('badge_id', $badgeId)
                ->exists();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getChallengePoints($challenge)
    {
        try {
            $rewards = json_decode($challenge->rewards, true);
            return $rewards['points'] ?? 10;
        } catch (\Exception $e) {
            return 10;
        }
    }
}

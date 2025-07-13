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
            // Attribuer automatiquement des badges aux utilisateurs
            $this->assignBadgesToUsers();

            // Récupérer les défis actifs
            $challenges = Challenge::where('is_active', true)
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
                ->select('badges.icon', 'badges.name', 'badges.color')
                ->get()
                ->map(function ($badge) {
                    return [
                        'icon' => $badge->icon,
                        'name' => $badge->name,
                        'color' => $badge->color
                    ];
                })
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

    private function assignBadgesToUsers()
    {
        try {
            // Récupérer tous les utilisateurs avec leurs scores
            $globalLeaderboard = Leaderboard::where('type', 'global')->first();
            if (!$globalLeaderboard) {
                return;
            }

            $userScores = UserScore::with('user')
                ->where('leaderboard_id', $globalLeaderboard->id)
                ->orderBy('score', 'desc')
                ->get();

            foreach ($userScores as $userScore) {
                $userId = $userScore->user->id;
                $score = $userScore->score;
                $rank = $userScore->rank;

                // Badge pour le 1er du classement
                if ($rank === 1) {
                    $this->assignBadgeToUser($userId, 'fa-trophy', 'Champion', 'Premier du classement global', '#FFD700');
                }

                // Badge pour le top 3
                if ($rank <= 3) {
                    $this->assignBadgeToUser($userId, 'fa-medal', 'Top 3', 'Dans le top 3 du classement', '#C0C0C0');
                }

                // Badge pour le top 10
                if ($rank <= 10) {
                    $this->assignBadgeToUser($userId, 'fa-award', 'Top 10', 'Dans le top 10 du classement', '#CD7F32');
                }

                // Badge pour score élevé (1000+ points)
                if ($score >= 1000) {
                    $this->assignBadgeToUser($userId, 'fa-star', 'Expert', 'A atteint 1000 points', '#FF8E2A');
                }

                // Badge pour score très élevé (2000+ points)
                if ($score >= 2000) {
                    $this->assignBadgeToUser($userId, 'fa-crown', 'Maître', 'A atteint 2000 points', '#9B59B6');
                }

                // Badge pour score exceptionnel (5000+ points)
                if ($score >= 5000) {
                    $this->assignBadgeToUser($userId, 'fa-gem', 'Légende', 'A atteint 5000 points', '#E32D31');
                }

                // Badge pour participation (score > 0)
                if ($score > 0) {
                    $this->assignBadgeToUser($userId, 'fa-user-graduate', 'Participant', 'A participé aux compétitions', '#27B371');
                }
            }

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'attribution des badges: ' . $e->getMessage());
        }
    }

    private function assignBadgeToUser($userId, $icon, $name, $description, $color)
    {
        try {
            // Vérifier si le badge existe déjà
            $badge = Badge::where('icon', $icon)
                         ->where('name', $name)
                         ->first();

            // Si le badge n'existe pas, le créer
            if (!$badge) {
                $badge = Badge::create([
                    'name' => $name,
                    'description' => $description,
                    'icon' => $icon,
                    'color' => $color,
                    'is_active' => true,
                    'unlock_order' => 1
                ]);
            }

            // Vérifier si l'utilisateur a déjà ce badge
            $userHasBadge = DB::table('badge_user')
                ->where('user_id', $userId)
                ->where('badge_id', $badge->id)
                ->exists();

            // Si l'utilisateur n'a pas ce badge, l'attribuer
            if (!$userHasBadge) {
                DB::table('badge_user')->insert([
                    'user_id' => $userId,
                    'badge_id' => $badge->id,
                    'awarded_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                \Log::info("Badge '{$name}' attribué à l'utilisateur {$userId}");
            }

        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'attribution du badge '{$name}' à l'utilisateur {$userId}: " . $e->getMessage());
        }
    }
}

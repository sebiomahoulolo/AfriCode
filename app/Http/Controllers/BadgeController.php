<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    /**
     * Affiche la liste des badges disponibles.
     */
    public function index()
    {
        $badges = Badge::where('is_secret', false)
            ->orWhere(function ($query) {
                $query->where('is_secret', true)
                    ->whereHas('users', function ($q) {
                        $q->where('user_id', auth()->id());
                    });
            })
            ->get();

        $userBadges = auth()->user()->badges()
            ->withPivot('earned_at')
            ->get();

        return view('badges.index', compact('badges', 'userBadges'));
    }

    /**
     * Affiche les détails d'un badge.
     */
    public function show(Badge $badge)
    {
        if ($badge->is_secret && !$badge->users()->where('user_id', auth()->id())->exists()) {
            abort(404);
        }

        $earnedBy = $badge->users()
            ->withPivot('earned_at')
            ->orderBy('pivot_earned_at', 'desc')
            ->take(10)
            ->get();

        return view('badges.show', compact('badge', 'earnedBy'));
    }

    /**
     * Affiche la liste des récompenses disponibles.
     */
    public function rewards()
    {
        $user = Auth::user();
        $rewards = Reward::where('is_active', true)->get();
        $userRewards = $user->rewards;
        $availableRewards = $user->getAvailableRewards();

        return view('badges.rewards', compact('rewards', 'userRewards', 'availableRewards'));
    }

    /**
     * Affiche les détails d'une récompense.
     */
    public function showReward(Reward $reward)
    {
        $user = Auth::user();
        $hasReward = $user->hasReward($reward->name);
        $canClaim = $user->progress->total_points >= $reward->required_points;

        return view('badges.show-reward', compact('reward', 'hasReward', 'canClaim'));
    }

    /**
     * Permet à l'utilisateur de réclamer une récompense.
     */
    public function claimReward(Reward $reward)
    {
        $user = Auth::user();

        if (!$user->hasReward($reward->name)) {
            return back()->with('error', 'Vous n\'avez pas encore obtenu cette récompense.');
        }

        $userReward = $user->rewards()->where('reward_id', $reward->id)->first();

        if ($userReward->pivot->is_claimed) {
            return back()->with('error', 'Vous avez déjà réclamé cette récompense.');
        }

        try {
            $reward->claimBy($user);
            return back()->with('success', 'Récompense réclamée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la réclamation de la récompense.');
        }
    }

    /**
     * Affiche le profil de progression de l'utilisateur.
     */
    public function profile()
    {
        $user = Auth::user();
        $stats = $user->getStats();
        $recentActivities = $user->activities()->latest()->take(10)->get();

        return view('badges.profile', compact('user', 'stats', 'recentActivities'));
    }

    /**
     * Récupère les conditions requises pour obtenir un badge.
     */
    private function getBadgeRequirements(Badge $badge, User $user): array
    {
        $requirements = [
            'points' => [
                'required' => $badge->required_points,
                'current' => $user->progress->total_points ?? 0,
                'completed' => ($user->progress->total_points ?? 0) >= $badge->required_points,
            ],
        ];

        switch ($badge->type) {
            case 'course_completion':
                $requirements['courses'] = [
                    'required' => $badge->value['courses'] ?? 1,
                    'current' => $user->enrolledCourses()->where('status', 'completed')->count(),
                    'completed' => $user->enrolledCourses()->where('status', 'completed')->count() >= ($badge->value['courses'] ?? 1),
                ];
                break;

            case 'quiz_master':
                $requirements['quizzes'] = [
                    'required' => $badge->value['quizzes'] ?? 1,
                    'current' => $user->quizAttempts()->where('score', '>=', 80)->count(),
                    'completed' => $user->quizAttempts()->where('score', '>=', 80)->count() >= ($badge->value['quizzes'] ?? 1),
                ];
                break;

            case 'social_butterfly':
                $requirements['interactions'] = [
                    'required' => $badge->value['interactions'] ?? 10,
                    'current' => $user->activities()->where('type', 'social_interaction')->count(),
                    'completed' => $user->activities()->where('type', 'social_interaction')->count() >= ($badge->value['interactions'] ?? 10),
                ];
                break;
        }

        return $requirements;
    }

    public function userBadges(User $user)
    {
        $badges = $user->badges()
            ->withPivot('earned_at')
            ->get();

        return view('badges.user-badges', compact('user', 'badges'));
    }

    public function checkProgress()
    {
        $user = auth()->user();
        $availableBadges = Badge::whereDoesntHave('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $newBadges = [];
        foreach ($availableBadges as $badge) {
            if ($badge->checkRequirements($user)) {
                $badge->awardTo($user);
                $newBadges[] = $badge;
            }
        }

        return response()->json([
            'success' => true,
            'new_badges' => $newBadges
        ]);
    }
} 
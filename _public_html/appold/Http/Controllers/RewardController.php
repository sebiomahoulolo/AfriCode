<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\User;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::where('is_active', true)->get();
        $userRewards = auth()->user()->rewards()
            ->withPivot('claimed_at', 'status')
            ->get();

        return view('rewards.index', compact('rewards', 'userRewards'));
    }

    public function show(Reward $reward)
    {
        if (!$reward->is_active) {
            abort(404);
        }

        $claimedBy = $reward->users()
            ->withPivot('claimed_at', 'status')
            ->orderBy('pivot_claimed_at', 'desc')
            ->take(10)
            ->get();

        return view('rewards.show', compact('reward', 'claimedBy'));
    }

    public function claim(Reward $reward)
    {
        $user = auth()->user();

        if (!$reward->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Cette récompense n\'est plus disponible.'
            ], 400);
        }

        if ($reward->users()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà réclamé cette récompense.'
            ], 400);
        }

        if (!$reward->checkRequirements($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne remplissez pas les conditions pour cette récompense.'
            ], 400);
        }

        if ($reward->claim($user)) {
            return response()->json([
                'success' => true,
                'message' => 'Récompense réclamée avec succès !'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors de la réclamation de la récompense.'
        ], 500);
    }

    public function userRewards(User $user)
    {
        $rewards = $user->rewards()
            ->withPivot('claimed_at', 'status')
            ->get();

        return view('rewards.user-rewards', compact('user', 'rewards'));
    }

    public function checkAvailable()
    {
        $user = auth()->user();
        $availableRewards = $user->getAvailableRewards();

        return response()->json([
            'success' => true,
            'available_rewards' => $availableRewards
        ]);
    }
} 
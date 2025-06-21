<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::all();
        $userAchievements = Auth::user()->achievements()
            ->withPivot('earned_at')
            ->get()
            ->keyBy('id');

        return view('achievements.index', compact('achievements', 'userAchievements'));
    }

    public function show(Achievement $achievement)
    {
        $users = $achievement->users()
            ->withPivot('earned_at')
            ->orderBy('user_achievements.earned_at', 'desc')
            ->paginate(20);

        return view('achievements.show', compact('achievement', 'users'));
    }

    public function checkAchievements(User $user)
    {
        $achievements = Achievement::all();
        $newAchievements = [];

        foreach ($achievements as $achievement) {
            if ($this->checkAchievementRequirements($user, $achievement)) {
                if (!$user->achievements()->where('achievement_id', $achievement->id)->exists()) {
                    $user->achievements()->attach($achievement->id, [
                        'earned_at' => now()
                    ]);
                    $newAchievements[] = $achievement;
                }
            }
        }

        return $newAchievements;
    }

    private function checkAchievementRequirements(User $user, Achievement $achievement)
    {
        $requirements = $achievement->requirement;
        
        switch ($achievement->type) {
            case 'course_completion':
                return $user->completedCourses()->count() >= $requirements['count'];
            
            case 'quiz_perfect':
                return $user->perfectQuizzes()->count() >= $requirements['count'];
            
            case 'streak':
                return $user->currentStreak() >= $requirements['days'];
            
            case 'points':
                return $user->totalPoints() >= $requirements['points'];
            
            default:
                return false;
        }
    }

    public function leaderboard()
    {
        $users = User::withCount('achievements')
            ->orderBy('achievements_count', 'desc')
            ->paginate(20);

        return view('achievements.leaderboard', compact('users'));
    }
} 
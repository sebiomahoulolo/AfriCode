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

            // Charger les participants pour chaque challenge
            $challengeParticipants = [];
            foreach ($challenges as $challenge) {
                $challengeId = is_array($challenge) ? $challenge['id'] : $challenge->id;
                $challengeParticipants[$challengeId] = \App\Models\Challenge::find($challengeId)?->users->map(function($u) {
                    return trim($u->first_name . ' ' . $u->last_name);
                })->toArray();
            }

            // Récupérer les compétitions actives
            $competitions = \App\Models\Competition::where('status', 'upcoming')
                ->orderBy('start_datetime', 'asc')
                ->take(6)
                ->get()
                ->map(function ($competition) {
                    return [
                        'id' => $competition->id,
                        'title' => $competition->title,
                        'description' => $competition->description,
                        'start' => $competition->start_datetime,
                        'end' => $competition->end_datetime,
                        'participants' => $competition->max_participants,
                        'slug' => $competition->slug,
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
            $badges = Badge::orderBy('unlock_order')
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

            return view('pages.compdisp', compact('challenges', 'competitions', 'leaderboardData', 'badges', 'currentUser', 'challengeParticipants'));
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }

    public function show($slug)
    {
        $competition = \App\Models\Competition::where('slug', $slug)->firstOrFail();
        $user = auth()->user();
        $isRegistered = false;
        if ($user) {
            $isRegistered = \App\Models\CompetitionRegistration::where('competition_id', $competition->id)
                ->where('user_id', $user->id)
                ->exists();
        }
        $participantsCount = \App\Models\CompetitionRegistration::where('competition_id', $competition->id)->count();
        return view('competitions.show', compact('competition', 'isRegistered', 'participantsCount'));
    }

    public function register($id)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour participer.');
        }
        $competition = \App\Models\Competition::findOrFail($id);
        $exists = \App\Models\CompetitionRegistration::where('competition_id', $competition->id)
            ->where('user_id', $user->id)
            ->exists();
        if ($exists) {
            return redirect()->route('competitions.show', $competition->slug)->with('success', 'Vous êtes déjà inscrit à cette compétition.');
        }
        \App\Models\CompetitionRegistration::create([
            'competition_id' => $competition->id,
            'user_id' => $user->id,
            'registered_at' => now(),
        ]);
        return redirect()->route('competitions.show', $competition->slug)->with('success', 'Inscription réussie à la compétition !');
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

    public function showChallenge($id)
    {
        $challenge = \App\Models\Challenge::findOrFail($id);
        $user = auth()->user();
        $isParticipating = false;
        if ($user) {
            $isParticipating = $challenge->users()->where('user_id', $user->id)->exists();
        }
        $participantsCount = $challenge->users()->count();
        return view('challenges.show', compact('challenge', 'isParticipating', 'participantsCount'));
    }

    public function participateChallenge($id)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour participer.');
        }
        $challenge = \App\Models\Challenge::findOrFail($id);
        $exists = $challenge->users()->where('user_id', $user->id)->exists();
        if ($exists) {
            return redirect()->route('challenges.show', $challenge->id)->with('success', 'Vous participez déjà à ce défi.');
        }
        $challenge->users()->attach($user->id, [
            'progress' => json_encode([]),
            'is_completed' => false,
        ]);
        return redirect()->route('challenges.show', $challenge->id)->with('success', 'Vous participez maintenant à ce défi !');
    }

    public function playChallenge($id, Request $request)
    {
        $challenge = \App\Models\Challenge::findOrFail($id);
        $user = auth()->user();
        $isParticipating = $user ? $challenge->users()->where('user_id', $user->id)->exists() : false;
        if (!$isParticipating) {
            return redirect()->route('challenges.show', $challenge->id)->with('error', 'Vous devez vous inscrire au défi pour participer.');
        }
        $questions = $challenge->questions()->with('options')->get();
        $score = null;
        $submitted = false;
        $userAnswers = [];
        $submission = \App\Models\ChallengeSubmission::where('challenge_id', $challenge->id)->where('user_id', $user->id)->first();
        if ($submission) {
            $submitted = true;
            $score = $submission->score;
            $userAnswers = $submission->answers ?? [];
        }
        if ($request->isMethod('post') && !$submitted) {
            $answers = $request->input('answers', []);
            $score = 0;
            foreach ($questions as $question) {
                $correct = $question->options->where('is_correct', true)->pluck('id')->sort()->values();
                $userAnswer = collect($answers[$question->id] ?? [])->map(fn($v)=>(int)$v)->sort()->values();
                if ($userAnswer->count() && $userAnswer->toArray() === $correct->toArray()) {
                    $score++;
                }
            }
            $submission = \App\Models\ChallengeSubmission::updateOrCreate(
                [
                    'challenge_id' => $challenge->id,
                    'user_id' => $user->id,
                ],
                [
                    'answers' => $answers,
                    'score' => $score,
                    'submitted_at' => now(),
                ]
            );
            $submitted = true;
            $userAnswers = $answers;
        }
        return view('challenges.play', compact('challenge', 'questions', 'score', 'submitted', 'userAnswers'));
    }

    public function playCompetition($slug, Request $request)
    {
        $competition = \App\Models\Competition::where('slug', $slug)->firstOrFail();
        $user = auth()->user();
        $isRegistered = $user ? \App\Models\CompetitionRegistration::where('competition_id', $competition->id)->where('user_id', $user->id)->exists() : false;
        if (!$isRegistered) {
            return redirect()->route('competitions.show', $competition->slug)->with('error', 'Vous devez vous inscrire à la compétition pour participer.');
        }
        $confirmation = false;
        if ($request->isMethod('post')) {
            $registration = \App\Models\CompetitionRegistration::where('competition_id', $competition->id)->where('user_id', $user->id)->first();
            if ($registration) {
                \App\Models\CompetitionSubmission::updateOrCreate(
                    [
                        'registration_id' => $registration->id,
                    ],
                    [
                        'project_title' => $request->input('project_title'),
                        'project_description' => $request->input('project_description'),
                        'project_link_repository' => $request->input('project_link_repository'),
                        'project_link_live' => $request->input('project_link_live'),
                        'submitted_at' => now(),
                    ]
                );
                $confirmation = true;
            }
        }
        return view('competitions.play', compact('competition', 'confirmation'));
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
            $rewards = is_array($challenge->rewards) ? $challenge->rewards : json_decode($challenge->rewards, true);
            return is_array($rewards) && isset($rewards['points']) ? $rewards['points'] : 10;
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

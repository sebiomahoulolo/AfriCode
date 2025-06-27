<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'time_limit',
        'passing_score',
        'randomize_questions',
        'show_correct_answers',
        'allow_retake',
        'max_attempts',
        'is_adaptive',
        'is_realtime',
        'is_collaborative',
        'allow_media',
        'anti_cheat_enabled',
        'module_id',
        'course_id',
        'quiz_type', // 'module_end' ou 'course_final'
        'passing_score',
        'time_limit_minutes',
        'is_required',
        'order',
        'max_attempts'

    ];

    protected $casts = [
        'randomize_questions' => 'boolean',
        'show_correct_answers' => 'boolean',
        'allow_retake' => 'boolean',
        'time_limit' => 'integer',
        'passing_score' => 'integer',

        'max_attempts' => 'integer',
        'is_adaptive' => 'boolean',
        'is_realtime' => 'boolean',
        'is_collaborative' => 'boolean',
        'allow_media' => 'boolean',
        'anti_cheat_enabled' => 'boolean',

        'time_limit_minutes' => 'integer',
        'is_required' => 'boolean',
        'order' => 'integer',
        'max_attempts' => 'integer'

    ];

   

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(QuizMedia::class);
    }

    public function adaptiveQuestions(): HasMany
    {
        return $this->hasMany(AdaptiveQuestion::class);
    }

    public function realtimeSession(): HasOne
    {
        return $this->hasOne(RealtimeQuizSession::class);
    }

    public function collaborativeSession(): HasOne
    {
        return $this->hasOne(CollaborativeQuizSession::class);
    }

    public function antiCheatRules(): MorphMany
    {
        return $this->morphMany(AntiCheatRule::class, 'ruleable');
    }

    public function getTotalPointsAttribute(): int
    {
        return $this->questions->sum('points');
    }

    public function canBeAttemptedBy(User $user): bool
    {
        if (!$this->allow_retake) {
            return !$this->attempts()->where('user_id', $user->id)->exists();
        }

        if ($this->max_attempts) {
            $attemptsCount = $this->attempts()->where('user_id', $user->id)->count();
            return $attemptsCount < $this->max_attempts;
        }

        return true;
    }

    public function getQuestionsForAttempt(User $user): \Illuminate\Database\Eloquent\Collection
    {
        if ($this->is_adaptive) {
            return $this->getAdaptiveQuestions($user);
        }

        $questions = $this->questions()->with('options')->get();
        
        if ($this->randomize_questions) {
            $questions = $questions->shuffle();
        }

        return $questions;
    }

    private function getAdaptiveQuestions(User $user): \Illuminate\Database\Eloquent\Collection
    {
        $previousAttempts = $this->attempts()
            ->where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->get();

        $userPerformance = $this->calculateUserPerformance($previousAttempts);
        $difficultyLevel = $this->determineDifficultyLevel($userPerformance);

        return $this->adaptiveQuestions()
            ->where('difficulty_level', $difficultyLevel)
            ->with('question')
            ->get()
            ->pluck('question');
    }

    private function calculateUserPerformance($attempts): array
    {
        if ($attempts->isEmpty()) {
            return [
                'average_score' => 0,
                'completion_time' => 0,
                'consecutive_correct' => 0
            ];
        }

        $totalScore = $attempts->sum('score');
        $totalQuestions = $attempts->sum(function ($attempt) {
            return count($attempt->answers);
        });

        $averageScore = $totalQuestions > 0 ? ($totalScore / $totalQuestions) * 100 : 0;

        $completionTimes = $attempts->map(function ($attempt) {
            return $attempt->submitted_at->diffInSeconds($attempt->started_at);
        });

        $consecutiveCorrect = $this->calculateConsecutiveCorrect($attempts);

        return [
            'average_score' => $averageScore,
            'completion_time' => $completionTimes->avg(),
            'consecutive_correct' => $consecutiveCorrect
        ];
    }

    private function calculateConsecutiveCorrect($attempts): int
    {
        $maxConsecutive = 0;
        $currentConsecutive = 0;

        foreach ($attempts as $attempt) {
            foreach ($attempt->answers as $answer) {
                if ($answer['is_correct']) {
                    $currentConsecutive++;
                    $maxConsecutive = max($maxConsecutive, $currentConsecutive);
                } else {
                    $currentConsecutive = 0;
                }
            }
        }

        return $maxConsecutive;
    }

    private function determineDifficultyLevel(array $performance): string
    {
        if ($performance['average_score'] >= 80 && $performance['consecutive_correct'] >= 5) {
            return 'hard';
        }

        if ($performance['average_score'] >= 60 || $performance['consecutive_correct'] >= 3) {
            return 'medium';
        }

        return 'easy';
    }

    public function startRealtimeSession(): RealtimeQuizSession
    {
        if ($this->realtimeSession) {
            throw new \Exception('Une session en temps réel est déjà en cours pour ce quiz.');
        }

        return $this->realtimeSession()->create([
            'scheduled_start' => now()->addMinutes(5),
            'participants' => [],
            'leaderboard' => []
        ]);
    }

    public function startCollaborativeSession(): CollaborativeQuizSession
    {
        if ($this->collaborativeSession) {
            throw new \Exception('Une session collaborative est déjà en cours pour ce quiz.');
        }

        return $this->collaborativeSession()->create([
            'started_at' => now(),
            'participants' => [],
            'group_answers' => []
        ]);
    }

    public function addMedia(string $type, string $url, ?string $altText = null, ?string $description = null): QuizMedia
    {
        if (!$this->allow_media) {
            throw new \Exception('Les médias ne sont pas autorisés pour ce quiz.');
        }

        return $this->media()->create([
            'type' => $type,
            'url' => $url,
            'alt_text' => $altText,
            'description' => $description
        ]);
    }

    public function enableAntiCheat(array $rules = []): void
    {
        $this->anti_cheat_enabled = true;
        $this->save();

        if (empty($rules)) {
            // Règles par défaut
            $rules = [
                [
                    'name' => 'Tab Switch Detection',
                    'type' => 'tab_switch',
                    'parameters' => ['max_switches' => 3]
                ],
                [
                    'name' => 'Copy-Paste Detection',
                    'type' => 'copy_paste',
                    'parameters' => ['max_events' => 0]
                ],
                [
                    'name' => 'Time Limit',
                    'type' => 'time_limit',
                    'parameters' => ['time_limit' => $this->time_limit * 60]
                ]
            ];
        }

        foreach ($rules as $rule) {
            $this->antiCheatRules()->create($rule);
        }
    }

    public function disableAntiCheat(): void
    {
        $this->anti_cheat_enabled = false;
        $this->save();
        $this->antiCheatRules()->delete();
    }

    // Relations directes au lieu de morphTo
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function latestAttemptByUser($userId)
    {
        return $this->attempts()->where('user_id', $userId)->latest()->first();
    }

    public function bestAttemptByUser($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->orderByDesc('score')
            ->first();
    }

    public function getTotalPoints()
    {
        return $this->questions->sum('points');
    }

    public function isPassedByUser($userId)
    {
        $bestAttempt = $this->bestAttemptByUser($userId);
        return $bestAttempt && $bestAttempt->score >= $this->passing_score;
    }

    public function isModuleQuiz()
    {
        return $this->quiz_type === 'module_end';
    }

    public function isCourseQuiz()
    {
        return $this->quiz_type === 'course_final';
    }

    // Vérifier si l'utilisateur peut accéder à ce quiz
    public function canBeAccessedByUser($userId)
    {
        if ($this->isModuleQuiz()) {
            // Pour un quiz de module, toutes les leçons du module doivent être complétées
            $module = $this->module;
            
            if (!$module) return false;
            
            return $module->allLessonsCompletedByUser($userId);
        }
        
        if ($this->isCourseQuiz()) {
            // Pour un quiz de cours, tous les quiz de modules requis doivent être réussis
            $course = $this->course;
            if (!$course) return false;

            foreach ($course->modules as $module) {
                if ($module->quiz && $module->quiz->is_required) {
                    if (!$module->quiz->isPassedByUser($userId)) {
                        return false;
                    }
                }
            }
            
            return true;
        }
        
        return false;
    }

    public function canBeAttemptedByUser($userId)
    {
        // Si le quiz a déjà été réussi, vérifier si c'était le dernier essai réussi
        if ($this->isPassedByUser($userId)) {
            $attempts = $this->attempts()
                ->where('user_id', $userId)
                ->orderBy('completed_at', 'desc')
                ->get();
            
            $lastAttempt = $attempts->first();
            if ($lastAttempt && $lastAttempt->passed) {
                return false; // Ne peut plus retenter si le dernier essai était réussi
            }
        }

        // Si max_attempts est 0, pas de limite
        if ($this->max_attempts === 0) {
            return true;
        }

        // Compter les tentatives de l'utilisateur
        $attemptCount = $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        return $attemptCount < $this->max_attempts;
    }

    public function getRemainingAttempts($userId)
    {
        if ($this->max_attempts === 0) {
            return -1; // -1 indique un nombre illimité de tentatives
        }

        $attemptCount = $this->attempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        return max(0, $this->max_attempts - $attemptCount);
    }

}

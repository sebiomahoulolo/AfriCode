<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AntiCheatViolation extends Model
{
    protected $fillable = [
        'quiz_attempt_id',
        'anti_cheat_rule_id',
        'violation_type',
        'violation_data',
        'detected_at',
        'is_verified',
        'admin_notes'
    ];

    protected $casts = [
        'violation_data' => 'array',
        'detected_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    /**
     * Get the quiz attempt that had the violation.
     */
    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    /**
     * Get the rule that was violated.
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(AntiCheatRule::class, 'anti_cheat_rule_id');
    }

    /**
     * Get the user who committed the violation.
     */
    public function user()
    {
        return $this->quizAttempt->user;
    }

    /**
     * Get the quiz where the violation occurred.
     */
    public function quiz()
    {
        return $this->quizAttempt->quiz;
    }

    /**
     * Get a human-readable description of the violation.
     */
    public function getDescription(): string
    {
        return match($this->violation_type) {
            'tab_switch' => sprintf(
                'Changement d\'onglet détecté %d fois',
                count($this->violation_data['tab_switches'] ?? [])
            ),
            'copy_paste' => sprintf(
                'Copier-coller détecté %d fois',
                count($this->violation_data['copy_paste_events'] ?? [])
            ),
            'time_limit' => sprintf(
                'Temps limite dépassé de %d minutes',
                ($this->violation_data['excess_time'] ?? 0) / 60
            ),
            default => 'Violation non définie'
        };
    }

    /**
     * Get the severity level of the violation.
     */
    public function getSeverityLevel(): string
    {
        return $this->rule->getSeverityLevel();
    }

    /**
     * Verify the violation.
     */
    public function verify(string $notes = null): void
    {
        $this->is_verified = true;
        $this->admin_notes = $notes;
        $this->save();
    }

    /**
     * Get the recommended action for this violation.
     */
    public function getRecommendedAction(): string
    {
        return match($this->violation_type) {
            'tab_switch' => 'Avertir l\'étudiant et surveiller les prochaines tentatives',
            'copy_paste' => 'Annuler la tentative et demander une explication',
            'time_limit' => 'Vérifier la validité de la tentative',
            default => 'Examiner manuellement'
        };
    }
} 
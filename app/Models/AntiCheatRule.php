<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AntiCheatRule extends Model
{
    protected $fillable = [
        'name',
        'type',
        'parameters',
        'is_active'
    ];

    protected $casts = [
        'parameters' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the violations for this rule.
     */
    public function violations(): HasMany
    {
        return $this->hasMany(AntiCheatViolation::class);
    }

    /**
     * Create a new tab switch rule.
     */
    public static function createTabSwitchRule(int $maxSwitches = 3): self
    {
        return self::create([
            'name' => 'Tab Switch Detection',
            'type' => 'tab_switch',
            'parameters' => [
                'max_switches' => $maxSwitches
            ]
        ]);
    }

    /**
     * Create a new copy-paste rule.
     */
    public static function createCopyPasteRule(int $maxEvents = 0): self
    {
        return self::create([
            'name' => 'Copy-Paste Detection',
            'type' => 'copy_paste',
            'parameters' => [
                'max_events' => $maxEvents
            ]
        ]);
    }

    /**
     * Create a new time limit rule.
     */
    public static function createTimeLimitRule(int $timeLimit): self
    {
        return self::create([
            'name' => 'Time Limit',
            'type' => 'time_limit',
            'parameters' => [
                'time_limit' => $timeLimit
            ]
        ]);
    }

    /**
     * Get the description of the rule.
     */
    public function getDescription(): string
    {
        return match($this->type) {
            'tab_switch' => sprintf(
                'Maximum %d changement(s) d\'onglet autorisé(s)',
                $this->parameters['max_switches']
            ),
            'copy_paste' => $this->parameters['max_events'] > 0
                ? sprintf('Maximum %d copier-coller autorisé(s)', $this->parameters['max_events'])
                : 'Copier-coller interdit',
            'time_limit' => sprintf(
                'Temps maximum de %d minutes',
                $this->parameters['time_limit'] / 60
            ),
            default => 'Règle non définie'
        };
    }

    /**
     * Get the severity level of the rule.
     */
    public function getSeverityLevel(): string
    {
        return match($this->type) {
            'tab_switch' => 'warning',
            'copy_paste' => 'error',
            'time_limit' => 'warning',
            default => 'info'
        };
    }
} 
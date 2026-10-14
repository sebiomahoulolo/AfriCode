<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class JobApplication extends Model
{
    protected $fillable = [
        'job_position_id',
        'nom_prenoms',
        'sexe',
        'email',
        'telephone',
        'date_naissance',
        'adresse',
        'niveau_etudes',
        'offer_source',
        'offer_source_other',
        'form_data',
        'test_score',
        'test_answers',
        'cv_path',
        'lettre_path',
        'other_documents',
        'status',
        'token',
        'submitted_at',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'form_data' => 'array',
        'test_answers' => 'array',
        'other_documents' => 'array',
        'submitted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (JobApplication $app) {
            if (empty($app->token)) {
                $app->token = Str::random(64);
            }
        });
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function getScoreLevel(): string
    {
        $s = $this->test_score;
        if ($s === null) {
            return 'non_évalué';
        }
        if ($s <= 4) {
            return 'insuffisant';
        }
        if ($s <= 6) {
            return 'moyen';
        }
        if ($s <= 8) {
            return 'bon';
        }
        return 'excellent';
    }
}

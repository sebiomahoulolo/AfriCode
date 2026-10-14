<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizMedia extends Model
{
    protected $fillable = [
        'quiz_id',
        'type',
        'url',
        'alt_text',
        'description'
    ];

    /**
     * Get the quiz that owns the media.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the HTML for displaying the media.
     */
    public function getDisplayHtml(): string
    {
        return match($this->type) {
            'image' => $this->getImageHtml(),
            'video' => $this->getVideoHtml(),
            'audio' => $this->getAudioHtml(),
            default => ''
        };
    }

    /**
     * Get HTML for image media.
     */
    private function getImageHtml(): string
    {
        return sprintf(
            '<img src="%s" alt="%s" class="max-w-full h-auto rounded-lg shadow-md">',
            $this->url,
            $this->alt_text ?? ''
        );
    }

    /**
     * Get HTML for video media.
     */
    private function getVideoHtml(): string
    {
        return sprintf(
            '<video controls class="max-w-full rounded-lg shadow-md">
                <source src="%s" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>',
            $this->url
        );
    }

    /**
     * Get HTML for audio media.
     */
    private function getAudioHtml(): string
    {
        return sprintf(
            '<audio controls class="w-full">
                <source src="%s" type="audio/mpeg">
                Votre navigateur ne supporte pas la lecture audio.
            </audio>',
            $this->url
        );
    }
} 
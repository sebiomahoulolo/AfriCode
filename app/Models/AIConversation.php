<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIConversation extends Model
{
    protected $table = 'ai_conversations';
    
    protected $fillable = [
        'user_id',
        'session_id',
        'messages',
        'context',
        'intent',
    ];

    protected $casts = [
        'messages' => 'array',
        'context' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addMessage(string $role, string $content): void
    {
        $messages = $this->messages ?? [];
        $messages[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => now()->toDateTimeString(),
        ];
        $this->update(['messages' => $messages]);
    }

    public function getContext(): array
    {
        return $this->context ?? [];
    }

    public function updateContext(array $context): void
    {
        $this->update(['context' => $context]);
    }
}



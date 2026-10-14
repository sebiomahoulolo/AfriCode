<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mention extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mentioned_by_id',
        'mentionable_type',
        'mentionable_id',
        'mention_text',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mentionedBy()
    {
        return $this->belongsTo(User::class, 'mentioned_by_id');
    }

    public function mentionable()
    {
        return $this->morphTo();
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Traite le contenu pour extraire et créer les mentions
     */
    public static function processMentions($content, $mentionable, $mentionedBy)
    {
        // Regex pour trouver les mentions @username
        preg_match_all('/@(\w+)/', $content, $matches);
        
        if (empty($matches[1])) {
            return [];
        }

        $mentions = [];
        foreach ($matches[1] as $username) {
            // Chercher l'utilisateur par nom d'utilisateur ou email
            $user = User::where('first_name', 'LIKE', "%{$username}%")
                       ->orWhere('last_name', 'LIKE', "%{$username}%")
                       ->orWhere('email', 'LIKE', "%{$username}%")
                       ->first();

            if ($user && $user->id !== $mentionedBy->id) {
                $mention = self::create([
                    'user_id' => $user->id,
                    'mentioned_by_id' => $mentionedBy->id,
                    'mentionable_type' => get_class($mentionable),
                    'mentionable_id' => $mentionable->id,
                    'mention_text' => "@{$username}"
                ]);

                $mentions[] = $mention;

                // Créer aussi une notification de forum
                ForumNotification::create([
                    'user_id' => $user->id,
                    'forum_post_id' => $mentionable->id,
                    'type' => 'mention'
                ]);
            }
        }

        return $mentions;
    }
}

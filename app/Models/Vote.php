<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'voteable_type',
        'voteable_id',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voteable()
    {
        return $this->morphTo();
    }

    public function scopeUpvotes($query)
    {
        return $query->where('type', 'upvote');
    }

    public function scopeDownvotes($query)
    {
        return $query->where('type', 'downvote');
    }

    /**
     * Toggle le vote d'un utilisateur sur un post/commentaire
     */
    public static function toggleVote($user, $voteable, $type = 'upvote')
    {
        $existingVote = self::where('user_id', $user->id)
            ->where('voteable_type', get_class($voteable))
            ->where('voteable_id', $voteable->id)
            ->first();

        if ($existingVote) {
            if ($existingVote->type === $type) {
                // Même type de vote, on supprime le vote
                $existingVote->delete();
                return 'removed';
            } else {
                // Type différent, on change le vote
                $existingVote->update(['type' => $type]);
                return 'changed';
            }
        } else {
            // Nouveau vote
            self::create([
                'user_id' => $user->id,
                'voteable_type' => get_class($voteable),
                'voteable_id' => $voteable->id,
                'type' => $type
            ]);
            return 'added';
        }
    }

    /**
     * Obtenir le score net d'un post/commentaire
     */
    public static function getScore($voteable)
    {
        $upvotes = self::where('voteable_type', get_class($voteable))
            ->where('voteable_id', $voteable->id)
            ->where('type', 'upvote')
            ->count();

        $downvotes = self::where('voteable_type', get_class($voteable))
            ->where('voteable_id', $voteable->id)
            ->where('type', 'downvote')
            ->count();

        return $upvotes - $downvotes;
    }
}

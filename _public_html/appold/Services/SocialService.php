<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SocialService
{
    public function createPost($user, $data)
    {
        $post = new Post([
            'user_id' => $user->id,
            'content' => $data['content'],
            'type' => $data['type'] ?? 'text',
            'visibility' => $data['visibility'] ?? 'public'
        ]);

        if (isset($data['course_id'])) {
            $post->course_id = $data['course_id'];
        }

        if (isset($data['media'])) {
            $post->media_url = $this->uploadMedia($data['media']);
        }

        $post->save();

        // Notifier les abonnés
        $this->notifyFollowers($user, 'new_post', [
            'post_id' => $post->id,
            'user_name' => $user->name
        ]);

        return $post;
    }

    public function createComment($user, $post, $content)
    {
        $comment = new Comment([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => $content
        ]);

        $comment->save();

        // Notifier l'auteur du post
        if ($post->user_id !== $user->id) {
            $this->notifyUser($post->user, 'new_comment', [
                'comment_id' => $comment->id,
                'post_id' => $post->id,
                'user_name' => $user->name
            ]);
        }

        return $comment;
    }

    public function toggleLike($user, $likeable)
    {
        $like = $likeable->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return false;
        }

        $like = new Like([
            'user_id' => $user->id,
            'likeable_id' => $likeable->id,
            'likeable_type' => get_class($likeable)
        ]);

        $like->save();

        // Notifier l'auteur si c'est un post ou un commentaire
        if ($likeable instanceof Post || $likeable instanceof Comment) {
            if ($likeable->user_id !== $user->id) {
                $this->notifyUser($likeable->user, 'new_like', [
                    'likeable_id' => $likeable->id,
                    'likeable_type' => get_class($likeable),
                    'user_name' => $user->name
                ]);
            }
        }

        return true;
    }

    public function followUser($follower, $following)
    {
        if ($follower->id === $following->id) {
            throw new \Exception('Vous ne pouvez pas vous suivre vous-même');
        }

        if ($follower->isFollowing($following)) {
            $follower->following()->detach($following->id);
            return false;
        }

        $follower->following()->attach($following->id);

        // Notifier l'utilisateur suivi
        $this->notifyUser($following, 'new_follower', [
            'follower_id' => $follower->id,
            'follower_name' => $follower->name
        ]);

        return true;
    }

    public function getFeed($user, $page = 1)
    {
        $cacheKey = "user_feed_{$user->id}_page_{$page}";
        
        return Cache::remember($cacheKey, 300, function () use ($user) {
            return Post::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('user_id', $user->following()->pluck('id'))
                    ->orWhere('visibility', 'public');
            })
            ->with(['user', 'likes', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        });
    }

    public function shareCourse($user, $course, $platform)
    {
        $shareData = [
            'title' => $course->title,
            'description' => $course->description,
            'url' => route('courses.show', $course),
            'image' => $course->thumbnail_url
        ];

        switch ($platform) {
            case 'facebook':
                return $this->shareToFacebook($shareData);
            case 'twitter':
                return $this->shareToTwitter($shareData);
            case 'linkedin':
                return $this->shareToLinkedIn($shareData);
            default:
                throw new \Exception('Plateforme non supportée');
        }
    }

    protected function shareToFacebook($data)
    {
        // Intégration avec l'API Facebook
        return "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($data['url']);
    }

    protected function shareToTwitter($data)
    {
        // Intégration avec l'API Twitter
        $text = urlencode($data['title'] . ' - ' . $data['description']);
        return "https://twitter.com/intent/tweet?text={$text}&url=" . urlencode($data['url']);
    }

    protected function shareToLinkedIn($data)
    {
        // Intégration avec l'API LinkedIn
        return "https://www.linkedin.com/shareArticle?mini=true&url=" . urlencode($data['url']) . 
               "&title=" . urlencode($data['title']) . 
               "&summary=" . urlencode($data['description']);
    }

    protected function uploadMedia($file)
    {
        // Logique d'upload de média
        return $file->store('social-media');
    }

    protected function notifyUser($user, $type, $data)
    {
        app(NotificationService::class)->send($user, $type, $data);
    }

    protected function notifyFollowers($user, $type, $data)
    {
        $followers = $user->followers;
        foreach ($followers as $follower) {
            $this->notifyUser($follower, $type, $data);
        }
    }
} 
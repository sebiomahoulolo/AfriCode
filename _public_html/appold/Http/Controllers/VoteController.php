<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Voter pour un post du forum
     */
    public function votePost(Request $request, ForumPost $post)
    {
        $request->validate([
            'type' => 'required|in:upvote,downvote'
        ]);

        $user = Auth::user();
        $type = $request->input('type');

        $result = Vote::toggleVote($user, $post, $type);

        $score = $post->score;
        $userVote = $post->getUserVote($user);

        return response()->json([
            'success' => true,
            'action' => $result,
            'score' => $score,
            'user_vote' => $userVote ? $userVote->type : null,
            'upvotes' => $post->upvotes()->count(),
            'downvotes' => $post->downvotes()->count()
        ]);
    }

    /**
     * Obtenir le score d'un post
     */
    public function getScore(ForumPost $post)
    {
        $user = Auth::user();
        $userVote = $post->getUserVote($user);

        return response()->json([
            'score' => $post->score,
            'upvotes' => $post->upvotes()->count(),
            'downvotes' => $post->downvotes()->count(),
            'user_vote' => $userVote ? $userVote->type : null
        ]);
    }
}

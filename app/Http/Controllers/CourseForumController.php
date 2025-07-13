<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseForum;
use App\Models\ForumPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseForumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forums = CourseForum::with('user', 'course')->orderByDesc('created_at')->paginate(15);
        return view('pages.forumapp', compact('forums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.forum_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $forum = CourseForum::create([
            'course_id' => $request->course_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);
        return redirect()->route('forum.show', $forum->id)->with('success', 'Sujet créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $forum = CourseForum::with(['user', 'course', 'posts.user'])->findOrFail($id);
        $posts = $forum->posts()->whereNull('parent_post_id')->with('replies.user')->orderBy('created_at')->get();
        return view('pages.forum_show', compact('forum', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $forum = CourseForum::findOrFail($id);
        if ($forum->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('forum.show', $forum->id)->with('error', 'Non autorisé.');
        }
        return view('pages.forum_edit', compact('forum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $forum = CourseForum::findOrFail($id);
        if ($forum->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('forum.show', $forum->id)->with('error', 'Non autorisé.');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $forum->update($request->only('title', 'content'));
        return redirect()->route('forum.show', $forum->id)->with('success', 'Sujet modifié.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $forum = CourseForum::findOrFail($id);
        if ($forum->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('forum.show', $forum->id)->with('error', 'Non autorisé.');
        }
        $forum->delete();
        return redirect()->route('forum.index')->with('success', 'Sujet supprimé.');
    }

    public function reply(Request $request, $forumId)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_post_id' => 'nullable|exists:forum_posts,id',
        ]);
        $forum = CourseForum::findOrFail($forumId);
        ForumPost::create([
            'course_forum_id' => $forum->id,
            'user_id' => Auth::id(),
            'parent_post_id' => $request->filled('parent_post_id') ? $request->parent_post_id : null,
            'content' => $request->content,
        ]);
        return redirect()->route('forum.show', $forum->id)->with('success', 'Réponse publiée.');
    }

    // Liste des sujets pour le forum des apprenants
    public function indexApprenants()
    {
        $forums = CourseForum::where('type', 'apprenant')->with('user', 'course')->orderByDesc('created_at')->paginate(15);
        return view('pages.forumapp', compact('forums'));
    }

    // Liste des sujets pour le forum des experts
    public function indexExperts()
    {
        $forums = CourseForum::where('type', 'expert')->with('user', 'course')->orderByDesc('created_at')->paginate(15);
        return view('pages.forumexp', compact('forums'));
    }

    /**
     * Création de sujet via AJAX (forumapp)
     */
    public function storeAjax(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Requête non autorisée.'], 403);
        }
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $forum = CourseForum::create([
            'course_id' => $request->course_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);
        $forum->load('user', 'course');
        return response()->json([
            'success' => true,
            'message' => 'Sujet créé avec succès !',
            'forum' => [
                'id' => $forum->id,
                'title' => $forum->title,
                'user' => [
                    'id' => $forum->user->id,
                    'first_name' => $forum->user->first_name,
                    'last_name' => $forum->user->last_name,
                ],
                'created_at' => $forum->created_at->diffForHumans(),
                'course' => [
                    'title' => $forum->course->title ?? 'Général',
                ],
                'replies' => 0,
                'views' => 0,
            ]
        ]);
    }

    /**
     * API : Liste des sujets (recherche, catégorie, pagination)
     */
    public function apiTopics(Request $request)
    {
        $query = CourseForum::with('user', 'course');
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%$search%")
                  ->orWhere('content', 'like', "%$search%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%") ;
                  });
        }
        if ($cat = $request->input('category')) {
            $query->where('course_id', $cat);
        }
        $forums = $query->orderByDesc('created_at')->paginate(10);
        $data = $forums->map(function($forum) {
            $lastPost = $forum->posts()->latest()->first();
            return [
                'id' => $forum->id,
                'title' => $forum->title,
                'user' => [
                    'id' => $forum->user->id,
                    'first_name' => $forum->user->first_name,
                    'last_name' => $forum->user->last_name,
                ],
                'created_at' => $forum->created_at->diffForHumans(),
                'course' => [
                    'id' => $forum->course->id ?? null,
                    'title' => $forum->course->title ?? 'Général',
                ],
                'replies' => $forum->getTotalRepliesCount(),
                'views' => $forum->views ?? 0,
                'last_post' => $lastPost ? [
                    'user' => [
                        'id' => $lastPost->user->id ?? null,
                        'first_name' => $lastPost->user->first_name ?? 'Utilisateur',
                        'last_name' => $lastPost->user->last_name ?? '',
                    ],
                    'created_at' => $lastPost->created_at->diffForHumans(),
                ] : null,
            ];
        });
        return response()->json([
            'success' => true,
            'forums' => $data,
            'pagination' => [
                'current_page' => $forums->currentPage(),
                'last_page' => $forums->lastPage(),
                'per_page' => $forums->perPage(),
                'total' => $forums->total(),
            ]
        ]);
    }

    /**
     * API : Détail d'un sujet (titre, contenu, auteur, date, réponses)
     */
    public function apiTopicDetail($id)
    {
        $forum = CourseForum::with(['user', 'course', 'posts.user', 'posts.replies.user'])->findOrFail($id);
        $replies = $forum->posts()->whereNull('parent_post_id')->with('user', 'replies.user')->orderBy('created_at')->get();
        return response()->json([
            'success' => true,
            'forum' => [
                'id' => $forum->id,
                'title' => $forum->title,
                'content' => $forum->content,
                'user' => [
                    'id' => $forum->user->id,
                    'first_name' => $forum->user->first_name,
                    'last_name' => $forum->user->last_name,
                ],
                'created_at' => $forum->created_at->diffForHumans(),
                'course' => [
                    'id' => $forum->course->id ?? null,
                    'title' => $forum->course->title ?? 'Général',
                ],
            ],
            'replies' => $replies->map(function($reply) {
                return [
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'user' => [
                        'id' => $reply->user->id ?? null,
                        'first_name' => $reply->user->first_name ?? 'Utilisateur',
                        'last_name' => $reply->user->last_name ?? '',
                    ],
                    'created_at' => $reply->created_at->diffForHumans(),
                    'replies' => $reply->replies->map(function($sub) {
                        return [
                            'id' => $sub->id,
                            'content' => $sub->content,
                            'user' => [
                                'id' => $sub->user->id ?? null,
                                'first_name' => $sub->user->first_name ?? 'Utilisateur',
                                'last_name' => $sub->user->last_name ?? '',
                            ],
                            'created_at' => $sub->created_at->diffForHumans(),
                        ];
                    }),
                ];
            }),
        ]);
    }

    /**
     * API : Répondre à un sujet (AJAX)
     */
    public function apiReply($id, Request $request)
    {
        $forum = CourseForum::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $reply = $forum->posts()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);
        $reply->load('user');
        return response()->json([
            'success' => true,
            'message' => 'Réponse publiée avec succès !',
            'reply' => [
                'id' => $reply->id,
                'content' => $reply->content,
                'user' => [
                    'id' => $reply->user->id ?? null,
                    'first_name' => $reply->user->first_name ?? 'Utilisateur',
                    'last_name' => $reply->user->last_name ?? '',
                ],
                'created_at' => $reply->created_at->diffForHumans(),
            ]
        ]);
    }
}

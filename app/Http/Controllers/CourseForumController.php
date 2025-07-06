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
        return view('pages.forumexp', compact('forum', 'posts'));
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
}

<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\FaqArticle;
use App\Models\FaqTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    // Tickets de support
    public function tickets()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->with(['assignedTo', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('support.tickets.index', compact('tickets'));
    }

    public function createTicket()
    {
        return view('support.tickets.create');
    }

    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'priority' => 'required|string|in:low,medium,high,urgent'
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'open'
        ]);

        return redirect()->route('support.tickets.show', $ticket)
            ->with('success', 'Ticket créé avec succès');
    }

    public function showTicket(SupportTicket $ticket)
    {
        $this->authorize('view', $ticket);
        
        $ticket->load(['replies.user', 'attachments']);
        return view('support.tickets.show', compact('ticket'));
    }

    public function replyTicket(Request $request, SupportTicket $ticket)
    {
        $this->authorize('reply', $ticket);

        $validated = $request->validate([
            'content' => 'required|string',
            'is_private' => 'boolean'
        ]);

        $reply = $ticket->replies()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'is_private' => $validated['is_private'] ?? false,
            'is_staff_reply' => Auth::user()->isStaff()
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $reply->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $file->store('ticket-attachments'),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        }

        return redirect()->back()->with('success', 'Réponse ajoutée avec succès');
    }

    // FAQ
    public function faq()
    {
        $categories = FaqArticle::where('is_published', true)
            ->select('category')
            ->distinct()
            ->get();

        $articles = FaqArticle::where('is_published', true)
            ->with('tags')
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        return view('support.faq.index', compact('categories', 'articles'));
    }

    public function showFaqArticle(FaqArticle $article)
    {
        if (!$article->is_published) {
            abort(404);
        }

        $article->increment('views_count');
        $relatedArticles = $article->relatedArticles()
            ->where('is_published', true)
            ->take(5)
            ->get();

        return view('support.faq.show', compact('article', 'relatedArticles'));
    }

    public function markFaqHelpful(FaqArticle $article)
    {
        $article->increment('helpful_count');
        return response()->json(['success' => true]);
    }

    public function markFaqNotHelpful(FaqArticle $article)
    {
        $article->increment('not_helpful_count');
        return response()->json(['success' => true]);
    }

    // Administration du support
    public function adminTickets()
    {
        $this->authorize('manageSupport', Auth::user());

        $tickets = SupportTicket::with(['user', 'assignedTo', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.support.tickets.index', compact('tickets'));
    }

    public function assignTicket(Request $request, SupportTicket $ticket)
    {
        $this->authorize('manageSupport', Auth::user());

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $ticket->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => 'in_progress'
        ]);

        return redirect()->back()->with('success', 'Ticket assigné avec succès');
    }

    public function updateTicketStatus(Request $request, SupportTicket $ticket)
    {
        $this->authorize('manageSupport', Auth::user());

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);

        $ticket->update([
            'status' => $validated['status'],
            'resolved_at' => $validated['status'] === 'resolved' ? now() : null,
            'closed_at' => $validated['status'] === 'closed' ? now() : null
        ]);

        return redirect()->back()->with('success', 'Statut du ticket mis à jour');
    }
} 
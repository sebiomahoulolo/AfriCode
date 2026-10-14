<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Retourne le nombre de non-lues + les 5 dernières (AJAX polling). */
    public function count(): JsonResponse
    {
        try {
            $userId = Auth::id();
            $unread = Notification::where('user_id', $userId)->where('read', false)->count();

            $items = Notification::where('user_id', $userId)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get()
                ->map(fn($n) => [
                    'id'          => $n->id,
                    'title'       => $n->title,
                    'message'     => $n->message,
                    'icon'        => $n->icon ?? 'bell',
                    'color'       => $n->color ?? 'primary',
                    'action_url'  => $n->action_url,
                    'read'        => (bool) $n->read,
                    'time_ago'    => $n->created_at?->diffForHumans() ?? '',
                ]);

            return response()->json(['unread' => $unread, 'items' => $items]);
        } catch (\Exception $e) {
            return response()->json(['unread' => 0, 'items' => []]);
        }
    }

    /** Marque une notification comme lue (AJAX). */
    public function markRead(int $id): JsonResponse
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['read' => true, 'read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    /** Marque toutes les notifications de l'utilisateur comme lues. */
    public function markAllRead(Request $request): JsonResponse|RedirectResponse
    {
        Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true, 'read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /** Page complète des notifications pour les apprenants. */
    public function apprenantIndex(): View
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('apprenants.notifications.index', compact('notifications'));
    }

    /** Page complète des notifications pour les formateurs. */
    public function formateurIndex(): View
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('formateurs.notifications.index', compact('notifications'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushNotificationJob;
use App\Models\PushCampaign;
use App\Models\Course;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PushCampaignController extends Controller
{
    public function index()
    {
        $campaigns = PushCampaign::with('creator')
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'total'       => PushCampaign::count(),
            'sent'        => PushCampaign::where('status', 'sent')->count(),
            'draft'       => PushCampaign::where('status', 'draft')->count(),
            'scheduled'   => PushCampaign::where('status', 'scheduled')->count(),
            'subscribers' => DB::table('push_subscriptions')->count(),
        ];

        return view('admin.push-campaigns.index', compact('campaigns', 'stats'));
    }

    public function create()
    {
        $courses = Course::select('id', 'title')->orderBy('title')->get();
        return view('admin.push-campaigns.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:100',
            'body'             => 'required|string|max:255',
            'url'              => 'nullable|string|max:512',
            'tag'              => 'nullable|string|max:100',
            'target_type'      => 'required|in:all,role,course_enrolled,inactive_days',
            'target_role'      => 'nullable|string|in:apprenant,formateur',
            'target_course_id' => 'nullable|integer|exists:courses,id',
            'target_days'      => 'nullable|integer|min:1|max:365',
            'scheduled_at'     => 'nullable|date|after:now',
            'send_now'         => 'nullable|boolean',
        ]);

        $targetValue = match ($data['target_type']) {
            'role'            => ['role'      => $data['target_role'] ?? 'apprenant'],
            'course_enrolled' => ['course_id' => (int)($data['target_course_id'] ?? 0)],
            'inactive_days'   => ['days'      => (int)($data['target_days'] ?? 7)],
            default           => null,
        };

        $campaign = PushCampaign::create([
            'title'        => $data['title'],
            'body'         => $data['body'],
            'url'          => $data['url'] ?? '/',
            'tag'          => $data['tag'] ?? 'africode-campaign',
            'target_type'  => $data['target_type'],
            'target_value' => $targetValue,
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'status'       => $data['scheduled_at'] ? 'scheduled' : 'draft',
            'created_by'   => auth()->user()?->id,
        ]);

        if ($request->boolean('send_now')) {
            return $this->doSend($campaign);
        }

        return redirect()->route('admin.push-campaigns.index')
            ->with('success', 'Campagne créée avec succès.');
    }

    public function show(PushCampaign $pushCampaign)
    {
        return view('admin.push-campaigns.show', [
            'campaign' => $pushCampaign->load('creator'),
        ]);
    }

    public function send(PushCampaign $pushCampaign)
    {
        if (in_array($pushCampaign->status, ['sending', 'sent'])) {
            return back()->with('error', 'Cette campagne a déjà été envoyée.');
        }

        return $this->doSend($pushCampaign);
    }

    public function destroy(PushCampaign $pushCampaign)
    {
        if ($pushCampaign->status === 'sending') {
            return back()->with('error', 'Impossible de supprimer une campagne en cours d\'envoi.');
        }

        $pushCampaign->delete();

        return redirect()->route('admin.push-campaigns.index')
            ->with('success', 'Campagne supprimée.');
    }

    // ── Envoi ────────────────────────────────────────────────────────────────

    private function doSend(PushCampaign $campaign)
    {
        try {
            $campaign->update(['status' => 'sending']);

            $payload = PushNotificationService::payload(
                $campaign->title,
                $campaign->body,
                $campaign->url ?: '/',
                '/images/icons/africode-192.png',
                $campaign->tag ?: 'africode-campaign'
            );

            // Calcul des userIds (requêtes DB rapides, synchrones)
            $userIds = match ($campaign->target_type) {
                'role'            => $this->getUserIdsByRole($campaign->target_value['role'] ?? 'apprenant'),
                'course_enrolled' => $this->getUserIdsByCourse($campaign->target_value['course_id'] ?? 0),
                'inactive_days'   => $this->getUserIdsByInactivity($campaign->target_value['days'] ?? 7),
                default           => null, // null = tous les abonnés dans le Job
            };

            // Dispatch asynchrone — le Job met à jour le statut après envoi
            SendPushNotificationJob::dispatch($payload, $userIds, $campaign->id)
                ->onQueue('notifications');

            return redirect()->route('admin.push-campaigns.show', $campaign)
                ->with('success', 'Campagne mise en file d\'envoi. Le statut sera mis à jour automatiquement.');

        } catch (\Throwable $e) {
            Log::error('Push campaign dispatch failed', [
                'id'    => $campaign->id,
                'error' => $e->getMessage(),
            ]);
            $campaign->update(['status' => 'failed']);

            return back()->with('error', 'Erreur lors de la mise en file : ' . $e->getMessage());
        }
    }

    // ── Helpers de ciblage (requêtes rapides) ────────────────────────────────

    private function getUserIdsByRole(string $role): array
    {
        $subscribedIds = DB::table('push_subscriptions')->pluck('user_id');

        return DB::table('users')
            ->where('role', $role)
            ->whereIn('id', $subscribedIds)
            ->pluck('id')
            ->toArray();
    }

    private function getUserIdsByCourse(int $courseId): array
    {
        if (!$courseId) return [];

        $subscribedIds = DB::table('push_subscriptions')->pluck('user_id');

        return DB::table('enrollments')
            ->where('course_id', $courseId)
            ->whereIn('user_id', $subscribedIds)
            ->pluck('user_id')
            ->unique()
            ->values()
            ->toArray();
    }

    private function getUserIdsByInactivity(int $days): array
    {
        $cutoff = now()->subDays($days);

        $activeUserIds = DB::table('lesson_completions')
            ->where('completed_at', '>=', $cutoff)
            ->pluck('user_id')
            ->unique();

        $subscribedIds = DB::table('push_subscriptions')->pluck('user_id');

        return DB::table('users')
            ->where('role', 'apprenant')
            ->whereIn('id', $subscribedIds)
            ->whereNotIn('id', $activeUserIds)
            ->pluck('id')
            ->unique()
            ->values()
            ->toArray();
    }
}

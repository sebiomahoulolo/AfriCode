<?php

namespace App\Console\Commands;

use App\Models\PushCampaign;
use App\Services\PushNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendScheduledPushCampaigns extends Command
{
    protected $signature   = 'push:send-scheduled';
    protected $description = 'Envoie les campagnes push planifiées dont la date est atteinte';

    public function handle(): int
    {
        $campaigns = PushCampaign::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($campaigns->isEmpty()) {
            $this->line('Aucune campagne planifiée à envoyer.');
            return self::SUCCESS;
        }

        $svc = new PushNotificationService();

        foreach ($campaigns as $campaign) {
            $this->line("Envoi campagne #{$campaign->id} : {$campaign->title}");
            $campaign->update(['status' => 'sending']);

            try {
                $payload = PushNotificationService::payload(
                    $campaign->title,
                    $campaign->body,
                    $campaign->url ?: '/',
                    '/images/icons/africode-192.png',
                    $campaign->tag ?: 'africode-campaign'
                );

                $count = match ($campaign->target_type) {
                    'role' => $this->sendToRole(
                        $svc, $payload,
                        $campaign->target_value['role'] ?? 'apprenant'
                    ),
                    'course_enrolled' => $this->sendToCourseEnrolled(
                        $svc, $payload,
                        $campaign->target_value['course_id'] ?? 0
                    ),
                    'inactive_days' => $this->sendToInactive(
                        $svc, $payload,
                        $campaign->target_value['days'] ?? 7
                    ),
                    default => $this->sendToAll($svc, $payload),
                };

                $campaign->update([
                    'status'           => 'sent',
                    'sent_at'          => now(),
                    'recipients_count' => $count,
                ]);

                $this->info("  ✔ Envoyée à {$count} abonné(s).");
                Log::info("Campagne push planifiée envoyée", [
                    'id'         => $campaign->id,
                    'title'      => $campaign->title,
                    'recipients' => $count,
                ]);

            } catch (\Throwable $e) {
                $campaign->update(['status' => 'failed']);
                $this->error("  ✘ Erreur : " . $e->getMessage());
                Log::error("Échec campagne push planifiée", [
                    'id'    => $campaign->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("{$campaigns->count()} campagne(s) traitée(s).");
        return self::SUCCESS;
    }

    private function sendToAll(PushNotificationService $svc, array $payload): int
    {
        $userIds = $svc->getUserIdsWithPreference('push_updates');
        if (!empty($userIds)) $svc->sendToUsers($userIds, $payload);
        return count($userIds);
    }

    private function sendToRole(PushNotificationService $svc, array $payload, string $role): int
    {
        $allIds = $svc->getUserIdsWithPreference('push_updates');
        $userIds = DB::table('users')
            ->where('role', $role)
            ->whereIn('id', $allIds)
            ->pluck('id')
            ->toArray();
        if (!empty($userIds)) $svc->sendToUsers($userIds, $payload);
        return count($userIds);
    }

    private function sendToCourseEnrolled(PushNotificationService $svc, array $payload, int $courseId): int
    {
        $allIds = $svc->getUserIdsWithPreference('push_updates');
        $userIds = DB::table('enrollments')
            ->where('course_id', $courseId)
            ->whereIn('user_id', $allIds)
            ->pluck('user_id')
            ->unique()
            ->toArray();
        if (!empty($userIds)) $svc->sendToUsers($userIds, $payload);
        return count($userIds);
    }

    private function sendToInactive(PushNotificationService $svc, array $payload, int $days): int
    {
        $allIds  = $svc->getUserIdsWithPreference('push_reminders');
        $cutoff  = now()->subDays($days);
        $activeIds = DB::table('lesson_completions')
            ->where('completed_at', '>=', $cutoff)
            ->pluck('user_id')
            ->unique();
        $userIds = DB::table('enrollments')
            ->whereIn('user_id', $allIds)
            ->whereNotIn('user_id', $activeIds)
            ->pluck('user_id')
            ->unique()
            ->toArray();
        if (!empty($userIds)) $svc->sendToUsers($userIds, $payload);
        return count($userIds);
    }
}

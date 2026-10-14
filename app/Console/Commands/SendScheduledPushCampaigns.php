<?php

namespace App\Console\Commands;

use App\Jobs\SendPushNotificationJob;
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
            $this->line("Dispatch campagne #{$campaign->id} : {$campaign->title}");
            $campaign->update(['status' => 'sending']);

            try {
                $payload = PushNotificationService::payload(
                    $campaign->title,
                    $campaign->body,
                    $campaign->url ?: '/',
                    '/images/icons/africode-192.png',
                    $campaign->tag ?: 'africode-campaign'
                );

                // Calcul des userIds ciblés avant dispatch (rapide, synchrone)
                $userIds = match ($campaign->target_type) {
                    'role'            => $this->getUserIdsByRole(
                        $svc,
                        $campaign->target_value['role'] ?? 'apprenant'
                    ),
                    'course_enrolled' => $this->getUserIdsByCourse(
                        $svc,
                        $campaign->target_value['course_id'] ?? 0
                    ),
                    'inactive_days'   => $this->getUserIdsByInactivity(
                        $svc,
                        $campaign->target_value['days'] ?? 7
                    ),
                    default           => $svc->getUserIdsWithPreference('push_updates'),
                };

                // Dispatch asynchrone — le Job gère l'envoi et met à jour le statut
                SendPushNotificationJob::dispatch($payload, $userIds, $campaign->id)
                    ->onQueue('notifications');

                $this->info("  ✔ Campagne #{$campaign->id} mise en queue ({$campaign->target_type}).");
                Log::info('Campagne push planifiée dispatchée', [
                    'id'         => $campaign->id,
                    'title'      => $campaign->title,
                    'target'     => $campaign->target_type,
                    'user_count' => count($userIds ?? []),
                ]);

            } catch (\Throwable $e) {
                $campaign->update(['status' => 'failed']);
                $this->error("  ✘ Erreur : " . $e->getMessage());
                Log::error('Échec dispatch campagne push planifiée', [
                    'id'    => $campaign->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("{$campaigns->count()} campagne(s) dispatchée(s) en queue.");
        return self::SUCCESS;
    }

    private function getUserIdsByRole(PushNotificationService $svc, string $role): array
    {
        $allIds = $svc->getUserIdsWithPreference('push_updates');
        return DB::table('users')
            ->where('role', $role)
            ->whereIn('id', $allIds)
            ->pluck('id')
            ->toArray();
    }

    private function getUserIdsByCourse(PushNotificationService $svc, int $courseId): array
    {
        $allIds = $svc->getUserIdsWithPreference('push_updates');
        return DB::table('enrollments')
            ->where('course_id', $courseId)
            ->whereIn('user_id', $allIds)
            ->pluck('user_id')
            ->unique()
            ->toArray();
    }

    private function getUserIdsByInactivity(PushNotificationService $svc, int $days): array
    {
        $allIds   = $svc->getUserIdsWithPreference('push_reminders');
        $cutoff   = now()->subDays($days);
        $activeIds = DB::table('lesson_completions')
            ->where('completed_at', '>=', $cutoff)
            ->pluck('user_id')
            ->unique();

        return DB::table('enrollments')
            ->whereIn('user_id', $allIds)
            ->whereNotIn('user_id', $activeIds)
            ->pluck('user_id')
            ->unique()
            ->toArray();
    }
}

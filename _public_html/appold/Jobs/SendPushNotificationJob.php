<?php

namespace App\Jobs;

use App\Services\PushNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 180;

    /**
     * @param array      $payload     Données de la notification (title, body, url, icon, tag)
     * @param array|null $userIds     null = tous les abonnés, array = utilisateurs ciblés
     * @param int|null   $campaignId  ID de la campagne à mettre à jour après envoi
     */
    public function __construct(
        public readonly array  $payload,
        public readonly ?array $userIds    = null,
        public readonly ?int   $campaignId = null,
    ) {}

    public function handle(PushNotificationService $push): void
    {
        try {
            if ($this->userIds === null) {
                $push->sendToAll($this->payload);
                $count = DB::table('push_subscriptions')->count();
            } else {
                if (empty($this->userIds)) {
                    $count = 0;
                } else {
                    $push->sendToUsers($this->userIds, $this->payload);
                    $count = count($this->userIds);
                }
            }

            Log::info('Push envoyé', [
                'title'      => $this->payload['title'],
                'recipients' => $count,
                'campaign'   => $this->campaignId,
            ]);

            if ($this->campaignId) {
                DB::table('push_campaigns')->where('id', $this->campaignId)->update([
                    'status'           => 'sent',
                    'sent_at'          => now(),
                    'recipients_count' => $count,
                    'updated_at'       => now(),
                ]);
            }

        } catch (\Throwable $e) {
            Log::error('Erreur envoi push campaign', [
                'campaign' => $this->campaignId,
                'error'    => $e->getMessage(),
                'payload'  => $this->payload,
            ]);

            if ($this->campaignId) {
                DB::table('push_campaigns')->where('id', $this->campaignId)->update([
                    'status'     => 'failed',
                    'updated_at' => now(),
                ]);
            }

            throw $e;
        }
    }

    public function failed(\Throwable $e): void
    {
        if ($this->campaignId) {
            DB::table('push_campaigns')->where('id', $this->campaignId)->update([
                'status'     => 'failed',
                'updated_at' => now(),
            ]);
        }

        Log::error('SendPushNotificationJob a échoué définitivement', [
            'campaign' => $this->campaignId,
            'error'    => $e->getMessage(),
        ]);
    }
}

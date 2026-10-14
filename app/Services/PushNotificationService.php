<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationService
{
    private WebPush $webPush;

    public function __construct()
    {
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject'    => config('services.vapid.subject'),
                'publicKey'  => config('services.vapid.public_key'),
                'privateKey' => config('services.vapid.private_key'),
            ],
        ]);

        $this->webPush->setDefaultOptions([
            'TTL'     => 2419200, // 28 jours
            'urgency' => 'normal',
        ]);
    }

    /**
     * Envoie une notification à TOUS les abonnés.
     */
    public function sendToAll(array $payload): void
    {
        $subscriptions = DB::table('push_subscriptions')->get();
        $this->dispatch($subscriptions, $payload);
    }

    /**
     * Envoie une notification aux abonnés d'une liste d'utilisateurs.
     */
    public function sendToUsers(array $userIds, array $payload): void
    {
        $subscriptions = DB::table('push_subscriptions')
            ->whereIn('user_id', $userIds)
            ->get();
        $this->dispatch($subscriptions, $payload);
    }

    /**
     * Envoie une notification à un utilisateur précis.
     */
    public function sendToUser(int $userId, array $payload): void
    {
        $this->sendToUsers([$userId], $payload);
    }

    /**
     * Retourne les IDs des abonnés push dont la préférence $prefKey est activée
     * (valeur par défaut = true si la préférence n'est pas définie).
     */
    public function getUserIdsWithPreference(string $prefKey): array
    {
        return DB::table('push_subscriptions')
            ->leftJoin('users', 'push_subscriptions.user_id', '=', 'users.id')
            ->select('push_subscriptions.user_id', 'users.notification_preferences')
            ->get()
            ->filter(function ($row) use ($prefKey) {
                if (empty($row->notification_preferences)) return true;
                $prefs = is_string($row->notification_preferences)
                    ? json_decode($row->notification_preferences, true)
                    : (array) $row->notification_preferences;
                return $prefs[$prefKey] ?? true;
            })
            ->pluck('user_id')
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Envoie une notification uniquement aux abonnés qui ont activé la préférence $prefKey.
     */
    public function sendWithPreference(string $prefKey, array $payload): void
    {
        $userIds = $this->getUserIdsWithPreference($prefKey);
        if (!empty($userIds)) {
            $this->sendToUsers($userIds, $payload);
        }
    }

    // ── Payload helpers ─────────────────────────────────────────

    /**
     * Construit le payload JSON d'une notification.
     *
     * @param array{
     *   title: string,
     *   body: string,
     *   url?: string,
     *   icon?: string,
     *   tag?: string,
     *   actions?: array
     * } $data
     */
    public static function payload(
        string $title,
        string $body,
        string $url    = '/',
        string $icon   = '/images/icons/africode-192.png',
        string $tag    = 'africode',
        array  $actions = []
    ): array {
        return compact('title', 'body', 'url', 'icon', 'tag', 'actions');
    }

    // ── Envoi interne ────────────────────────────────────────────

    private function dispatch(\Illuminate\Support\Collection $subscriptions, array $payload): void
    {
        if ($subscriptions->isEmpty()) {
            return;
        }

        $json = json_encode($payload);

        foreach ($subscriptions as $sub) {
            $this->webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'keys'     => [
                        'p256dh' => $sub->public_key,
                        'auth'   => $sub->auth_token,
                    ],
                ]),
                $json
            );
        }

        // Envoie tout et nettoie les abonnements expirés
        foreach ($this->webPush->flush() as $report) {
            if ($report->isSubscriptionExpired()) {
                DB::table('push_subscriptions')
                    ->where('endpoint', $report->getRequest()->getUri()->__toString())
                    ->delete();

                Log::info('Push: abonnement expiré supprimé', [
                    'endpoint' => substr($report->getRequest()->getUri()->__toString(), 0, 60),
                ]);
            }
        }
    }
}

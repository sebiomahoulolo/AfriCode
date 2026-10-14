<?php

namespace App\Console\Commands;

use App\Jobs\SendPushNotificationJob;
use App\Services\PushNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendInactivityPushReminder extends Command
{
    protected $signature   = 'push:inactivity-reminder {days=7 : Jours d\'inactivité avant rappel}';
    protected $description = 'Envoie une notification push aux apprenants inactifs depuis N jours';

    public function handle(): int
    {
        $days = (int) $this->argument('days');

        // Apprenants inscrits, non terminés, sans leçon complétée depuis $days jours + abonnement push actif
        $inactiveUserIds = DB::table('push_subscriptions')
            ->join('enrollments', 'push_subscriptions.user_id', '=', 'enrollments.user_id')
            ->whereNotIn('push_subscriptions.user_id', function ($query) use ($days) {
                $query->select('user_id')
                    ->from('lesson_completions')
                    ->where('completed_at', '>=', now()->subDays($days));
            })
            ->whereNull('enrollments.completed_at')
            ->where('enrollments.progress_percentage', '<', 100)
            ->whereNotNull('enrollments.enrolled_at')
            ->where('enrollments.enrolled_at', '<', now()->subDays(1))
            ->pluck('push_subscriptions.user_id')
            ->unique()
            ->values()
            ->toArray();

        if (empty($inactiveUserIds)) {
            $this->info("Aucun apprenant inactif depuis {$days} jours.");
            return self::SUCCESS;
        }

        // Filtre : uniquement ceux qui ont activé "push_reminders" (défaut = true)
        $filteredIds = DB::table('users')
            ->whereIn('id', $inactiveUserIds)
            ->get(['id', 'notification_preferences'])
            ->filter(function ($user) {
                if (empty($user->notification_preferences)) return true;
                $prefs = is_string($user->notification_preferences)
                    ? json_decode($user->notification_preferences, true)
                    : (array) $user->notification_preferences;
                return $prefs['push_reminders'] ?? true;
            })
            ->pluck('id')
            ->values()
            ->toArray();

        if (empty($filteredIds)) {
            $this->info("Aucun destinataire après filtrage des préférences.");
            return self::SUCCESS;
        }

        $payload = PushNotificationService::payload(
            title: 'Continuez votre apprentissage !',
            body:  "Vous n'avez pas suivi de cours depuis {$days} jours. Reprenez dès maintenant !",
            url:   '/dashboard',
            tag:   'inactivite-rappel',
        );

        SendPushNotificationJob::dispatch($payload, $filteredIds)->onQueue('notifications');

        $this->info("Rappel d'inactivité envoyé à " . count($filteredIds) . " apprenants (filtrés depuis " . count($inactiveUserIds) . " inactifs).");

        return self::SUCCESS;
    }
}

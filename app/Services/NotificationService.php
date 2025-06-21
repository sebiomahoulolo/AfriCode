<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a new notification for a user
     *
     * @param User|int $user User or user_id
     * @param string $type Notification type (e.g., admin.user.created)
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $options Additional options (data, icon, color, action_url, action_text)
     * @return Notification
     */
    public static function create($user, string $type, string $title, string $message, array $options = [])
    {
        $userId = $user instanceof User ? $user->id : $user;
        
        $notification = new Notification([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $options['data'] ?? null,
            'icon' => $options['icon'] ?? 'bell',
            'color' => $options['color'] ?? 'primary',
            'action_url' => $options['action_url'] ?? null,
            'action_text' => $options['action_text'] ?? null,
        ]);
        
        $notification->save();
        
        return $notification;
    }
    
    /**
     * Create admin notification for user actions
     *
     * @param string $action Action performed (created, updated, deleted)
     * @param string $modelType Type of model affected (user, course, etc.)
     * @param string $modelName Name or identifier of the model
     * @param array $options Additional options
     * @return Notification|null
     */
    public static function userAction(string $action, string $modelType, string $modelName, array $options = [])
    {
        $user = Auth::user();
        if (!$user) return null;
        
        $actionVerb = match($action) {
            'created' => 'créé',
            'updated' => 'modifié',
            'deleted' => 'supprimé',
            default => $action
        };
        
        $modelLabel = match($modelType) {
            'user' => 'utilisateur',
            'course' => 'cours',
            'module' => 'module',
            'lesson' => 'leçon',
            'quiz' => 'quiz',
            'setting' => 'paramètre',
            'event' => 'événement',
            'announcement' => 'annonce',
            default => $modelType
        };
        
        // Create notification for all admin users
        $adminUsers = User::where('role', 'administrateur')->where('id', '!=', $user->id)->get();
        
        $icon = match($modelType) {
            'user' => 'user',
            'course' => 'graduation-cap',
            'module' => 'book',
            'lesson' => 'file-alt',
            'quiz' => 'question-circle',
            'setting' => 'cog',
            'event' => 'calendar',
            'announcement' => 'bullhorn',
            default => 'bell'
        };
        
        $color = match($action) {
            'created' => 'success',
            'updated' => 'info',
            'deleted' => 'danger',
            default => 'primary'
        };
        
        $createdNotifications = [];
        
        foreach ($adminUsers as $admin) {
            // Create notification
            $notification = self::create(
                $admin,
                "admin.$modelType.$action",
                "Un $modelLabel a été $actionVerb",
                "{$user->name} a $actionVerb le $modelLabel \"$modelName\"",
                array_merge([
                    'icon' => $icon, 
                    'color' => $color,
                    'data' => [
                        'model_id' => $options['model_id'] ?? null,
                        'user_id' => $user->id,
                        'user_name' => $user->name
                    ]
                ], $options)
            );
            
            $createdNotifications[] = $notification;
        }
        
        return $createdNotifications;
    }
    
    /**
     * Create system notification for all admins
     *
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array $options Additional options
     * @return array Created notifications
     */
    public static function systemNotification(string $title, string $message, array $options = [])
    {
        $adminUsers = User::where('role', 'administrateur')->get();
        $createdNotifications = [];
        
        foreach ($adminUsers as $admin) {
            $notification = self::create(
                $admin,
                'admin.system',
                $title,
                $message,
                array_merge(['icon' => 'server', 'color' => 'dark'], $options)
            );
            
            $createdNotifications[] = $notification;
        }
        
        return $createdNotifications;
    }
    
    /**
     * Create error notification for admin users
     *
     * @param string $title Error title
     * @param string $message Error message
     * @param array $options Additional options
     * @return array Created notifications
     */
    public static function errorNotification(string $title, string $message, array $options = [])
    {
        $adminUsers = User::where('role', 'administrateur')->get();
        $createdNotifications = [];
        
        foreach ($adminUsers as $admin) {
            $notification = self::create(
                $admin,
                'admin.error',
                $title,
                $message,
                array_merge([
                    'icon' => 'exclamation-triangle', 
                    'color' => 'danger',
                    'data' => [
                        'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3),
                        'timestamp' => now()->toDateTimeString()
                    ]
                ], $options)
            );
            
            $createdNotifications[] = $notification;
        }
        
        return $createdNotifications;
    }
    
    /**
     * Mark a notification as read
     *
     * @param int $id Notification ID
     * @return Notification|null
     */
    public static function markAsRead(int $id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return $notification;
    }
    
    /**
     * Mark all notifications as read for a user
     *
     * @param int|null $userId User ID (defaults to authenticated user)
     * @return int Number of notifications marked as read
     */
    public static function markAllAsRead(?int $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        if (!$userId) return 0;
        
        return Notification::where('user_id', $userId)
            ->where('read', false)
            ->update([
                'read' => true,
                'read_at' => now()
            ]);
    }

    public function send($user, $type, $data, $channels = ['database', 'email'])
    {
        try {
            foreach ($channels as $channel) {
                switch ($channel) {
                    case 'database':
                        $this->sendDatabaseNotification($user, $type, $data);
                        break;
                    case 'email':
                        $this->sendEmailNotification($user, $type, $data);
                        break;
                    case 'push':
                        $this->sendPushNotification($user, $type, $data);
                        break;
                    case 'sms':
                        $this->sendSmsNotification($user, $type, $data);
                        break;
                }
            }
        } catch (\Exception $e) {
            Log::error('Notification failed: ' . $e->getMessage());
        }
    }

    protected function sendDatabaseNotification($user, $type, $data)
    {
        $notification = new Notification([
            'type' => $type,
            'data' => $data,
            'read_at' => null
        ]);

        $user->notifications()->save($notification);
    }

    protected function sendEmailNotification($user, $type, $data)
    {
        $template = $this->getEmailTemplate($type);
        
        Mail::send($template, [
            'user' => $user,
            'data' => $data
        ], function ($message) use ($user, $type) {
            $message->to($user->email)
                   ->subject($this->getEmailSubject($type));
        });
    }

    protected function sendPushNotification($user, $type, $data)
    {
        if ($user->push_token) {
            // Intégration avec Firebase Cloud Messaging ou autre service de push
            // Exemple avec Firebase:
            /*
            $fcm = new Firebase\CloudMessaging\CloudMessaging();
            $fcm->send([
                'token' => $user->push_token,
                'notification' => [
                    'title' => $this->getNotificationTitle($type),
                    'body' => $this->getNotificationBody($type, $data)
                ],
                'data' => $data
            ]);
            */
        }
    }

    protected function sendSmsNotification($user, $type, $data)
    {
        if ($user->phone) {
            // Intégration avec un service SMS
            // Exemple avec Twilio:
            /*
            $twilio = new Twilio\Rest\Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );
            
            $twilio->messages->create(
                $user->phone,
                [
                    'from' => config('services.twilio.from'),
                    'body' => $this->getSmsBody($type, $data)
                ]
            );
            */
        }
    }

    protected function getEmailTemplate($type)
    {
        $templates = [
            'course_enrolled' => 'emails.course.enrolled',
            'course_completed' => 'emails.course.completed',
            'payment_received' => 'emails.payment.received',
            'support_ticket' => 'emails.support.ticket',
            'achievement_earned' => 'emails.achievement.earned'
        ];

        return $templates[$type] ?? 'emails.default';
    }

    protected function getEmailSubject($type)
    {
        $subjects = [
            'course_enrolled' => 'Bienvenue dans votre nouveau cours !',
            'course_completed' => 'Félicitations ! Vous avez terminé le cours',
            'payment_received' => 'Confirmation de paiement',
            'support_ticket' => 'Mise à jour de votre ticket de support',
            'achievement_earned' => 'Nouveau badge débloqué !'
        ];

        return $subjects[$type] ?? 'Notification';
    }

    protected function getNotificationTitle($type)
    {
        $titles = [
            'course_enrolled' => 'Nouveau cours',
            'course_completed' => 'Cours terminé',
            'payment_received' => 'Paiement reçu',
            'support_ticket' => 'Mise à jour du support',
            'achievement_earned' => 'Nouveau badge'
        ];

        return $titles[$type] ?? 'Notification';
    }

    protected function getNotificationBody($type, $data)
    {
        $bodies = [
            'course_enrolled' => "Vous êtes maintenant inscrit au cours : {$data['course_name']}",
            'course_completed' => "Félicitations ! Vous avez terminé le cours : {$data['course_name']}",
            'payment_received' => "Paiement de {$data['amount']}€ reçu pour {$data['course_name']}",
            'support_ticket' => "Mise à jour de votre ticket : {$data['ticket_subject']}",
            'achievement_earned' => "Vous avez débloqué le badge : {$data['achievement_name']}"
        ];

        return $bodies[$type] ?? 'Nouvelle notification';
    }

    protected function getSmsBody($type, $data)
    {
        $bodies = [
            'course_enrolled' => "Inscription confirmée au cours {$data['course_name']}",
            'course_completed' => "Félicitations ! Cours {$data['course_name']} terminé",
            'payment_received' => "Paiement de {$data['amount']}€ reçu",
            'support_ticket' => "Ticket {$data['ticket_id']} mis à jour",
            'achievement_earned' => "Nouveau badge : {$data['achievement_name']}"
        ];

        return $bodies[$type] ?? 'Nouvelle notification';
    }
}

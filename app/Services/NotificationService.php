<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
}

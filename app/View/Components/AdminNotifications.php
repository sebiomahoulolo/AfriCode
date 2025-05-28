<?php

namespace App\View\Components;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

class AdminNotifications extends Component
{
    /**
     * Notifications for the current user
     */
    public $notifications;
    
    /**
     * Count of unread notifications
     */
    public $unreadCount;
    
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $user = Auth::user();
        
        if ($user) {
            $this->notifications = $user->notifications()
                ->latest()
                ->limit(10)
                ->get();
            
            $this->unreadCount = $user->notifications()
                ->where('read', false)
                ->count();
        } else {
            $this->notifications = collect();
            $this->unreadCount = 0;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.admin-notifications');
    }
}

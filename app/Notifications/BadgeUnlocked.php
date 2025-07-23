<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BadgeUnlocked extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Nouveau badge débloqué !')
            ->greeting('Félicitations ' . $notifiable->first_name . ' !')
            ->line('Vous venez de débloquer un nouveau badge sur AfriCode.')
            ->action('Voir mes badges', url('/badges'))
            ->line('Continuez comme ça !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Nouveau badge débloqué !',
            'body' => 'Vous venez de débloquer un badge. Bravo !',
            'url' => url('/badges'),
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProgressionMilestone extends Notification
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
            ->subject('Bravo pour votre progression !')
            ->greeting('Félicitations ' . $notifiable->first_name . ' !')
            ->line('Vous avez atteint un nouveau palier de progression sur AfriCode.')
            ->action('Voir ma progression', url('/dashboard'))
            ->line('Continuez sur cette lancée !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Nouveau palier atteint !',
            'body' => 'Vous avez atteint un nouveau palier de progression. Continuez ainsi !',
            'url' => url('/dashboard'),
        ];
    }
}

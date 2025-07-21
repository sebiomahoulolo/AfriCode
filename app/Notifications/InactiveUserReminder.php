<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InactiveUserReminder extends Notification
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
            ->subject('On ne vous voit plus sur AfriCode !')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Nous avons remarqué que vous n’avez pas progressé depuis quelques jours.')
            ->action('Reprendre ma formation', url('/dashboard'))
            ->line('Votre réussite nous tient à cœur !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'On ne vous voit plus !',
            'body' => 'Vous n’avez pas progressé depuis quelques jours. Reprenez votre parcours !',
            'url' => url('/dashboard'),
        ];
    }
}

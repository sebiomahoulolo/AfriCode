<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LearningHelpNotification extends Notification
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
            ->subject('Besoin d’aide dans votre apprentissage ?')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Nous avons détecté que vous rencontrez des difficultés dans votre apprentissage sur AfriCode.')
            ->action('Voir les ressources d’aide', url('/faq'))
            ->line('N’hésitez pas à contacter un mentor ou le support si besoin.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Besoin d’aide ?',
            'body' => 'Nous avons détecté que vous rencontrez des difficultés. Consultez la FAQ ou contactez un mentor.',
            'url' => url('/faq'),
        ];
    }
}

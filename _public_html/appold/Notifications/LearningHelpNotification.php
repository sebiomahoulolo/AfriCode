<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LearningHelpNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Besoin d'aide dans votre apprentissage ?")
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line("Nous avons détecté que vous rencontrez des difficultés dans votre apprentissage sur AfriCode.")
            ->action("Voir les ressources d'aide", route('support.faq.index'))
            ->line("N'hésitez pas à contacter un mentor ou le support si besoin.");
    }

    public function toArray($notifiable)
    {
        return [
            'title' => "Besoin d'aide ?",
            'body'  => "Nous avons détecté que vous rencontrez des difficultés. Consultez la FAQ ou contactez un mentor.",
            'url'   => route('support.faq.index'),
        ];
    }
}

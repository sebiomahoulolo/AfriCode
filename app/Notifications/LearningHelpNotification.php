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
            ->view('emails.notifications.learning-help', [
                'firstName' => $notifiable->first_name,
                'helpUrl'   => route('support.faq.index'),
            ]);
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

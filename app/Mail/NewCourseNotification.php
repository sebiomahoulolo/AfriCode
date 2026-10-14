<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class NewCourseNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $course;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Course $course, User $user)
    {
        $this->course = $course;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'noreply@africode.com'),
                config('mail.from.name', 'AfriCode')
            ),
            subject: 'Nouveau cours disponible : ' . $this->course->title,
            tags: ['new-course', 'course-' . $this->course->id, 'notification'],
            metadata: [
                'course_id' => (string) $this->course->id,
                'user_id' => (string) $this->user->id,
                'campaign_type' => 'new_course_notification',
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Générer l'URL de désabonnement
        // Si l'utilisateur est abonné à la newsletter, utiliser le token
        // Sinon, créer un lien vers la page de préférences
        $unsubscribeUrl = route('courses.index');
        
        // Vérifier si l'utilisateur est abonné à la newsletter
        $subscriber = \App\Models\NewsletterSubscriber::where('email', $this->user->email)->first();
        if ($subscriber && $subscriber->unsubscribe_token) {
            $unsubscribeUrl = route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]);
        }

        return new Content(
            view: 'emails.courses.new-course',
            text: 'emails.courses.new-course-text',
            with: [
                'course' => $this->course,
                'user' => $this->user,
                'courseUrl' => route('courses.show', $this->course->slug),
                'unsubscribeUrl' => $unsubscribeUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}


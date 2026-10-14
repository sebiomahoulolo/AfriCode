<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class CandidatureAccuseReception extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public JobApplication $application
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'noreply@africode.tech'),
                config('mail.from.name', 'AfriCode Recrutement')
            ),
            subject: 'AfriCode – Accusé de réception de votre candidature',
            replyTo: [new Address('contact@africode.tech', 'AfriCode')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recruitment.accuse-reception',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

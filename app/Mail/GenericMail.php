<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 60;

    public function __construct(
        private string  $mailHtmlView,
        private ?string $mailTextView,
        private array   $mailViewData,
        private string  $subjectLine,
        private ?string $replyToEmail      = null,
        private ?string $replyToName       = null,
        private ?string $attachmentData    = null,
        private ?string $attachmentName    = null,
        private ?string $attachmentMime    = null,
    ) {}

    public function build(): self
    {
        $mail = $this
            ->subject($this->subjectLine)
            ->view($this->mailHtmlView, $this->mailViewData);

        if ($this->mailTextView) {
            $mail->text($this->mailTextView, $this->mailViewData);
        }

        if ($this->replyToEmail) {
            $mail->replyTo($this->replyToEmail, $this->replyToName ?? '');
        }

        if ($this->attachmentData && $this->attachmentName) {
            $mail->attachData(
                base64_decode($this->attachmentData),
                $this->attachmentName,
                ['mime' => $this->attachmentMime ?? 'application/octet-stream']
            );
        }

        return $mail;
    }
}

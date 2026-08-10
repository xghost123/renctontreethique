<?php

namespace App\Mail;

use App\Models\Biodata;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BioDataRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Biodata $biodata,
        public ?string $feedback = null
    ) {
        $this->onQueue('notifications');
        $this->delay(now()->addSeconds(5));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            subject: 'Votre profil Rencontre Éthique nécessite des révisions - Your Biodata Revision Required',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.biodata-rejected',
            with: [
                'biodata' => $this->biodata,
                'user' => $this->biodata->user,
                'feedback' => $this->feedback,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

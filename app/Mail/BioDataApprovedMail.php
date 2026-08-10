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

class BioDataApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Biodata $biodata)
    {
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
            subject: 'Votre profil Rencontre Éthique est approuvé - Your Biodata Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.biodata-approved',
            with: [
                'biodata' => $this->biodata,
                'user' => $this->biodata->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

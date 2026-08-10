<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegistrationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
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
            subject: 'Bienvenue sur Rencontre Éthique - Welcome to Islamic Matrimony',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user-registration',
            with: [
                'user' => $this->user,
                'verificationUrl' => route('verification.verify', [
                    'id' => $this->user->id,
                    'hash' => sha1($this->user->email),
                ]),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

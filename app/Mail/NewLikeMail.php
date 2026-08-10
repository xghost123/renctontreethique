<?php

namespace App\Mail;

use App\Models\Like;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLikeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Like $like,
        public User $recipient
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
            subject: 'Quelqu\'un a aimé votre profil - New Like Received',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-like',
            with: [
                'like' => $this->like,
                'liker' => $this->like->liker,
                'recipient' => $this->recipient,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

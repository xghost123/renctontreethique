<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Message $message,
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
            subject: 'Vous avez reçu un nouveau message - New Message Received',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-message',
            with: [
                'message' => $this->message,
                'sender' => $this->message->sender,
                'recipient' => $this->recipient,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

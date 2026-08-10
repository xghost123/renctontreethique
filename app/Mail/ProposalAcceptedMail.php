<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProposalAcceptedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Proposal $proposal)
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
            subject: 'Votre proposition de mariage a été acceptée - Proposal Accepted',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.proposal-accepted',
            with: [
                'proposal' => $this->proposal,
                'proposedUser' => $this->proposal->proposedUser,
                'proposer' => $this->proposal->proposer,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $type;
    protected $data;

    public function __construct($user, $type, $data = [])
    {
        $this->user = $user;
        $this->type = $type;
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'proposal_created' => 'New Marriage Proposal Received',
            'proposal_response' => 'Your Proposal has been ' . ucfirst($this->data['status'] ?? 'Responded'),
            'message_received' => 'New Message from ' . ($this->data['sender_name'] ?? 'a Member'),
            'profile_approved' => 'Your Profile has been Approved!',
            'profile_viewed' => 'Your Profile was Viewed',
        ];

        return new Envelope(
            subject: $subjects[$this->type] ?? 'Rencontre Éthique Notification'
        );
    }

    public function content(): Content
    {
        $view = match($this->type) {
            'proposal_created' => 'mail.notifications.proposal-created',
            'proposal_response' => 'mail.notifications.proposal-response',
            'message_received' => 'mail.notifications.message-received',
            'profile_approved' => 'mail.notifications.profile-approved',
            'profile_viewed' => 'mail.notifications.profile-viewed',
            default => 'mail.notifications.generic',
        };

        return new Content(
            markdown: $view,
            with: [
                'user' => $this->user,
                'data' => $this->data,
                'type' => $this->type,
            ]
        );
    }
}

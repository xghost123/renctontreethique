<?php

namespace App\Listeners;

use App\Events\ProposalResponse;
use App\Models\Notification;
use App\Jobs\SendNotificationEmail;

class CreateProposalResponseNotification
{
    public function handle(ProposalResponse $event): void
    {
        if (!$event->senderUser) {
            return;
        }

        $preferences = $event->senderUser->getOrCreateNotificationPreferences();

        $statusText = $event->status === 'accepted' ? 'accepted' : 'declined';
        $title = 'Proposal ' . ucfirst($statusText);
        $message = $event->responderUser ? 
            "{$event->responderUser->name} has {$statusText} your proposal" : 
            "Your proposal has been {$statusText}";
        $color = $event->status === 'accepted' ? 'success' : 'warning';

        // Create in-app notification if enabled
        if ($preferences->inapp_proposal_response) {
            Notification::create([
                'user_id' => $event->senderUser->id,
                'type' => 'proposal_response',
                'title' => $title,
                'message' => $message,
                'icon' => $event->status === 'accepted' ? 'check' : 'x',
                'color' => $color,
                'data' => [
                    'proposal_id' => $event->proposal->id,
                    'status' => $event->status,
                    'responder_user_id' => $event->proposal->receiver_user_id,
                ],
            ]);
        }

        // Send email if enabled
        if ($preferences->email_proposal_response) {
            SendNotificationEmail::dispatch(
                $event->senderUser,
                'proposal_response',
                [
                    'responder_name' => $event->responderUser?->name ?? 'A member',
                    'status' => $event->status,
                    'proposal_id' => $event->proposal->id,
                ]
            );
        }
    }
}

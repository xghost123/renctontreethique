<?php

namespace App\Listeners;

use App\Events\ProposalCreated;
use App\Models\Notification;
use App\Jobs\SendNotificationEmail;

class CreateProposalNotification
{
    public function handle(ProposalCreated $event): void
    {
        if (!$event->receiverUser) {
            return;
        }

        $preferences = $event->receiverUser->getOrCreateNotificationPreferences();

        // Create in-app notification if enabled
        if ($preferences->inapp_proposal_created) {
            Notification::create([
                'user_id' => $event->receiverUser->id,
                'type' => 'proposal_created',
                'title' => 'New Proposal Received',
                'message' => $event->senderUser ? 
                    "You received a new proposal from {$event->senderUser->name}" : 
                    'You received a new proposal',
                'icon' => 'heart',
                'color' => 'success',
                'data' => [
                    'proposal_id' => $event->proposal->id,
                    'sender_user_id' => $event->proposal->sender_user_id,
                ],
            ]);
        }

        // Send email if enabled
        if ($preferences->email_proposal_created) {
            SendNotificationEmail::dispatch(
                $event->receiverUser,
                'proposal_created',
                [
                    'sender_name' => $event->senderUser?->name ?? 'A member',
                    'proposal_id' => $event->proposal->id,
                ]
            );
        }
    }
}

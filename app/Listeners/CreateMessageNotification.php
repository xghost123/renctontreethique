<?php

namespace App\Listeners;

use App\Events\MessageReceived;
use App\Models\Notification;
use App\Jobs\SendNotificationEmail;

class CreateMessageNotification
{
    public function handle(MessageReceived $event): void
    {
        if (!$event->receiverUser) {
            return;
        }

        $preferences = $event->receiverUser->getOrCreateNotificationPreferences();

        // Create in-app notification if enabled
        if ($preferences->inapp_message_received) {
            Notification::create([
                'user_id' => $event->receiverUser->id,
                'type' => 'message_received',
                'title' => 'New Message',
                'message' => $event->senderUser ? 
                    "New message from {$event->senderUser->name}" : 
                    'You have a new message',
                'icon' => 'envelope',
                'color' => 'info',
                'data' => [
                    'message_id' => $event->message->id,
                    'sender_user_id' => $event->message->sender_id ?? $event->senderUser?->id,
                    'conversation_id' => $event->message->conversation_id,
                ],
            ]);
        }

        // Send email if enabled
        if ($preferences->email_message_received) {
            SendNotificationEmail::dispatch(
                $event->receiverUser,
                'message_received',
                [
                    'sender_name' => $event->senderUser?->name ?? 'A member',
                    'message_id' => $event->message->id,
                ]
            );
        }
    }
}

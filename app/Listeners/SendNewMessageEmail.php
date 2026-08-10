<?php

namespace App\Listeners;

use App\Events\MessageReceived;
use App\Mail\NewMessageMail;
use App\Models\NotificationPreference;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewMessageEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(MessageReceived $event): void
    {
        $recipientId = $event->message->recipient_id;

        $preference = NotificationPreference::firstOrCreate(
            ['user_id' => $recipientId],
            ['email_on_message' => true]
        );

        if ($preference->email_on_message) {
            Mail::send(new NewMessageMail($event->message, $event->message->recipient));
        }
    }
}

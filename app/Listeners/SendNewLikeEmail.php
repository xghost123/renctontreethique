<?php

namespace App\Listeners;

use App\Events\UserLiked;
use App\Mail\NewLikeMail;
use App\Models\NotificationPreference;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewLikeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserLiked $event): void
    {
        $recipientId = $event->like->liked_user_id;

        $preference = NotificationPreference::firstOrCreate(
            ['user_id' => $recipientId],
            ['email_on_like' => true]
        );

        if ($preference->email_on_like) {
            Mail::send(new NewLikeMail($event->like, $event->like->likedUser));
        }
    }
}

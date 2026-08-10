<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\UserRegistrationMail;
use App\Models\NotificationPreference;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendUserRegistrationEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserRegistered $event): void
    {
        // Check if user has enabled registration emails
        $preference = NotificationPreference::firstOrCreate(
            ['user_id' => $event->user->id],
            ['email_on_registration' => true]
        );

        if ($preference->email_on_registration) {
            Mail::send(new UserRegistrationMail($event->user));
        }
    }
}

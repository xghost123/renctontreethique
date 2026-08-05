<?php

namespace App\Listeners;

use App\Events\ProfileApproved;
use App\Models\Notification;
use App\Jobs\SendNotificationEmail;

class CreateProfileApprovedNotification
{
    public function handle(ProfileApproved $event): void
    {
        if (!$event->user) {
            return;
        }

        $preferences = $event->user->getOrCreateNotificationPreferences();

        // Create in-app notification if enabled
        if ($preferences->inapp_profile_approved) {
            Notification::create([
                'user_id' => $event->user->id,
                'type' => 'profile_approved',
                'title' => 'Profile Approved',
                'message' => 'Congratulations! Your profile has been approved by admin',
                'icon' => 'check',
                'color' => 'success',
                'data' => [
                    'user_id' => $event->user->id,
                ],
            ]);
        }

        // Send email if enabled
        if ($preferences->email_profile_approved) {
            SendNotificationEmail::dispatch(
                $event->user,
                'profile_approved',
                [
                    'user_name' => $event->user->name,
                ]
            );
        }
    }
}

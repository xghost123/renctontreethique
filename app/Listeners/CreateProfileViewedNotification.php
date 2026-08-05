<?php

namespace App\Listeners;

use App\Events\ProfileViewed;
use App\Models\Notification;
use App\Jobs\SendNotificationEmail;

class CreateProfileViewedNotification
{
    public function handle(ProfileViewed $event): void
    {
        if (!$event->viewedUser) {
            return;
        }

        $preferences = $event->viewedUser->getOrCreateNotificationPreferences();

        // Create in-app notification if enabled
        if ($preferences->inapp_profile_viewed) {
            Notification::create([
                'user_id' => $event->viewedUser->id,
                'type' => 'profile_viewed',
                'title' => 'Profile Viewed',
                'message' => $event->viewerUser ? 
                    "{$event->viewerUser->name} viewed your profile" : 
                    'Someone viewed your profile',
                'icon' => 'eye',
                'color' => 'info',
                'data' => [
                    'viewer_user_id' => $event->viewerUser?->id,
                ],
            ]);
        }

        // Send email only if explicitly enabled (defaults to false)
        if ($preferences->email_profile_viewed) {
            SendNotificationEmail::dispatch(
                $event->viewedUser,
                'profile_viewed',
                [
                    'viewer_name' => $event->viewerUser?->name ?? 'Someone',
                ]
            );
        }
    }
}

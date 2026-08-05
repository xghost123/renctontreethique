<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $notifications = $user->notifications()
            ->paginate(15);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function getUnread(Request $request)
    {
        $user = $request->user();
        
        $notifications = $user->unreadNotifications()
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count(),
        ]);
    }

    public function show(Request $request, Notification $notification)
    {
        $this->authorize('view', $notification);
        
        if (!$notification->read) {
            $notification->markAsRead();
        }

        return response()->json($notification);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        $this->authorize('update', $notification);
        
        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
            'notification' => $notification,
        ]);
    }

    public function markAsUnread(Request $request, Notification $notification)
    {
        $this->authorize('update', $notification);
        
        $notification->markAsUnread();

        return response()->json([
            'message' => 'Notification marked as unread',
            'notification' => $notification,
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        
        $user->unreadNotifications()
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'message' => 'All notifications marked as read',
            'unread_count' => 0,
        ]);
    }

    public function delete(Request $request, Notification $notification)
    {
        $this->authorize('delete', $notification);
        
        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted',
        ]);
    }

    public function deleteAll(Request $request)
    {
        $user = $request->user();
        
        $user->notifications()->delete();

        return response()->json([
            'message' => 'All notifications deleted',
        ]);
    }

    public function preferences(Request $request)
    {
        $user = $request->user();
        $preferences = $user->getOrCreateNotificationPreferences();

        return response()->json($preferences);
    }

    public function updatePreferences(Request $request)
    {
        $user = $request->user();
        $preferences = $user->getOrCreateNotificationPreferences();

        $validated = $request->validate([
            'email_proposal_created' => 'boolean',
            'email_message_received' => 'boolean',
            'email_profile_approved' => 'boolean',
            'email_proposal_response' => 'boolean',
            'email_profile_viewed' => 'boolean',
            'inapp_proposal_created' => 'boolean',
            'inapp_message_received' => 'boolean',
            'inapp_profile_approved' => 'boolean',
            'inapp_proposal_response' => 'boolean',
            'inapp_profile_viewed' => 'boolean',
            'email_frequency' => 'in:immediate,daily,weekly',
        ]);

        $preferences->update($validated);

        return response()->json([
            'message' => 'Preferences updated successfully',
            'preferences' => $preferences,
        ]);
    }

    public function settingsPage(Request $request)
    {
        $user = $request->user();
        $preferences = $user->getOrCreateNotificationPreferences();

        return Inertia::render('NotificationSettings', [
            'preferences' => $preferences,
        ]);
    }
}

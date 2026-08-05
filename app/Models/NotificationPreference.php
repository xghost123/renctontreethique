<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_proposal_created',
        'email_message_received',
        'email_profile_approved',
        'email_proposal_response',
        'email_profile_viewed',
        'inapp_proposal_created',
        'inapp_message_received',
        'inapp_profile_approved',
        'inapp_proposal_response',
        'inapp_profile_viewed',
        'email_frequency',
    ];

    protected $casts = [
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getOrCreateForUser($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'user_id' => $userId,
                'email_proposal_created' => true,
                'email_message_received' => true,
                'email_profile_approved' => true,
                'email_proposal_response' => true,
                'email_profile_viewed' => false,
                'inapp_proposal_created' => true,
                'inapp_message_received' => true,
                'inapp_profile_approved' => true,
                'inapp_proposal_response' => true,
                'inapp_profile_viewed' => true,
                'email_frequency' => 'immediate',
            ]
        );
    }

    public function isEmailEnabledFor($type)
    {
        $key = 'email_' . $type;
        return $this->$key ?? false;
    }

    public function isInAppEnabledFor($type)
    {
        $key = 'inapp_' . $type;
        return $this->$key ?? true;
    }
}

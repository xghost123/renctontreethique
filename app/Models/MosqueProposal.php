<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MosqueProposal extends Model
{
    protected $fillable = [
        'mosque_id', 'sender_id', 'receiver_id', 'status',
        'message', 'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // A brother can only have ONE active proposal to a sister
    public static function hasActiveProposal(int $senderId, int $receiverId): bool
    {
        return static::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->whereIn('status', ['pending'])
            ->exists();
    }
}

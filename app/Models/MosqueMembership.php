<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MosqueMembership extends Model
{
    protected $fillable = [
        'mosque_id', 'user_id', 'role', 'status',
        'approved_by', 'approved_at', 'rejection_reason',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Is this user an approved member of a mosque?
    public static function isApprovedMember(int $userId, int $mosqueId): bool
    {
        return static::where('user_id', $userId)
            ->where('mosque_id', $mosqueId)
            ->where('status', 'approved')
            ->exists();
    }

    // The user's approved mosque (strict isolation = at most one visible)
    public static function approvedMosqueId(int $userId): ?int
    {
        return static::where('user_id', $userId)
            ->where('status', 'approved')
            ->value('mosque_id');
    }
}

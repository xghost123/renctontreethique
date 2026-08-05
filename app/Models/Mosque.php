<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mosque extends Model
{
    protected $fillable = [
        'name', 'slug', 'city', 'country', 'latitude', 'longitude', 'address',
        'imam_name', 'imam_phone', 'imam_email', 'status', 'created_by',
    ];

    // Approved members of this mosque
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'mosque_memberships')
            ->wherePivot('status', 'approved')
            ->withPivot('role');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(MosqueMembership::class);
    }

    public function moderators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'mosque_memberships')
            ->wherePivotIn('role', ['moderator', 'imam'])
            ->wherePivot('status', 'approved');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(MosqueProposal::class);
    }

    public function joinRequests(): HasMany
    {
        return $this->hasMany(MosqueJoinRequest::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

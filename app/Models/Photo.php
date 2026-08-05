<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'biodata_id',
        'path',
        'original_filename',
        'size',
        'mime_type',
        'approved',
        'approved_by',
        'approved_at',
        'display_order',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'datetime',
        'size' => 'integer',
    ];

    /**
     * Get the user that owns this photo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the biodata that owns this photo
     */
    public function biodata(): BelongsTo
    {
        return $this->belongsTo(Biodata::class);
    }

    /**
     * Get the admin who approved this photo
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope: Get approved photos only
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Scope: Get pending photos (not yet approved)
     */
    public function scopePending($query)
    {
        return $query->where('approved', false);
    }

    /**
     * Scope: Get photos for a specific biodata
     */
    public function scopeForBiodata($query, $biodataId)
    {
        return $query->where('biodata_id', $biodataId)->orderBy('display_order')->orderByDesc('created_at');
    }

    /**
     * Get the full URL for this photo
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Check if this photo is approved
     */
    public function isApproved(): bool
    {
        return $this->approved === true;
    }

    /**
     * Check if this photo is pending approval
     */
    public function isPending(): bool
    {
        return $this->approved === false;
    }
}

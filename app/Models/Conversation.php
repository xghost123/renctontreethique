<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'status', 'owner_id', 'dest_id', 'owner_name', 'dest_name',
        'last_message', 'close_reason', 'closed_by', 'closed_at',
        'is_moderation_opened', 'mosque_id',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
        'is_moderation_opened' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function dest()
    {
        return $this->belongsTo(User::class, 'dest_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
}

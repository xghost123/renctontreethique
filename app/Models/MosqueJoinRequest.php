<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MosqueJoinRequest extends Model
{
    protected $fillable = [
        'mosque_id', 'user_id', 'status', 'note', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

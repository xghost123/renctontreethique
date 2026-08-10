<?php

namespace App\Events;

use App\Models\Like;
use Illuminate\Foundation\Events\Dispatchable;

class UserLiked
{
    use Dispatchable;

    public function __construct(public Like $like)
    {
    }
}

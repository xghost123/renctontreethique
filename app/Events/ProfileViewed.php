<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class ProfileViewed
{
    use Dispatchable;

    public function __construct(
        public ?User $viewedUser = null,
        public ?User $viewerUser = null
    ) {}
}

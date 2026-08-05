<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class ProfileApproved
{
    use Dispatchable;

    public function __construct(
        public ?User $user = null
    ) {}
}

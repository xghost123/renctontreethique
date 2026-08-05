<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class MessageReceived
{
    use Dispatchable;

    public function __construct(
        public $message,
        public ?User $senderUser = null,
        public ?User $receiverUser = null
    ) {}
}

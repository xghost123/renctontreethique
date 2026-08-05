<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class ProposalCreated
{
    use Dispatchable;

    public function __construct(
        public $proposal,
        public ?User $senderUser = null,
        public ?User $receiverUser = null
    ) {}
}

<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class ProposalResponse
{
    use Dispatchable;

    public function __construct(
        public $proposal,
        public string $status, // 'accepted' or 'rejected'
        public ?User $responderUser = null,
        public ?User $senderUser = null
    ) {}
}

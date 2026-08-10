<?php

namespace App\Events;

use App\Models\Biodata;
use Illuminate\Foundation\Events\Dispatchable;

class BioDataStatusChanged
{
    use Dispatchable;

    public function __construct(
        public Biodata $biodata,
        public string $status,
        public ?string $feedback = null
    ) {
    }
}

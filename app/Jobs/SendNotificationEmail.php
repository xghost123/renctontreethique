<?php

namespace App\Jobs;

use App\Mail\NotificationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $type;
    protected $data;

    public function __construct($user, $type, $data = [])
    {
        $this->user = $user;
        $this->type = $type;
        $this->data = $data;
    }

    public function handle(): void
    {
        try {
            Mail::send(new NotificationEmail($this->user, $this->type, $this->data));
        } catch (\Exception $e) {
            \Log::error('Failed to send notification email', [
                'user_id' => $this->user->id,
                'type' => $this->type,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

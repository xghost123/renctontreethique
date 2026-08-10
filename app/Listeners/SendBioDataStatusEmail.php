<?php

namespace App\Listeners;

use App\Events\BioDataStatusChanged;
use App\Mail\BioDataApprovedMail;
use App\Mail\BioDataRejectedMail;
use App\Models\NotificationPreference;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBioDataStatusEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BioDataStatusChanged $event): void
    {
        $preference = NotificationPreference::firstOrCreate(
            ['user_id' => $event->biodata->user_id],
            ['email_on_biodata_status' => true]
        );

        if (!$preference->email_on_biodata_status) {
            return;
        }

        if ($event->status === 'approved') {
            Mail::send(new BioDataApprovedMail($event->biodata));
        } elseif ($event->status === 'rejected') {
            Mail::send(new BioDataRejectedMail($event->biodata, $event->feedback));
        }
    }
}

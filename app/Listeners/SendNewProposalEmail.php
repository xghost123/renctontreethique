<?php

namespace App\Listeners;

use App\Events\ProposalCreated;
use App\Mail\NewProposalMail;
use App\Models\NotificationPreference;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewProposalEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ProposalCreated $event): void
    {
        $proposedUserId = $event->proposal->proposed_user_id;

        $preference = NotificationPreference::firstOrCreate(
            ['user_id' => $proposedUserId],
            ['email_on_proposal' => true]
        );

        if ($preference->email_on_proposal) {
            Mail::send(new NewProposalMail($event->proposal));
        }
    }
}

<?php

namespace App\Listeners;

use App\Events\ProposalAccepted;
use App\Mail\ProposalAcceptedMail;
use App\Models\NotificationPreference;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendProposalAcceptedEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ProposalAccepted $event): void
    {
        $proposerId = $event->proposal->proposer_user_id;

        $preference = NotificationPreference::firstOrCreate(
            ['user_id' => $proposerId],
            ['email_on_proposal_response' => true]
        );

        if ($preference->email_on_proposal_response) {
            Mail::send(new ProposalAcceptedMail($event->proposal));
        }
    }
}

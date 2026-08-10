<?php

namespace App\Providers;

use App\Events\BioDataStatusChanged;
use App\Events\MessageReceived;
use App\Events\ProposalAccepted;
use App\Events\ProposalCreated;
use App\Events\UserLiked;
use App\Events\UserRegistered;
use App\Listeners\SendBioDataStatusEmail;
use App\Listeners\SendNewLikeEmail;
use App\Listeners\SendNewMessageEmail;
use App\Listeners\SendNewProposalEmail;
use App\Listeners\SendProposalAcceptedEmail;
use App\Listeners\SendUserRegistrationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserRegistered::class => [
            SendUserRegistrationEmail::class,
        ],
        BioDataStatusChanged::class => [
            SendBioDataStatusEmail::class,
        ],
        ProposalCreated::class => [
            SendNewProposalEmail::class,
        ],
        ProposalAccepted::class => [
            SendProposalAcceptedEmail::class,
        ],
        MessageReceived::class => [
            SendNewMessageEmail::class,
        ],
        UserLiked::class => [
            SendNewLikeEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}

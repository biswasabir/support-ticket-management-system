<?php

namespace App\Providers;

use App\Events\AdminNewReplyCreated;
use App\Events\AdminTicketCreated;
use App\Events\AgentNewReplyCreated;
use App\Events\UserNewReplyCreated;
use App\Events\UserTicketCreated;
use App\Listeners\Admin\SendAdminNewReplyNotification;
use App\Listeners\Admin\SendAdminNewTicketNotification;
use App\Listeners\Agent\SendAgentNewReplyNotification;
use App\Listeners\Agent\SendAgentNewTicketNotification;
use App\Listeners\User\SendUserNewReplyNotification;
use App\Listeners\User\SendUserNewTicketNotification;
use App\Models\Ticket;
use App\Observers\TicketObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserTicketCreated::class => [
            SendAdminNewTicketNotification::class,
            [SendAgentNewTicketNotification::class, 'handleUserTicketCreated'],
        ],
        AdminTicketCreated::class => [
            SendUserNewTicketNotification::class,
            [SendAgentNewTicketNotification::class, 'handleAdminTicketCreated'],
        ],
        UserNewReplyCreated::class => [
            SendAgentNewReplyNotification::class,
            SendAdminNewReplyNotification::class,
        ],
        AgentNewReplyCreated::class => [
            [SendUserNewReplyNotification::class, 'handleAgentNewReplyCreated'],
        ],
        AdminNewReplyCreated::class => [
            [SendUserNewReplyNotification::class, 'handleAdminNewReplyCreated'],
        ],
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Ticket::class => [TicketObserver::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

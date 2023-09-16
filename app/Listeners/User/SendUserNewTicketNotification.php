<?php

namespace App\Listeners\User;

use App\Events\AdminTicketCreated;
use App\Models\User;

class SendUserNewTicketNotification
{
    public function handle(AdminTicketCreated $event)
    {
        $ticket = $event->ticket;
        $user = $ticket->user;
        $user->sendNewTicketNotification($ticket);
        $title = str(lang('New Ticket Created #{number}', 'notifications'))->replace('{number}', $ticket->id);
        $image = asset('images/notifications/ticket.png');
        $link = route('user.tickets.show', $ticket->id);
        $user->pushNotify($title, $image, $link);
    }
}

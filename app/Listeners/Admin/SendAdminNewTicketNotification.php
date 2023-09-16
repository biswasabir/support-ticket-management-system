<?php

namespace App\Listeners\Admin;

use App\Events\UserTicketCreated;
use App\Models\User;

class SendAdminNewTicketNotification
{
    public function handle(UserTicketCreated $event)
    {
        $ticket = $event->ticket;
        $admins = User::admins()->get();
        foreach ($admins as $admin) {
            $admin->sendNewTicketNotification($ticket);
            $title = str(admin_lang('New Ticket Created #{number}'))->replace('{number}', $ticket->id);
            $image = asset('images/notifications/ticket.png');
            $link = route('admin.tickets.show', $ticket->id);
            $admin->pushNotify($title, $image, $link);
        }
    }
}

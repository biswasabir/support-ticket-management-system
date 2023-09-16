<?php

namespace App\Listeners\Admin;

use App\Events\UserNewReplyCreated;
use App\Models\User;

class SendAdminNewReplyNotification
{
    public function handle(UserNewReplyCreated $event)
    {
        $ticketReply = $event->ticketReply;
        $ticket = $ticketReply->ticket;
        $admins = User::admins()->get();
        foreach ($admins as $admin) {
            $admin->sendNewReplyNotification($ticketReply);
            $title = str(lang('New Reply On Ticket #{number}', 'notifications'))->replace('{number}', $ticket->id);
            $image = asset('images/notifications/reply.png');
            $link = route('admin.tickets.show', $ticket->id);
            $admin->pushNotify($title, $image, $link);
        }
    }
}

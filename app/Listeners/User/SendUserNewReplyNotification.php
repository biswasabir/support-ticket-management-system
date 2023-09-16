<?php

namespace App\Listeners\User;

use App\Events\AdminNewReplyCreated;
use App\Events\AgentNewReplyCreated;

class SendUserNewReplyNotification
{
    public function handleAgentNewReplyCreated(AgentNewReplyCreated $event)
    {
        $this->userNotify($event->ticketReply);
    }

    public function handleAdminNewReplyCreated(AdminNewReplyCreated $event)
    {
        $this->userNotify($event->ticketReply);
    }

    private function userNotify($ticketReply)
    {
        $ticket = $ticketReply->ticket;
        $user = $ticket->user;
        $user->sendNewReplyNotification($ticketReply);
        $title = str(lang('New Reply On Ticket #{number}', 'notifications'))->replace('{number}', $ticket->id);
        $image = asset('images/notifications/reply.png');
        $link = route('user.tickets.show', $ticket->id);
        $user->pushNotify($title, $image, $link);
    }
}

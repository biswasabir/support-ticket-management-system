<?php

namespace App\Listeners\Agent;

use App\Events\UserNewReplyCreated;
use App\Models\Department;

class SendAgentNewReplyNotification
{
    public function handle(UserNewReplyCreated $event)
    {
        $ticketReply = $event->ticketReply;
        $ticket = $ticketReply->ticket;
        $agents = Department::where('id', $ticket->department->id)->active()->first()->users;
        foreach ($agents as $agent) {
            $agent->sendNewReplyNotification($ticketReply);
            $title = str(lang('New Reply On Ticket #{number}', 'notifications'))->replace('{number}', $ticket->id);
            $image = asset('images/notifications/reply.png');
            $link = route('agent.tickets.show', $ticket->id);
            $agent->pushNotify($title, $image, $link);
        }
    }
}

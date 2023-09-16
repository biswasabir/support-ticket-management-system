<?php

namespace App\Listeners\Agent;

use App\Events\AdminTicketCreated;
use App\Events\UserTicketCreated;
use App\Models\Department;

class SendAgentNewTicketNotification
{
    public function handleUserTicketCreated(UserTicketCreated $event)
    {
        $this->agentNotify($event->ticket);
    }

    public function handleAdminTicketCreated(AdminTicketCreated $event)
    {
        $this->agentNotify($event->ticket);
    }

    private function agentNotify($ticket)
    {
        $agents = Department::where('id', $ticket->department->id)->active()->first()->users;
        foreach ($agents as $agent) {
            $agent->sendNewTicketNotification($ticket);
            $title = str(lang('New Ticket Created #{number}', 'notifications'))->replace('{number}', $ticket->id);
            $image = asset('images/notifications/ticket.png');
            $link = route('agent.tickets.show', $ticket->id);
            $agent->pushNotify($title, $image, $link);
        }
    }
}

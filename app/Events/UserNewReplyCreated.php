<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserNewReplyCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticketReply;

    public function __construct($ticketReply)
    {
        $this->ticketReply = $ticketReply;
    }
}

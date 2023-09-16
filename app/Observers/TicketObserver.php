<?php

namespace App\Observers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class TicketObserver
{
    public function deleted(Ticket $ticket)
    {
        $ticket->replies->each(function ($reply) use ($ticket) {
            $reply->attachments->each(function ($attachment) use ($ticket) {
                $disk = Storage::disk('public');
                $disk->deleteDirectory("tickets/{$ticket->id}");
            });
        });
    }
}

<?php

namespace App\Notifications\Agent;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgentNewReplyNotification extends Notification
{
    use Queueable;

    public $ticketReply;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticketReply)
    {
        $this->ticketReply = $ticketReply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $ticketReply = $this->ticketReply;
        $ticket = $ticketReply->ticket;
        $template = mailTemplate('agent_new_reply_notification');
        return (new MailMessage)
            ->subject($template->subject)
            ->markdown('emails.default', [
                'body' => $template->body,
                'short_codes' => [
                    '{{username}}' => $ticket->user->getName(),
                    '{{number}}' => $ticket->id,
                    '{{message}}' => nl2br(e($ticketReply->body)),
                    '{{link}}' => route('agent.tickets.show', $ticket->id),
                    '{{date}}' => dateFormat($ticketReply->created_at),
                    '{{website_name}}' => settings('general')->site_name,
                ],
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

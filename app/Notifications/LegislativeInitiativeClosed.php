<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LegislativeInitiativeClosed extends Notification
{
    use Queueable;

    protected $item;
    protected $action;
    protected $data;
    protected $subscriptionsLink;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($item, $action, $subscriptionsLink = true)
    {
        $this->item = $item;
        $this->action = $action;
        $this->subscriptionsLink = $subscriptionsLink;
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
        $this->data = array('item' => $this->item);
        $this->data['showSubscriptionLink'] = $this->subscriptionsLink;
        if($this->action != 'deleted'){
            $this->data['url'] = route('legislative_initiatives.view', $this->item->id);
        }

        return (new MailMessage)
            ->subject('[Strategy.bg] '.__('notifications_msg.legislative_initiative.closed.subject').': '.$this->item->facebookTitle)
            ->markdown('emails.legislative_initiative.unsuccessful', ['data' => $this->data]);
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

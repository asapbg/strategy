<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdvBoardsMsgToModerator extends Notification
{
    use Queueable;

    protected $msgData;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($msgData)
    {
        $this->msgData = $msgData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line($this->msgData['title'] ?? 'Съобщение от модератор Консултативни съвети')
                    ->line($this->msgData['content'] ?? 'Съдържание на съобщение от модератор Консултативни съвети');
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
            'subject' => $this->msgData['title'] ?? 'Съобщение от модератор Консултативни съвети',
            'message' => $this->msgData['content'] ?? 'Съдържание на съобщение от модератор Консултативни съвети',
            'from' => auth()->user()->id,
            'from_name' => auth()->user()->fullName(),
            'to' => $notifiable->id,
            'to_name' => $notifiable->fullName(),
        ];
    }
}

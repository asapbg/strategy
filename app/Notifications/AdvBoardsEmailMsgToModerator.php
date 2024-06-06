<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdvBoardsEmailMsgToModerator extends Notification
{
    use Queueable;

    protected $notificationInfo;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notificationInfo)
    {
        $this->notificationInfo = $notificationInfo;
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
        $data = json_decode($this->notificationInfo, true);
        return (new MailMessage)
                    ->subject('[Strategy.bg] '.__('Ново съобщение в Портала за обществени консултацииe'))
                    ->line('Имате ново съобщение в Портала за обществени консултации')
                    ->action('Отвори съобщението', route('admin.user.notification_show', $data['id']));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = json_decode($this->notificationInfo, true);
        return [
            'subject' => $data['data']['subject'],
            'message' => $data['data']['message'],
            'from' => $data['data']['from']
        ];
    }
}

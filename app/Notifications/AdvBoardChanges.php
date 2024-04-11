<?php

namespace App\Notifications;

use App\Models\AdvisoryBoard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdvBoardChanges extends Notification
{
    use Queueable;

    protected $item;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AdvisoryBoard $item)
    {
        $this->item = $item;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; //, 'database'
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
                    ->subject(__('notifications_msg.adv_board_changes'))
                    ->line(__('notifications_msg.adv_board_changes.extra_info'))
                    ->action($this->item->name, route('admin.advisory-boards.edit', $this->item));
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
            'model' => get_class($this->item),
            'id' => $this->item->id,
            'subject' => __('notifications_msg.adv_board_changes'),
            'message' => __('notifications_msg.adv_board_changes.extra_info')
        ];
    }
}

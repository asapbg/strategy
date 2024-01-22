<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdvBoardUpToDateCheck extends Notification
{
    use Queueable;

    protected $items;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; //, 'mail'
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $msg = (new MailMessage)->subject(__('notifications_msg.adv_board_up_to_date'))
            ->line(__('notifications_msg.adv_board_up_to_date.extra_info'));

        if(sizeof($this->items)) {
            foreach ($this->items as $item){
                $msg->action($item->name, route('admin.advisory-boards.edit', $item));
            }
        }

        return $msg;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $list = '';
        if(sizeof($this->items)) {
            foreach ($this->items as $item) {
                $list .= '<br><a href="' . route('admin.advisory-boards.edit', $item) . '">' . $item->name . '</a>';
            }
        }

        return [
            //'model' => get_class($this->item),
            //'id' => $this->item->id,
            'subject' => __('notifications_msg.adv_board_up_to_date'),
            'message' => __('notifications_msg.adv_board_up_to_date.extra_info').$list
        ];
    }
}

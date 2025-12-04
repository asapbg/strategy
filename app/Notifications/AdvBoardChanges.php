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
    protected $section;
    protected $changes;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AdvisoryBoard $item, $section = '', $changes = array())
    {
        $this->item = $item;
        $this->section = $section;
        $this->changes = $changes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; //,'database'
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[Strategy.bg] ' . __('notifications_msg.adv_board_changes') . ': ' . $this->item->name)
            ->line(__('notifications_msg.adv_board_changes.extra_info'))
            ->action($this->item->name, route('admin.advisory-boards.edit', $this->item));
    }

    public function toArray(object $notifiable): array
    {
        $extraMsg = !empty($this->section) . '<br>Секция: ' . $this->section;
        if (sizeof($this->changes)) {
            $extraMsg .= '<table class="table table-sm my-3"><tbody>';

            $mainTitle = false;
            foreach ($this->changes as $key => $change) {
                if (!in_array($key, ['bg', 'en'])) {
                    if (!$mainTitle) {
                        $extraMsg .= '<tr><th colspan="3">Промени:</th></tr>';
                        $extraMsg .= '<tr><th>Поле</th><th>Старо състояние:</th><th>Ново състояние:</th></tr>';
                        $mainTitle = true;
                    }
                    $extraMsg .= '<tr><td>' . __('custom.' . $key) . '</td><td>' . $change['old'] . '</td><td>' . $change['new'] . '</td></tr>';
                }
            }

            foreach ($this->changes as $key => $change) {
                if (in_array($key, ['bg', 'en'])) {
                    $extraMsg .= '<tr><th colspan="3">Промени в преводи (' . strtoupper($key) . '):</th></tr>';
                    $extraMsg .= '<tr><th>Поле</th><th>Старо състояние:</th><th>Ново състояние:</th></tr>';
                    foreach ($change as $field => $value) {
                        $extraMsg .= '<tr><td>' . __('custom.' . $field) . '</td><td>' . $value['old'] . '</td><td>' . $value['new'] . '</td></tr>';
                    }
                }
            }

            $extraMsg .= '</tbody></table>';
        }

        return [
            'model' => get_class($this->item),
            'id' => $this->item->id,
            'subject' => __('notifications_msg.adv_board_changes') . ' (' . $this->item->name . ')',
            'message' => __('notifications_msg.adv_board_changes.extra_info') . $this->item->name . $extraMsg
        ];
    }
}

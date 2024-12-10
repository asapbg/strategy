<?php

namespace App\Notifications;

use App\Models\AdvisoryBoard;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class AdvisoryBoardMeeting extends Notification
{

    use Queueable;

    protected $advisoryBoard;
    protected $advisoryBoardMeeting;

    protected $link;

    protected $include_files = true;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AdvisoryBoard $advisoryBoard, \App\Models\AdvisoryBoardMeeting $meeting, string $link = '', bool $include_files = true)
    {
        $this->advisoryBoard = $advisoryBoard;
        $this->advisoryBoardMeeting = $meeting;
        $this->link = $link;
        $this->include_files = $include_files;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Покана за заседание на ' . $this->advisoryBoard->name)
            ->line('Уважаеми членове на ' . $this->advisoryBoard->name .
                ', Във връзка със задълженията на ' . $this->advisoryBoard->name . ', ви уведомяваме за предстоящо заседание:')
            ->line('Дата на заседанието: ' . Carbon::parse($this->advisoryBoardMeeting->next_meeting)->format('d.m.Yг.'));

        if (!empty($this->link)) {
            $message->action('Повече информация може да намерите тук', $this->link);
        }

        if ($this->include_files) {
            foreach ($this->advisoryBoardMeeting->files->where('locale', 'bg')->values() as $file) {
                $message->attach(Storage::disk('public_uploads')->download($file->path, $file->filename), [
                    'as'    => $file->filename,
                    'mime'  => $file->content_type,
                ]);
            }
        }

        return $message;
    }

    public function toArray(object $notifiable)
    {
        //
    }
}

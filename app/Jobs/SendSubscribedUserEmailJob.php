<?php

namespace App\Jobs;

use App\Mail\NotifySubscribedUser;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSubscribedUserEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var mixed
     */
    private mixed $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $administrators = isset($this->data['administrators'])
            ? $this->data['administrators']
            : null;
        $moderators = isset($this->data['moderators'])
            ? $this->data['moderators']
            : null;
        $subscribedUsers = isset($this->data['subscribedUsers'])
            ? $this->data['subscribedUsers']
            : null;
        unset($this->data['administrators'], $this->data['moderators'], $this->data['subscribedUsers']);

        foreach (['admin', 'moderator', 'user'] as $type) {
            $var = $type."_text";
            if ($this->data['modelInstance'] instanceof PublicConsultation) {
                if ($this->data['event'] == "new-comment") {
                    ${$var} = __("New consultation comment $type text");
                } else {
                    ${$var} = __("New consultation $type text");
                }
            }
        }

        if ($administrators) {
            foreach ($administrators as $admin) {
                $this->data['text'] = $admin_text;
                Mail::to($admin['email'])->send(new NotifySubscribedUser($admin, $this->data));
            }
        }
        if ($moderators) {
            foreach ($moderators as $moderator) {
                $this->data['text'] = $moderator_text;
                Mail::to($moderator['email'])->send(new NotifySubscribedUser($moderator, $this->data));
            }
        }
        if ($subscribedUsers) {
            foreach ($subscribedUsers as $subscribedUser) {
                $this->data['text'] = $user_text;
                $user = $subscribedUser->user;
                Mail::to($user['email'])->send(new NotifySubscribedUser($user, $this->data));
            }
        }
    }
}

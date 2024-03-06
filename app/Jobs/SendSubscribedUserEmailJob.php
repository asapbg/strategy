<?php

namespace App\Jobs;

use App\Mail\NotifySubscribedUser;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\StrategicDocument;
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
            $varSubject = $type."_subject_text";
            $varUrl = $type."_url";
            if ($this->data['modelInstance'] instanceof PublicConsultation) {
                if ($this->data['event'] == "new-comment") {
                    ${$var} = __("New consultation comment $type text");
                    ${$varSubject} = __("New consultation comment");
                } elseif ($this->data['event'] == "created") {
                    ${$var} = __("New consultation $type text");
                    ${$varSubject} = __("New consultation");
                } else{
                    ${$var} = __("Update consultation $type text");
                    ${$varSubject} = __("Update consultation");
                }
                ${$varUrl} = match ($this) {
                    'user' => route('public_consultation.view', ['id' => $this->data['modelInstance']->id]),
                    default => route('admin.consultations.public_consultations.edit', $this->data['modelInstance']),
                };

            } elseif ($this->data['modelInstance'] instanceof StrategicDocument) {
                if ($this->data['event'] == "updated") {
                    ${$var} = __("Update strategic document $type text");
                    ${$varSubject} = __("Update strategic document");
                } else {
                    ${$var} = __("New strategic document $type text");
                    ${$varSubject} = __("New strategic document");
                }
                ${$varUrl} = match ($type) {
                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
                    default => route('admin.strategic_documents.edit', ['id' => $this->data['modelInstance']->id]),
                };
            }
        }
        if ($administrators) {
            foreach ($administrators as $admin) {
                $this->data['text'] = $admin_text;
                $this->data['subject'] = $admin_subject_text;
                $this->data['url'] = $admin_url;
                $mail = config('app.env') != 'production' ? config('mail.local_to_mail') : $admin['email'];
                Mail::to($mail)->send(new NotifySubscribedUser($admin, $this->data));
            }
        }
        if ($moderators) {
            foreach ($moderators as $moderator) {
                $this->data['text'] = $moderator_text;
                $this->data['subject'] = $moderator_subject_text;
                $this->data['url'] = $moderator_url;
                $mail = config('app.env') != 'production' ? config('mail.local_to_mail') : $moderator['email'];
                Mail::to($mail)->send(new NotifySubscribedUser($moderator, $this->data));
            }
        }
        if ($subscribedUsers) {
            foreach ($subscribedUsers as $subscribedUser) {
                $this->data['text'] = $user_text;
                $this->data['subject'] = $user_subject_text;
                $this->data['url'] = $user_url;
                $user = $subscribedUser->user;
                $mail = config('app.env') != 'production' ? config('mail.local_to_mail') : $user['email'];
                Mail::to($mail)->send(new NotifySubscribedUser($user, $this->data));
            }
        }
    }
}

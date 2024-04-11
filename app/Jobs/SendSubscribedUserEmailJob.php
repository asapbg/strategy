<?php

namespace App\Jobs;

use App\Enums\PublicationTypesEnum;
use App\Mail\NotifySubscribedUser;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Models\Comments;
use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\PublicConsultation;
use App\Models\LegislativeInitiative;
use App\Models\OgpPlan;
use App\Models\Poll;
use App\Models\Pris;
use App\Models\Publication;
use App\Models\StrategicDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
                } elseif ($this->data['event'] == "expire") {
                    ${$var} = __("Public consultation expire soon in couple of days", ['days' => PublicConsultation::NOTIFY_DAYS_BEFORE_END]);
                    ${$varSubject} = __("Public consultation expire soon");
                } elseif ($this->data['event'] == "created_with_pc") {
                    ${$var} = __("Public consultation accept pris $type text", ['days' => PublicConsultation::NOTIFY_DAYS_BEFORE_END]);
                    ${$varSubject} = __("Public consultation accept pris");
                } else{
                    ${$var} = __("Update consultation $type text");
                    ${$varSubject} = __("Update consultation");
                }
                ${$varUrl} = match ($this) {
//                    'user' => route('public_consultation.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.consultations.public_consultations.edit', $this->data['modelInstance']),
                    default => route('public_consultation.view', ['id' => $this->data['modelInstance']->id]),
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
//                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.strategic_vdocuments.edit', ['id' => $this->data['modelInstance']->id]),
                    default => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof Publication) {
                if ($this->data['event'] == "created") {
                    ${$var} = $this->data['modelInstance']->type == PublicationTypesEnum::TYPE_LIBRARY->value ? __("New publication $type text") : __("New news $type text");
                    ${$varSubject} = $this->data['modelInstance']->type == PublicationTypesEnum::TYPE_LIBRARY->value ? __("New publication") : __("New news");
                }
                ${$varUrl} = match ($type) {
//                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.strategic_vdocuments.edit', ['id' => $this->data['modelInstance']->id]),
                    default => route('library.details', ['type' => $this->data['modelInstance']->type, 'id' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof AdvisoryBoard) {
                if ($this->data['event'] == "created") {
                    ${$var} = __("New adv board $type text");
                    ${$varSubject} = __("New adv board");
                }
                ${$varUrl} = match ($type) {
//                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.strategic_vdocuments.edit', ['id' => $this->data['modelInstance']->id]),
                    default => route('advisory-boards.view', ['item' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof AdvisoryBoardMeeting) {
                if ($this->data['event'] == "created") {
                    ${$var} = __("New adv board meeting $type text");
                    ${$varSubject} = __("New adv board meeting");
                }
                ${$varUrl} = match ($type) {
//                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.strategic_vdocuments.edit', ['id' => $this->data['modelInstance']->id]),
                    default => route('advisory-boards.view', ['item' => $this->data['modelInstance']->advisory_board_id]),
                };
            } elseif ($this->data['modelInstance'] instanceof Pris) {
                if ($this->data['event'] == "created") {
                    ${$var} = __("New pris $type text");
                    ${$varSubject} = __("New pris");
                }
                ${$varUrl} = match ($type) {
//                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.strategic_vdocuments.edit', ['id' => $this->data['modelInstance']->id]),
                    default => route('pris.view', ['category' => Str::slug($this->data['modelInstance']->actType?->name), 'id' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof LegislativeProgram) {
                if ($this->data['event'] == "created") {
                    ${$var} = __("New lp $type text");
                    ${$varSubject} = __("New lp");
                }
                ${$varUrl} = match ($type) {
//                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.strategic_vdocuments.edit', ['id' => $this->data['modelInstance']->id]),
                    default => route('lp.view', ['id' => $this->data['modelInstance']->id]),
                };
            }elseif ($this->data['modelInstance'] instanceof OperationalProgram) {
                if ($this->data['event'] == "created") {
                    ${$var} = __("New op $type text");
                    ${$varSubject} = __("New op");
                }
                ${$varUrl} = match ($type) {
//                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.strategic_vdocuments.edit', ['id' => $this->data['modelInstance']->id]),
                    default => route('op.view', ['id' => $this->data['modelInstance']->id]),
                };
            }  elseif ($this->data['modelInstance'] instanceof Poll) {
                if ($this->data['event'] == "created") {
                    ${$var} = __("New poll $type text");
                    ${$varSubject} = __("New poll");

                    ${$varUrl} = match ($type) {
//                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.strategic_vdocuments.edit', ['id' => $this->data['modelInstance']->id]),
                        default => route('poll.show', ['id' => $this->data['modelInstance']->id]),
                    };
                } elseif ($this->data['event'] == "pc_poll_created"){
                    ${$var} = __("New poll to pc $type text");
                    ${$varSubject} = __("New poll to pc");

                    ${$varUrl} = match ($type) {
//                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.strategic_vdocuments.edit', ['id' => $this->data['modelInstance']->id]),
                        default => route('public_consultation.view', ['id' => $this->data['secondModelInstance']->id]),
                    };
                }

            } elseif ($this->data['modelInstance'] instanceof OgpPlan) {
                if ($this->data['event'] == "created") {
                    ${$var} = __("New ogp $type text");
                    ${$varSubject} = __("New ogp");
                } else if($this->data['event'] == "created_report"){
                    ${$var} = __("Ogp plan report $type text");
                    ${$varSubject} = __("Ogp plan report");
                }
                ${$varUrl} = match ($type) {
//                    'user' => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
//                    default => route('admin.strategic_vdocuments.edit', ['id' => $this->data['modelInstance']->id]),
                    default => route('ogp.national_action_plans.show', ['id' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof LegislativeInitiative) {
                if ($this->data['event'] == "updated") {
                    ${$var} = __("Update legislative initiative $type text");
                    ${$varSubject} = __("Update legislative initiative");
                }elseif($this->data['event'] == "comment"){
                    ${$var} = __("Comment legislative initiative $type text");
                    ${$varSubject} = __("Comment legislative initiative");
                } else {
                    ${$var} = __("New legislative initiative $type text");
                    ${$varSubject} = __("New legislative initiative");
                }
                ${$varUrl} = match ($type) {
//                    'user' => route('legislative_initiatives.view', ['item' => $this->data['modelInstance']->id]),
//                    default => route('admin.legislative_initiatives.view', ['item' => $this->data['modelInstance']->id]),
                    default => route('legislative_initiatives.view', ['item' => $this->data['modelInstance']->id]),
                };
            }
        }
        if ($administrators) {
            foreach ($administrators as $admin) {
                $this->data['text'] = $admin_text;
                $this->data['subject'] = $admin_subject_text;
                $this->data['url'] = $admin_url;
                $mail = config('app.env') != 'production' ? config('mail.local_to_mail') : $admin->email;
                Mail::to($mail)->send(new NotifySubscribedUser($admin, $this->data));
            }
        }
        if ($moderators) {
            foreach ($moderators as $moderator) {
                $this->data['text'] = $moderator_text;
                $this->data['subject'] = $moderator_subject_text;
                $this->data['url'] = $moderator_url;
                $mail = config('app.env') != 'production' ? config('mail.local_to_mail') : $moderator->email;
                Mail::to($mail)->send(new NotifySubscribedUser($moderator, $this->data));
            }
        }
        if ($subscribedUsers) {
            foreach ($subscribedUsers as $subscribedUser) {
                $this->data['text'] = $user_text;
                $this->data['subject'] = $user_subject_text;
                $this->data['url'] = $user_url;
                $user = $subscribedUser->user;
                $mail = config('app.env') != 'production' ? config('mail.local_to_mail') : $user->notification_email;
                Mail::to($mail)->send(new NotifySubscribedUser($user, $this->data));
            }
        }
    }
}

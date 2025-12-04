<?php

namespace App\Jobs;

use App\Enums\PublicationTypesEnum;
use App\Mail\NotifySubscribedUser;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\LegislativeInitiative;
use App\Models\OgpPlan;
use App\Models\Poll;
use App\Models\Pris;
use App\Models\Publication;
use App\Models\StrategicDocument;
use App\Models\User;
use App\Models\UserSubscribe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendSubscribedUserEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var mixed
     */
    private mixed $data;
    private $subscribedUsers;

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
        $specialUser = $this->data['specialUser'] ?? null;
        $modelInstance = $this->data['modelInstance'] ?? null;
        $event = $this->data['event'] ?? null;
        unset($this->data['administrators'], $this->data['moderators'], $this->data['subscribedUsers'], $this->data['specialUser']);

        $data = $this->getSubscriptions($event, $modelInstance);
        $administrators = $data['administrators'];
        $moderators = $data['moderators'];
        $subscribedUsers = $data['subscribedUsers'];

        if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
            return;
        }

        foreach (['admin', 'moderator', 'user'] as $type) {
            $this->data[$type]['text'] = "";
            $this->data[$type]['subject_text'] = "";
            $this->data[$type]['url'] = "";
            if ($this->data['modelInstance'] instanceof PublicConsultation) {
                if ($this->data['event'] == "new-comment") {
                    $this->data[$type]['text'] = __("New consultation comment $type text");
                    $this->data[$type]['subject_text'] = __("New consultation comment");
                } elseif ($this->data['event'] == "expire") {
                    $this->data[$type]['text'] = __("Public consultation expire soon in couple of days", ['days' => PublicConsultation::NOTIFY_DAYS_BEFORE_END]);
                    $this->data[$type]['subject_text'] = __("Public consultation expire soon");
                } elseif ($this->data['event'] == "created_with_pc" || $this->data['event'] == "updated_with_pc") {
                    $this->data[$type]['text'] = __("Public consultation accept pris $type text", ['days' => PublicConsultation::NOTIFY_DAYS_BEFORE_END]);
                    $this->data[$type]['subject_text'] = __("Public consultation accept pris");
                } elseif ($this->data['event'] == "created") {
                    $this->data[$type]['text'] = __("New consultation $type text");
                    $this->data[$type]['subject_text'] = __("New consultation");
                } else {
                    $this->data[$type]['text'] = __("Update consultation $type text");
                    $this->data[$type]['subject_text'] = __("Update consultation");
                }
                $this->data[$type]['url'] = match ($this) {
                    default => route('public_consultation.view', ['id' => $this->data['modelInstance']->id]),
                };

            } elseif ($this->data['modelInstance'] instanceof StrategicDocument) {
                if ($this->data['event'] == "updated") {
                    $this->data[$type]['text'] = __("Update strategic document $type text");
                    $this->data[$type]['subject_text'] = __("Update strategic document");
                    if ($this->data['secondModelInstance']) {

                    }
                } else {
                    $this->data[$type]['text'] = __("New strategic document $type text");
                    $this->data[$type]['subject_text'] = __("New strategic document");
                }
                $this->data[$type]['url'] = match ($type) {
                    default => route('strategy-document.view', ['id' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof Publication) {
                if ($this->data['event'] == "created") {
                    $this->data[$type]['text'] = $this->data['modelInstance']->type == PublicationTypesEnum::TYPE_LIBRARY->value
                        ? __("New publication $type text")
                        : __("New news $type text");
                    $this->data[$type]['subject_text'] = $this->data['modelInstance']->type == PublicationTypesEnum::TYPE_LIBRARY->value
                        ? __("New publication")
                        : __("New news");
                }
                $this->data[$type]['url'] = match ($type) {
                    default => route('library.details', ['type' => $this->data['modelInstance']->type, 'id' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof AdvisoryBoard) {
                if ($this->data['event'] == "created") {
                    $this->data[$type]['text'] = __("New adv board $type text");
                    $this->data[$type]['subject_text'] = __("New adv board");
                }
                $this->data[$type]['url'] = match ($type) {
                    default => route('advisory-boards.view', ['item' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof AdvisoryBoardMeeting) {
                if ($this->data['event'] == "created") {
                    $this->data[$type]['text'] = __("New adv board meeting $type text");
                    $this->data[$type]['subject_text'] = __("New adv board meeting");
                }
                $this->data[$type]['url'] = match ($type) {
                    default => route('advisory-boards.view', ['item' => $this->data['modelInstance']->advisory_board_id]),
                };
            } elseif ($this->data['modelInstance'] instanceof Pris) {
                if ($this->data['event'] == "created") {
                    $this->data[$type]['text'] = __("New pris $type text");
                    $this->data[$type]['subject_text'] = __("New pris");
                } elseif ($this->data['event'] == "update") {
                    $this->data[$type]['text'] = __("Update pris $type text");
                    $this->data[$type]['subject_text'] = __("Update pris");
                } else {
                    $this->data[$type]['text'] = __("Update pris $type text");
                    $this->data[$type]['subject_text'] = __("Update pris");
                }
                $this->data[$type]['url'] = match ($type) {
                    default => ($this->data['modelInstance']->in_archive
                        ? route('pris.archive.view', ['category' => Str::slug($this->data['modelInstance']->actType?->name), 'id' => $this->data['modelInstance']->id])
                        : route('pris.view', ['category' => Str::slug($this->data['modelInstance']->actType?->name), 'id' => $this->data['modelInstance']->id])),
                };
            } elseif ($this->data['modelInstance'] instanceof LegislativeProgram) {
                if ($this->data['event'] == "created") {
                    $this->data[$type]['text'] = __("New lp $type text");
                    $this->data[$type]['subject_text'] = __("New lp");
                }
                $this->data[$type]['url'] = match ($type) {
                    default => route('lp.view', ['id' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof OperationalProgram) {
                if ($this->data['event'] == "created") {
                    $this->data[$type]['text'] = __("New op $type text");
                    $this->data[$type]['subject_text'] = __("New op");
                }
                $this->data[$type]['url'] = match ($type) {
                    default => route('op.view', ['id' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof Poll) {
                if ($this->data['event'] == "created") {
                    $this->data[$type]['text'] = __("New poll $type text");
                    $this->data[$type]['subject_text'] = __("New poll");

                    $this->data[$type]['url'] = match ($type) {
                        default => route('poll.show', ['id' => $this->data['modelInstance']->id]),
                    };
                } elseif ($this->data['event'] == "pc_poll_created") {
                    $this->data[$type]['text'] = __("New poll to pc $type text");
                    $this->data[$type]['subject_text'] = __("New poll to pc");

                    $this->data[$type]['url'] = match ($type) {
                        default => route('public_consultation.view', ['id' => $this->data['secondModelInstance']->id]),
                    };
                }

            } elseif ($this->data['modelInstance'] instanceof OgpPlan) {
                if ($this->data['event'] == "created") {
                    $this->data[$type]['text'] = __("New ogp $type text");
                    $this->data[$type]['subject_text'] = __("New ogp");
                } else if ($this->data['event'] == "created_report") {
                    $this->data[$type]['text'] = __("Ogp plan report $type text");
                    $this->data[$type]['subject_text'] = __("Ogp plan report");
                }
                $this->data[$type]['url'] = match ($type) {
                    default => route('ogp.national_action_plans.show', ['id' => $this->data['modelInstance']->id]),
                };
            } elseif ($this->data['modelInstance'] instanceof LegislativeInitiative) {
                if ($this->data['event'] == "updated") {
                    $this->data[$type]['text'] = __("Update legislative initiative $type text");
                    $this->data[$type]['subject_text'] = __("Update legislative initiative");
                } elseif ($this->data['event'] == "comment") {
                    $this->data[$type]['text'] = __("Comment legislative initiative $type text");
                    $this->data[$type]['subject_text'] = __("Comment legislative initiative");
                } else {
                    $this->data[$type]['text'] = __("New legislative initiative $type text");
                    $this->data[$type]['subject_text'] = __("New legislative initiative");
                }
                $this->data[$type]['url'] = match ($type) {
                    default => route('legislative_initiatives.view', ['item' => $this->data['modelInstance']->id]),
                };
            }
        }

        if (isset($this->data['modelInstance']) && defined(get_class($this->data['modelInstance']) . '::MODULE_NAME')) {
            $variable = get_class($this->data['modelInstance'])::MODULE_NAME;
        }
        if (isset($this->data['secondModelInstance']) && defined(get_class($this->data['secondModelInstance']) . '::MODULE_NAME')) {
            $variable = get_class($this->data['secondModelInstance'])::MODULE_NAME;
        }
        $log_email_subscription = isset($variable) ? trans_choice($variable, 1). " - ". $this->data['modelName'] : "";
        $is_production = app()->environment() == "production";
        $is_production = true;
        if ($administrators && $is_production) {
            foreach ($administrators as $admin) {
                $this->data['text'] = $this->data['admin']['text'];
                $this->data['subject'] = '[Strategy.bg] ' . $this->data['admin']['subject_text'] . (isset($this->data['modelName']) ? ': ' . $this->data['modelName'] : '');
                $this->data['url'] = $this->data['admin']['url'];
                $mail = config('app.env') != 'production'
                    ? config('mail.local_to_mail')
                    : $admin->email;

                Log::channel('emails')->info("Send email to administrator ".$admin->fullName(). " with email: $admin->email, for $log_email_subscription");

//                Mail::to($mail)->send(new NotifySubscribedUser($admin, $this->data, false));
//                sleep(2);
            }
        }
        if ($moderators && $is_production) {
            foreach ($moderators as $moderator) {
                $this->data['text'] = $this->data['moderator']['text'];
                $this->data['subject'] = '[Strategy.bg] ' . $this->data['moderator']['subject_text'] . (isset($this->data['modelName']) ? ': ' . $this->data['modelName'] : '');
                $this->data['url'] = $this->data['moderator']['url'];
                $mail = config('app.env') != 'production'
                    ? config('mail.local_to_mail')
                    : $moderator->email;

                Log::channel('emails')->info("Send email to moderator ".$moderator->fullName(). " with email: $moderator->email, for $log_email_subscription");

//                Mail::to($mail)->send(new NotifySubscribedUser($moderator, $this->data, false));
//                sleep(2);
            }
        }
        if ($subscribedUsers) {
            foreach ($subscribedUsers as $subscribedUser) {
                $this->data['text'] = $this->data['user']['text'];
                $this->data['subject'] = '[Strategy.bg] ' . $this->data['user']['subject_text'] . (isset($this->data['modelName']) ? ': ' . $this->data['modelName'] : '');
                $this->data['url'] = $this->data['user']['url'];
                $user = $subscribedUser->user;
                if ($user) {
                    $mail = $user->notification_email ?? $user->email;

                    Log::channel('emails')->info("Send email to subscribed user ".$user->fullName(). " with email: $user->email, for $log_email_subscription");

//                    Mail::to($mail)->send(new NotifySubscribedUser($user, $this->data));
//                    sleep(2);
                }
            }
        }

        if ($specialUser) {
            Log::error($specialUser);
            $this->data['text'] = $this->data['user']['text'];
            $this->data['subject'] = '[Strategy.bg] ' . $this->data['user']['subject_text'] . (isset($this->data['modelName']) ? ': ' . $this->data['modelName'] : '');
            $this->data['url'] = $this->data['user']['url'];
            $user = $specialUser;
            if ($user) {
                $mail = $user->notification_email ?? $user->email;

                Log::channel('emails')->info("Send email to spatial user ".$user->fullName(). " with email: $user->email, for $log_email_subscription");

                Mail::to($mail)->send(new NotifySubscribedUser($user, $this->data, false));
            }
        }
    }

    /**
     * @param mixed $event
     * @param $modelInstance
     * @return array
     */
    private function getSubscriptions(mixed $event, $modelInstance): array
    {
        $administrators = User::whereActive(true)
            ->hasRole(CustomRole::ADMIN_USER_ROLE)
            //->whereRaw("email::TEXT NOT LIKE '%@asap.bg%'")
            //->take(1)
            ->get();
        if (
            $modelInstance instanceof Pris || $modelInstance instanceof LegislativeProgram || $modelInstance instanceof OperationalProgram
            || $modelInstance instanceof AdvisoryBoard || $modelInstance instanceof OgpPlan || $modelInstance instanceof Publication
            || ($modelInstance instanceof LegislativeInitiative && $event == "comment")
        ) {
            $administrators = null;
        }
        $moderators = $this->getModerators($modelInstance);
        //dd($moderators->toArray());
        $this->subscribedUsers = User::where('id', 0)->get();
        $model_class = get_class($modelInstance);

        if (
            $modelInstance instanceof Pris
            || $modelInstance instanceof PublicConsultation
            || $modelInstance instanceof LegislativeInitiative
            || $modelInstance instanceof LegislativeProgram
            || $modelInstance instanceof OperationalProgram
            || $modelInstance instanceof StrategicDocument
            || $modelInstance instanceof AdvisoryBoard
            || $modelInstance instanceof AdvisoryBoardMeeting
            || $modelInstance instanceof Poll
            || $modelInstance instanceof Publication
            || $modelInstance instanceof OgpPlan
        ) {
            if (in_array($event, ["created", "pc_poll_created", 'created_with_pc'])) {

                $this->filterSubscribedUsers($model_class, $modelInstance);

            }
            if (in_array($event, ["updated", "updated_with_pc", "expire", "comment"])) {

                $this->subscribedUsers = $this->getSubscribedUsers($modelInstance, true);

            }
        }
        if ($modelInstance instanceof PublicConsultation) {

            if ($event == "new-comment") {
                $subscribedUsersIds = $modelInstance->comments->unique('user_id')->pluck('user_id')->toArray();
                if (sizeof($subscribedUsersIds)) {
                    $this->subscribedUsers = User::whereIn('id', $subscribedUsersIds)->get();
                }

                //get users by model ID
                $filterSubscriptions = $this->getSubscribedUsers($modelInstance, true);

                if ($filterSubscriptions->count()) {
                    foreach ($filterSubscriptions as $fSubscribe) {
                        if (!in_array($fSubscribe->id, $subscribedUsersIds)) {
                            $this->subscribedUsers->add($fSubscribe);
                        }
                    }
                }
            }

        }

        //dd($this->subscribedUsers->toArray());
        $data['administrators'] = $administrators;
        $data['moderators'] = $moderators;
        $data['subscribedUsers'] = $this->subscribedUsers;

        return $data;
    }

    /**
     * @param $modelInstance
     * @param bool $subscribable
     * @return mixed
     */
    private function getSubscribedUsers($modelInstance, bool $subscribable = false)
    {
        $model_class = get_class($modelInstance);
        if ($modelInstance instanceof AdvisoryBoardMeeting) {
            $model_class = AdvisoryBoard::class;
        }
        return UserSubscribe::where('subscribable_type', $model_class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
            ->when($subscribable, function ($query) use ($modelInstance) {
                $query->where('subscribable_id', '=', $modelInstance->id);
            }, function ($query) {
                $query->whereNull('subscribable_id');
            })
            ->get();
    }

    /**
     * @param string $model_class
     * @param $modelInstance
     * @return void
     */
    private function filterSubscribedUsers(string $model_class, $modelInstance): void
    {
        $filterSubscriptions = $this->getSubscribedUsers($modelInstance);
        if ($filterSubscriptions->count()) {
            $method = $modelInstance instanceof Pris ? "listIds" : "list";
            if ($modelInstance instanceof AdvisoryBoardMeeting) {
                $model_class = AdvisoryBoard::class;
            }
            foreach ($filterSubscriptions as $fSubscribe) {
                $filter_array = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                $filter_array = array_merge($filter_array, ['id' => $modelInstance->id]);
                if ($filter_array) {
                    $filteredModel = $modelInstance instanceof Publication
                        ? $model_class::$method($filter_array, $modelInstance->type)->whereIn('id', [$modelInstance->id])->first()
                        : $model_class::$method($filter_array)->whereIn('id', [$modelInstance->id])->first() ;
                    if ($filteredModel) {
                        $this->subscribedUsers->add($fSubscribe);
                    }
                }
            }
        }
    }

    /**
     * Get moderators for a some models
     *
     * @param $modelInstance
     * @return null|Collection
     */
    public function getModerators($modelInstance): ?Collection
    {
        $moderators = null;
        if ($modelInstance instanceof AdvisoryBoard || $modelInstance instanceof AdvisoryBoardMeeting) {
            $moderatorsModel = User::select('users.*')
                ->whereActive(true)
                ->whereActivityStatus(User::STATUS_ACTIVE)
                ->hasRole([CustomRole::MODERATOR_ADVISORY_BOARD])
                ->whereNotIn('email', User::EXCLUDE_CONTACT_USER_BY_MAIL)
                //->whereRaw("email::TEXT NOT LIKE '%@asap.bg%'")
                ->join('advisory_board_moderators as m', 'm.user_id', '=', 'users.id')
                ->where('m.advisory_board_id', '=', $modelInstance->id)
                ->whereNull('m.deleted_at');
            $moderatorsSection = User::select('users.*')
                ->whereActive(true)
                ->whereActivityStatus(User::STATUS_ACTIVE)
                ->hasRole([CustomRole::MODERATOR_ADVISORY_BOARDS]);

            $moderators = $moderatorsModel
                ->union($moderatorsSection)
                ->get()
                ->unique('id');
        }
        if ($modelInstance instanceof PublicConsultation) {
            $moderators = User::whereActive(true)
                ->whereActivityStatus(User::STATUS_ACTIVE)
                ->hasRole([CustomRole::MODERATOR_PUBLIC_CONSULTATION])
                ->where('users.id', '=', $modelInstance->user_id)
                ->get()
                ->unique('id');
        }
        if ($modelInstance instanceof OgpPlan) {
            $moderators = User::whereActive(true)
                ->whereActivityStatus(User::STATUS_ACTIVE)
                ->hasRole([CustomRole::MODERATOR_PARTNERSHIP])
                ->get()
                ->unique('id');
        }
        if ($modelInstance instanceof StrategicDocument) {
            $moderatorsInstitution = User::select('users.*')
                ->whereActive(true)
                ->whereActivityStatus(User::STATUS_ACTIVE)
                ->hasRole([CustomRole::MODERATOR_STRATEGIC_DOCUMENT])
                ->whereNotIn('email', User::EXCLUDE_CONTACT_USER_BY_MAIL)
                //->whereRaw("email::TEXT NOT LIKE '%@asap.bg%'")
                ->join('institution_field_of_action as pivot', 'pivot.institution_id', '=', 'users.institution_id')
                ->where('pivot.field_of_action_id', $modelInstance->policy_area_id);

            $moderatorsSection = User::select('users.*')
                ->whereActive(true)
                ->whereActivityStatus(User::STATUS_ACTIVE)
                ->hasRole([CustomRole::MODERATOR_STRATEGIC_DOCUMENTS]);

            $moderators = $moderatorsInstitution
                ->union($moderatorsSection)
                ->get()
                ->unique('id');
        }
        return $moderators;
    }
}

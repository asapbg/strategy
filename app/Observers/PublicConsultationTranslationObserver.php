<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\Consultations\PublicConsultation;
use App\Models\Consultations\PublicConsultationTranslation;
use App\Models\CustomRole;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class PublicConsultationTranslationObserver
{
    /**
     * Handle the PublicConsultationTranslation "created" event.
     *
     * @param \App\Models\Consultations\PublicConsultationTranslation $publicConsultationTranslation
     * @return void
     */
    public function created(PublicConsultationTranslation $publicConsultationTranslation)
    {
        $publicConsultation = $publicConsultationTranslation->parent;
        if (
            !env('DISABLE_OBSERVERS', false)
            && $publicConsultationTranslation->locale == config('app.default_lang')
            && $publicConsultation
        ) {
            if ($publicConsultation->active) {
                if (Setting::allowPostingToFacebook()) {
                    $facebookApi = new Facebook();
                    $facebookApi->postToFacebook($publicConsultation);
                }

                //$this->sendEmails($publicConsultationTranslation, 'created');

                Log::info('Send subscribe email on creation');
            }
        }
    }

    /**
     * Handle the PublicConsultationTranslation "updated" event.
     *
     * @param \App\Models\Consultations\PublicConsultationTranslation $publicConsultationTranslation
     * @return void
     */
    public function updated(PublicConsultationTranslation $publicConsultationTranslation)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            //Check for real changes
            $dirty = $publicConsultationTranslation->getDirty(); //return all changed fields
            //skip some fields in specific cases
            unset($dirty['updated_at']);
            if (sizeof($dirty)) {
                //$this->sendEmails($publicConsultationTranslation, 'updated');
                Log::info('Send subscribe email on update');
            }
        }
    }

    /**
     * Handle the PublicConsultationTranslation "deleted" event.
     *
     * @param \App\Models\Consultations\PublicConsultationTranslation $publicConsultationTranslation
     * @return void
     */
    public function deleted(PublicConsultationTranslation $publicConsultationTranslation)
    {
        //
    }

    /**
     * Handle the PublicConsultationTranslation "restored" event.
     *
     * @param \App\Models\Consultations\PublicConsultationTranslation $publicConsultationTranslation
     * @return void
     */
    public function restored(PublicConsultationTranslation $publicConsultationTranslation)
    {
        //
    }

    /**
     * Handle the PublicConsultationTranslation "force deleted" event.
     *
     * @param \App\Models\Consultations\PublicConsultationTranslation $publicConsultationTranslation
     * @return void
     */
    public function forceDeleted(PublicConsultationTranslation $publicConsultationTranslation)
    {
        //
    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param PublicConsultationTranslation $publicConsultationTranslation
     * @param $event
     * @return void
     */
    private function sendEmails(PublicConsultationTranslation $publicConsultationTranslation, $event): void
    {
        $publicConsultation = $publicConsultationTranslation->parent;
        Log::error($publicConsultation);
        $administrators = null;
        $moderators = null;

        if ($event == "created") {
            $administrators = User::whereActive(true)
                ->hasRole(CustomRole::ADMIN_USER_ROLE)
                ->get();
            $moderators = User::whereActive(true)
                ->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE, CustomRole::MODERATOR_PUBLIC_CONSULTATION])
                ->where('id', '=', $publicConsultation->user_id)
//                ->where('institution_id', $publicConsultation->importer_institution_id)
                ->get()
                ->unique('id');

            $subscribedUsers = UserSubscribe::where('id', 0)->get();
            //get users by model filter
            $filterSubscribtions = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->whereNull('subscribable_id')
                ->get();

            if ($filterSubscribtions->count()) {
                foreach ($filterSubscribtions as $fSubscribe) {
                    $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                    $modelIds = PublicConsultation::list($filterArray, 'title', 'desc', 0)->pluck('id')->toArray();
                    if (in_array($publicConsultation->id, $modelIds)) {
                        $subscribedUsers->add($fSubscribe);
                    }
                }
            }
        } else {
            //get users by model ID
            $subscribedUsers = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->where('subscribable_id', '=', $publicConsultation->id)
                ->get();
        }

        $data['event'] = $event;
        $data['administrators'] = $administrators;
        $data['moderators'] = $moderators;
        $data['subscribedUsers'] = $subscribedUsers;
        $data['modelInstance'] = $publicConsultationTranslation->parent;
        $data['modelName'] = $publicConsultationTranslation->parent->title;
        $data['markdown'] = 'public-consultation';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

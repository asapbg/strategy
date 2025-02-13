<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class PublicConsultationObserver
{
    /**
     * Handle the PublicConsultation "created" event.
     *
     * @param PublicConsultation $publicConsultation
     * @return void
     */
    public function created(PublicConsultation $publicConsultation)
    {

    }

    /**
     * Handle the PublicConsultation "updated" event.
     *
     * @param PublicConsultation $publicConsultation
     * @return void
     */
    public function updated(PublicConsultation $publicConsultation)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $old_active = (int)$publicConsultation->getOriginal('active');

            $dirty = $publicConsultation->getDirty();
            unset($dirty['updated_at']);
            unset($dirty['end_notify']);

            if (!$old_active && $publicConsultation->active) {
                if (Setting::allowPostingToFacebook()) {
                    $facebookApi = new Facebook();
                    $facebookApi->postToFacebook($publicConsultation);
                }
            }

            if ($old_active == (int)$publicConsultation->active) {
                unset($dirty['active']);
            }

            if (sizeof($dirty)) {
                //$this->sendEmails($publicConsultation, 'updated');
                Log::info('Send subscribe email on update');
            }
        }
    }

    /**
     * Send emails
     *
     * @param PublicConsultation $publicConsultation
     * @param $event
     * @return void
     */
    private function sendEmails(PublicConsultation $publicConsultation, $event): void
    {
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
        if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
            return;
        }

        $data['event'] = $event;
        $data['administrators'] = $administrators;
        $data['moderators'] = $moderators;
        $data['subscribedUsers'] = $subscribedUsers;
        $data['modelInstance'] = $publicConsultation;
        $data['modelName'] = $publicConsultation->title;
        $data['markdown'] = 'public-consultation';

        SendSubscribedUserEmailJob::dispatch($data);
    }

    /**
     * Handle the PublicConsultation "deleted" event.
     *
     * @param PublicConsultation $publicConsultation
     * @return void
     */
    public function deleted(PublicConsultation $publicConsultation)
    {
        //
    }

    /**
     * Handle the PublicConsultation "restored" event.
     *
     * @param PublicConsultation $publicConsultation
     * @return void
     */
    public function restored(PublicConsultation $publicConsultation)
    {
        //
    }

    /**
     * Handle the PublicConsultation "force deleted" event.
     *
     * @param PublicConsultation $publicConsultation
     * @return void
     */
    public function forceDeleted(PublicConsultation $publicConsultation)
    {
        //
    }
}

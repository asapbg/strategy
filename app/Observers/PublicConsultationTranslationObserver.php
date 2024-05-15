<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Consultations\PublicConsultation;
use App\Models\Consultations\PublicConsultationTranslation;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class PublicConsultationTranslationObserver
{
    /**
     * Handle the PublicConsultationTranslation "created" event.
     *
     * @param  \App\Models\Consultations\PublicConsultationTranslation  $publicConsultationTranslation
     * @return void
     */
    public function created(PublicConsultationTranslation $publicConsultationTranslation)
    {
        //
    }

    /**
     * Handle the PublicConsultationTranslation "updated" event.
     *
     * @param  \App\Models\Consultations\PublicConsultationTranslation  $publicConsultationTranslation
     * @return void
     */
    public function updated(PublicConsultationTranslation $publicConsultationTranslation)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            //Check for real changes
            $dirty = $publicConsultationTranslation->getDirty(); //return all changed fields
            //skip some fields in specific cases
            unset($dirty['updated_at']);
            if (sizeof($dirty)) {
                $this->sendEmails($publicConsultationTranslation, 'updated');
                Log::info('Send subscribe email on update');
            }
        }
    }

    /**
     * Handle the PublicConsultationTranslation "deleted" event.
     *
     * @param  \App\Models\Consultations\PublicConsultationTranslation  $publicConsultationTranslation
     * @return void
     */
    public function deleted(PublicConsultationTranslation $publicConsultationTranslation)
    {
        //
    }

    /**
     * Handle the PublicConsultationTranslation "restored" event.
     *
     * @param  \App\Models\Consultations\PublicConsultationTranslation  $publicConsultationTranslation
     * @return void
     */
    public function restored(PublicConsultationTranslation $publicConsultationTranslation)
    {
        //
    }

    /**
     * Handle the PublicConsultationTranslation "force deleted" event.
     *
     * @param  \App\Models\Consultations\PublicConsultationTranslation  $publicConsultationTranslation
     * @return void
     */
    public function forceDeleted(PublicConsultationTranslation $publicConsultationTranslation)
    {
        //
    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param PublicConsultationTranslation  $publicConsultationTranslation
     * @param $event
     * @return void
     */
    private function sendEmails(PublicConsultationTranslation  $publicConsultationTranslation, $event): void
    {
        $administrators = null;
        $moderators = null;

        //get users by model ID
        $subscribedUsers = UserSubscribe::where('subscribable_type', PublicConsultation::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
            ->where('subscribable_id', '=', $publicConsultationTranslation->parent->id)
            ->get();

        if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
            return;
        }

        $data['event'] = $event;
        $data['administrators'] = $administrators;
        $data['moderators'] = $moderators;
        $data['subscribedUsers'] = $subscribedUsers;
        $data['modelInstance'] = $publicConsultationTranslation->parent;
        $data['markdown'] = 'public-consultation';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

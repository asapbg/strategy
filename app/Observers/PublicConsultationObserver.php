<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\User;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicConsultationObserver
{
    /**
     * Handle the PublicConsultation "created" event.
     *
     * @param  PublicConsultation  $publicConsultation
     * @return void
     */
    public function created(PublicConsultation $publicConsultation)
    {
        if ($publicConsultation->active) {

            $this->sendEmails($publicConsultation, 'created');

            Log::info('Send subscribe email on creation');
        }
    }

    /**
     * Handle the PublicConsultation "updated" event.
     *
     * @param  PublicConsultation  $publicConsultation
     * @return void
     */
    public function updated(PublicConsultation $publicConsultation)
    {
        $old_active = $publicConsultation->getOriginal('active');

        if (!$old_active && $publicConsultation->active) {

            $this->sendEmails($publicConsultation, 'updated');

            Log::info('Send subscribe email on update');
        }
    }

    /**
     * @param PublicConsultation $publicConsultation
     * @return void
     */
    private function sendEmails(PublicConsultation $publicConsultation, $event): void
    {
        $adminUsers = null;
        if ($event == "created") {
            $adminUsers = User::whereActive(true)
                ->whereHas('hasRole', function ($q) {
                    $q->where('name', CustomRole::ADMIN_USER_ROLE);
                })->get();
        }
        $subscribedUsers = UserSubscribe::where('subscribable_type', PublicConsultation::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', true)
            ->get();

        if (!$adminUsers && $subscribedUsers->count() == 0) {
            return;
        }

        $data['event'] = $event;
        $data['adminUsers'] = $adminUsers;
        $data['subscribedUsers'] = $subscribedUsers;
        $data['modelInstance'] = $publicConsultation;
        $data['markdown'] = 'public-consultation';
        SendSubscribedUserEmailJob::dispatch($data);
    }

    /**
     * Handle the PublicConsultation "deleted" event.
     *
     * @param  PublicConsultation  $publicConsultation
     * @return void
     */
    public function deleted(PublicConsultation $publicConsultation)
    {
        //
    }

    /**
     * Handle the PublicConsultation "restored" event.
     *
     * @param  PublicConsultation  $publicConsultation
     * @return void
     */
    public function restored(PublicConsultation $publicConsultation)
    {
        //
    }

    /**
     * Handle the PublicConsultation "force deleted" event.
     *
     * @param  PublicConsultation  $publicConsultation
     * @return void
     */
    public function forceDeleted(PublicConsultation $publicConsultation)
    {
        //
    }
}

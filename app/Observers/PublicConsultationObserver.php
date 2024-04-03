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
     * @param  PublicConsultation  $publicConsultation
     * @return void
     */
    public function created(PublicConsultation $publicConsultation)
    {
        if ($publicConsultation->active) {
            //TODO post on facebook
            $activeFB = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
                ->where('name', '=', Setting::FACEBOOK_IS_ACTIVE)
                ->get()->first();
            if($activeFB->value){
                $facebookApi = new Facebook();
                $facebookApi->postOnPage(array(
                    'message' => 'Публикувана е Обществена консултация: '.$publicConsultation->title,
                    'link' => route('public_consultation.view', $publicConsultation->id),
                    'published' => true
                ));
            }

            //TODO post on twitter

            //$this->sendEmails($publicConsultation, 'created');

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
            //TODO post on facebook
            $activeFB = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
                ->where('name', '=', Setting::FACEBOOK_IS_ACTIVE)
                ->get()->first();
            if($activeFB->value){
                $facebookApi = new Facebook();
                $facebookApi->postOnPage(array(
                    'message' => 'Публикувана е Обществена консултация: '.$publicConsultation->title,
                    'link' => route('public_consultation.view', $publicConsultation->id),
                    'published' => true
                ));
            }
            //TODO post on twitter

            //$this->sendEmails($publicConsultation, 'updated');

            Log::info('Send subscribe email on update');
        }
    }

    /**
     * Send emails to all administrators, moderators and subscribed users
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
            $moderator_roles = CustomRole::select('name')
                ->where('name', 'ILIKE', 'moderator-%')
                ->get()
                ->pluck('name')
                ->toArray();
            $moderators = User::whereActive(true)
                ->hasRole($moderator_roles)
                ->where('institution_id', $publicConsultation->importer_institution_id)
                ->get()
                ->unique('id');
        }
        $subscribedUsers = UserSubscribe::where('subscribable_type', PublicConsultation::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', UserSubscribe::SUBSCRIBED)
            ->get();

        if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
            return;
        }

        $data['event'] = $event;
        $data['administrators'] = $administrators;
        $data['moderators'] = $moderators;
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

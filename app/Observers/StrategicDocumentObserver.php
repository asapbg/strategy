<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\StrategicDocument;
use App\Models\User;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class StrategicDocumentObserver
{
    /**
     * Handle the PublicConsultation "created" event.
     *
     * @param  StrategicDocument  $strategicDocument
     * @return void
     */
    public function created(StrategicDocument  $strategicDocument)
    {
        if ($strategicDocument->active) {
            //$this->sendEmails($strategicDocument, 'created');

            Log::info('Send subscribe email on creation');
        }
    }

    /**
     * Handle the PublicConsultation "updated" event.
     *
     * @param  StrategicDocument  $strategicDocument
     * @return void
     */
    public function updated(StrategicDocument  $strategicDocument)
    {
        $old_active = $strategicDocument->getOriginal('active');

        if (!$old_active && $strategicDocument->active) {
            //$this->sendEmails($strategicDocument, 'updated');

            Log::info('Send subscribe email on update');
        }
    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param StrategicDocument  $strategicDocument
     * @param $event
     * @return void
     */
    private function sendEmails(StrategicDocument  $strategicDocument, $event): void
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
//            $moderators = User::whereActive(true)
//                ->hasRole($moderator_roles)
//                ->where('institution_id', $strategicDocument->importer_institution_id)
//                ->get()
//                ->unique('id');
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
        $data['modelInstance'] = $strategicDocument;
        $data['markdown'] = 'strategic-document';

        SendSubscribedUserEmailJob::dispatch($data);
    }

    /**
     * Handle the PublicConsultation "deleted" event.
     *
     * @param  StrategicDocument  $strategicDocument
     * @return void
     */
    public function deleted(StrategicDocument  $strategicDocument)
    {
        //
    }

    /**
     * Handle the PublicConsultation "restored" event.
     *
     * @param  StrategicDocument  $strategicDocument
     * @return void
     */
    public function restored(StrategicDocument  $strategicDocument)
    {
        //
    }

    /**
     * Handle the PublicConsultation "force deleted" event.
     *
     * @param  StrategicDocument  $strategicDocument
     * @return void
     */
    public function forceDeleted(StrategicDocument  $strategicDocument)
    {
        //
    }
}

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
            $this->sendEmails($strategicDocument, 'created');

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

        //Check for real changes
        $dirty = $strategicDocument->getDirty(); //return all changed fields
        //skip some fields in specific cases
        unset($dirty['updated_at']);
        if($old_active == (boolval($strategicDocument->active))) {
            unset($dirty['active']);
        }

        if(sizeof($dirty)){
            $this->sendEmails($strategicDocument, 'updated');
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

            $moderators = \DB::select('
                select users.id
                from users
                join model_has_role on model_has_role.model_id = users.id and model_has_role.model_type = \'App\Models\User\'
                join roles on roles.id = model_has_role.role_id and roles.deleted_at is null
                join institution on institution.id = users.institution_id and institution.deleted_at is null
                join institution_field_of_action on institution_field_of_action.institution_id = institution.id
                join field_of_actions on field_of_actions.id = institution_field_of_action.field_of_action_id and field_of_actions.deleted_at is null
                where
                    users.active = '.User::STATUS_ACTIVE.'
                    and users.user_type = '.User::USER_TYPE_INTERNAL.'
                    and users.deleted_at is null
                    and (
                        roles.name = \''.CustomRole::MODERATOR_STRATEGIC_DOCUMENTS.'\'
                        or (
                            roles.name = \''.CustomRole::MODERATOR_STRATEGIC_DOCUMENT.'\'
                            and field_of_actions.id = '.$strategicDocument->policy_area_id.'
                        )
                    )
                group by users.id
            ');
        }

        //get users by model ID
        $subscribedUsers = UserSubscribe::where('subscribable_type', StrategicDocument::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
            ->where('subscribable_id', '=', $strategicDocument->id)
            ->get();

        //get users by model filter
        $filterSubscribtions = UserSubscribe::where('subscribable_type', StrategicDocument::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
            ->whereNotNull('search_filters')
            ->get();

        if($filterSubscribtions->count()){
            foreach ($filterSubscribtions as $fSubscribe){
                $filterArray = json_decode($fSubscribe->search_filters, true);
                if($filterArray){
                    $modelIds = StrategicDocument::list($filterArray)->pluck('id')->toArray();
                    if(in_array($strategicDocument->id, $modelIds)){
                        $subscribedUsers->add($fSubscribe);
                    }
                }
            }
        }

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

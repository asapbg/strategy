<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\CustomRole;
use App\Models\Setting;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentTranslation;
use App\Models\User;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class StrategicDocumentTranslationObserver
{

    /**
     * Handle the StrategicDocumentTranslation "created" event.
     *
     * @param StrategicDocumentTranslation $strategicDocumentTranslation
     * @return void
     */
    public function created(StrategicDocumentTranslation $strategicDocumentTranslation)
    {
        $strategicDocument = $strategicDocumentTranslation->parent;
        if (!env('DISABLE_OBSERVERS', false) && $strategicDocumentTranslation->locale == config('app.default_lang')) {
            if ($strategicDocument->active) {
                if (!$strategicDocument->parent_document_id) {
                    if (Setting::allowPostingToFacebook()) {
                        $facebookApi = new Facebook();
                        $facebookApi->postToFacebook($strategicDocument);
                    }
                }

                //$this->sendEmails($strategicDocumentTranslation, 'created');

                Log::info('Send subscribe email on creation');
            }
        }
    }

    /**
     * Handle the StrategicDocumentTranslation "updated" event.
     *
     * @param StrategicDocumentTranslation $strategicDocumentTranslation
     * @return void
     */
    public function updated(StrategicDocumentTranslation $strategicDocumentTranslation)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            //Check for real changes
            $dirty = $strategicDocumentTranslation->getDirty(); //return all changed fields
            //skip some fields in specific cases
            unset($dirty['updated_at']);

            if (sizeof($dirty)) {
                $this->sendEmails($strategicDocumentTranslation, 'updated');
                Log::info('Send subscribe email on update');
            }
        }

    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param StrategicDocumentTranslation $strategicDocumentTranslation
     * @param $event
     * @return void
     */
    private function sendEmails(StrategicDocumentTranslation $strategicDocumentTranslation, $event): void
    {
        $strategicDocument = $strategicDocumentTranslation->parent;
        $administrators = null;
        $moderators = null;
        if ($event == "created") {
            $administrators = User::whereActive(true)
                ->hasRole(CustomRole::ADMIN_USER_ROLE)
                ->get();

            $moderators = \DB::select('
                select users.id
                from users
                join model_has_roles on model_has_roles.model_id = users.id and model_has_roles.model_type = \'App\Models\User\'
                join roles on roles.id = model_has_roles.role_id and roles.deleted_at is null
                join institution on institution.id = users.institution_id and institution.deleted_at is null
                join institution_field_of_action on institution_field_of_action.institution_id = institution.id
                join field_of_actions on field_of_actions.id = institution_field_of_action.field_of_action_id and field_of_actions.deleted_at is null
                where
                    users.active = true
                    and users.user_type = ' . User::USER_TYPE_INTERNAL . '
                    and users.deleted_at is null
                    and (
                        roles.name = \'' . CustomRole::MODERATOR_STRATEGIC_DOCUMENTS . '\'
                        or (
                            roles.name = \'' . CustomRole::MODERATOR_STRATEGIC_DOCUMENT . '\'
                            and field_of_actions.id = ' . $strategicDocumentTranslation->parent->policy_area_id . '
                        )
                    )
                group by users.id
            ');

            if (sizeof($moderators)) {
                $moderators = User::wherein('id', array_column($moderators, 'id'))->get();
            } else {
                $moderators = null;
            }

            //get users by model ID
            $subscribedUsers = UserSubscribe::where('subscribable_type', StrategicDocument::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->where('subscribable_id', '=', $strategicDocumentTranslation->parent->id)
                ->get();

            //get users by model filter
            $filterSubscribtions = UserSubscribe::where('subscribable_type', StrategicDocument::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->whereNull('subscribable_id')
                ->get();

            if ($filterSubscribtions->count()) {
                foreach ($filterSubscribtions as $fSubscribe) {
                    $filterArray = json_decode($fSubscribe->search_filters, true);
                    if ($filterArray) {
                        $modelIds = StrategicDocument::list($filterArray)->pluck('id')->toArray();
                        if (in_array($strategicDocument->id, $modelIds)) {
                            $subscribedUsers->add($fSubscribe);
                        }
                    }
                }
            }
        } else {
            //get users by model ID
            $subscribedUsers = UserSubscribe::where('subscribable_type', StrategicDocument::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->where('subscribable_id', '=', $strategicDocument->id)
                ->get();
        }

        if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
            return;
        }

        $data['event'] = $event;
        $data['administrators'] = $administrators;
        $data['moderators'] = $moderators;
        $data['subscribedUsers'] = $subscribedUsers;
        $data['modelInstance'] = $strategicDocumentTranslation->parent;
        $data['modelName'] = $strategicDocumentTranslation->parent->title;
        $data['markdown'] = 'strategic-document';

        SendSubscribedUserEmailJob::dispatch($data);
    }

    /**
     * Handle the StrategicDocumentTranslation "deleted" event.
     *
     * @param StrategicDocumentTranslation $strategicDocumentTranslation
     * @return void
     */
    public function deleted(StrategicDocumentTranslation $strategicDocumentTranslation)
    {
        //
    }

    /**
     * Handle the StrategicDocumentTranslation "restored" event.
     *
     * @param StrategicDocumentTranslation $strategicDocumentTranslation
     * @return void
     */
    public function restored(StrategicDocumentTranslation $strategicDocumentTranslation)
    {
        //
    }

    /**
     * Handle the StrategicDocumentTranslation "force deleted" event.
     *
     * @param StrategicDocumentTranslation $strategicDocumentTranslation
     * @return void
     */
    public function forceDeleted(StrategicDocumentTranslation $strategicDocumentTranslation)
    {
        //
    }
}

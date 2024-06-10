<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\CustomRole;
use App\Models\Setting;
use App\Models\StrategicDocument;
use App\Models\User;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class StrategicDocumentObserver
{

    /**
     * Handle the StrategicDocument "created" event.
     *
     * @param  StrategicDocument  $strategicDocument
     * @return void
     */
    public function created(StrategicDocument  $strategicDocument)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            if ($strategicDocument->active) {
                if (!$strategicDocument->parent_document_id) {
                    //post on facebook
                    $activeFB = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
                        ->where('name', '=', Setting::FACEBOOK_IS_ACTIVE)
                        ->get()->first();
                    if ($activeFB->value) {
                        $facebookApi = new Facebook();
                        $facebookApi->postOnPage(array(
                            'message' => 'На Портала за обществени консултации е публикуван нов стратегически документ. Запознайте се с документа тук.',
//                            'message' => 'Публикуван е нов Стратегически документ: ' . $strategicDocument->title,
                            'link' => route('strategy-document.view', $strategicDocument->id),
                            'published' => true
                        ));
                    }
                }

                $this->sendEmails($strategicDocument, 'created');

                Log::info('Send subscribe email on creation');
            }
        }
    }

    /**
     * Handle the StrategicDocument "updated" event.
     *
     * @param  StrategicDocument  $strategicDocument
     * @return void
     */
    public function updated(StrategicDocument  $strategicDocument)
    {
        if(!env('DISABLE_OBSERVERS', false)) {
            $old_active = $strategicDocument->getOriginal('active');

            //Check for real changes
            $dirty = $strategicDocument->getDirty(); //return all changed fields
            //skip some fields in specific cases
            unset($dirty['updated_at']);

            if (!$old_active && $strategicDocument->active && !$strategicDocument->parent_document_id) {
                //post on facebook
                $activeFB = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
                    ->where('name', '=', Setting::FACEBOOK_IS_ACTIVE)
                    ->get()->first();
                if ($activeFB->value) {
                    $facebookApi = new Facebook();
                    $facebookApi->postOnPage(array(
                        'message' => 'На Портала за обществени консултации е публикуван нов стратегически документ: '.$strategicDocument->title.'. Запознайте се с документа тук.',
//                        'message' => 'Публикуван е нов Стратегически документ: ' . $strategicDocument->title,
                        'link' => route('strategy-document.view', $strategicDocument->id),
                        'published' => true
                    ));
                }
            }

            if ($old_active == (boolval($strategicDocument->active))) {
                unset($dirty['active']);
            }

            if (sizeof($dirty)) {
                $this->sendEmails($strategicDocument, 'updated');
                Log::info('Send subscribe email on update');
            }
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
                join model_has_roles on model_has_roles.model_id = users.id and model_has_roles.model_type = \'App\Models\User\'
                join roles on roles.id = model_has_roles.role_id and roles.deleted_at is null
                join institution on institution.id = users.institution_id and institution.deleted_at is null
                join institution_field_of_action on institution_field_of_action.institution_id = institution.id
                join field_of_actions on field_of_actions.id = institution_field_of_action.field_of_action_id and field_of_actions.deleted_at is null
                where
                    users.active = true
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

            if(sizeof($moderators)) {
                $moderators = User::wherein('id', array_column($moderators, 'id'))->get();
            } else{
                $moderators = null;
            }

            $subscribedUsers = UserSubscribe::where('id', 0)->get();
            //get users by model filter
            $filterSubscribtions = UserSubscribe::where('subscribable_type', StrategicDocument::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->whereNull('subscribable_id')
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
        } else{
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
        $data['modelInstance'] = $strategicDocument;
        $data['modelName'] = $strategicDocument->title;
        $data['markdown'] = 'strategic-document';

        SendSubscribedUserEmailJob::dispatch($data);
    }

    /**
     * Handle the StrategicDocument "deleted" event.
     *
     * @param  StrategicDocument  $strategicDocument
     * @return void
     */
    public function deleted(StrategicDocument  $strategicDocument)
    {
        //
    }

    /**
     * Handle the StrategicDocument "restored" event.
     *
     * @param  StrategicDocument  $strategicDocument
     * @return void
     */
    public function restored(StrategicDocument  $strategicDocument)
    {
        //
    }

    /**
     * Handle the StrategicDocument "force deleted" event.
     *
     * @param  StrategicDocument  $strategicDocument
     * @return void
     */
    public function forceDeleted(StrategicDocument  $strategicDocument)
    {
        //
    }
}

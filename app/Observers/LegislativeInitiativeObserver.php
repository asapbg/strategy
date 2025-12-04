<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\LegislativeInitiative;
use App\Models\Setting;

class LegislativeInitiativeObserver
{

    /**
     * Handle the Legislative initiative "created" event.
     *
     * @param LegislativeInitiative $legislativeInitiative
     * @return void
     */
    public function created(LegislativeInitiative $legislativeInitiative)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            //TODO post on facebook
            if (Setting::allowPostingToFacebook()) {
                $facebookApi = new Facebook();
                $facebookApi->postOnPage(array(
                    'message' => 'На Портала за обществени консултации е направено предложение за промяна на ' . $legislativeInitiative->law?->name . ' и ако събере подкрепа от ' . (int)$legislativeInitiative->cap . ' регистрирани потребители, ще бъде изпратена автоматично на компетентната институция. Срокът за коментари и подкрепа е: ' . displayDate($legislativeInitiative->active_support) . '. Вижте повече на линка.',
                    //'message' => 'Публикувана е нова Законодателна инициатива: ' . $legislativeInitiative->facebookTitle,
                    'link' => route('legislative_initiatives.view', $legislativeInitiative),
                    'published' => true
                ));
            }

            $this->sendEmails($legislativeInitiative, 'created');
        }
    }

    /**
     * Handle the Legislative initiative "updated" event.
     *
     * @param LegislativeInitiative $legislativeInitiative
     * @return void
     */
    public function updated(LegislativeInitiative $legislativeInitiative)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $dirty = $legislativeInitiative->getDirty(); //return all changed fields
            unset($dirty['updated_at']);

            if (sizeof($dirty)) {
                $this->sendEmails($legislativeInitiative, 'updated');
            }
        }

    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param LegislativeInitiative $legislativeInitiative
     * @param $event
     * @return void
     */
    private function sendEmails(LegislativeInitiative $legislativeInitiative, $event): void
    {
        $data['event'] = $event;
        $data['modelInstance'] = $legislativeInitiative;
        $data['modelName'] = $legislativeInitiative->facebookTitle;
        $data['markdown'] = 'legislative-initiative';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

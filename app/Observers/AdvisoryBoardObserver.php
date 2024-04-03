<?php

namespace App\Observers;

use App\Library\Facebook;
use App\Models\AdvisoryBoard;
use App\Models\Setting;

class AdvisoryBoardObserver
{
    /**
     * Handle the AdvisoryBoard "created" event.
     *
     * @param  \App\Models\AdvisoryBoard  $advisoryBoard
     * @return void
     */
    public function created(AdvisoryBoard $advisoryBoard)
    {
        if($advisoryBoard->public) {
            //TODO post on facebook
            $activeFB = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
                ->where('name', '=', Setting::FACEBOOK_IS_ACTIVE)
                ->get()->first();
            if($activeFB->value){
                $facebookApi = new Facebook();
                $facebookApi->postOnPage(array(
                    'message' => 'Създаден е нов консултативен съвет: '.$advisoryBoard->name,
                    'link' => route('advisory-boards.view', $advisoryBoard),
                    'published' => true
                ));
            }
            //TODO post on twitter
        }
    }

    /**
     * Handle the AdvisoryBoard "updated" event.
     *
     * @param  \App\Models\AdvisoryBoard  $advisoryBoard
     * @return void
     */
    public function updated(AdvisoryBoard $advisoryBoard)
    {
        $old_active = $advisoryBoard->getOriginal('active');
        $old_public = $advisoryBoard->getOriginal('public');

        if($advisoryBoard->active && !$old_public && $advisoryBoard->public) {
            //TODO post on facebook
            $activeFB = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
                ->where('name', '=', Setting::FACEBOOK_IS_ACTIVE)
                ->get()->first();
            if($activeFB->value){
                $facebookApi = new Facebook();
                $facebookApi->postOnPage(array(
                    'message' => 'Създаден е нов консултативен съвет: '.$advisoryBoard->name,
                    'link' => route('advisory-boards.view', $advisoryBoard),
                    'published' => true
                ));
            }
            //TODO post on twitter
        }
    }

    /**
     * Handle the AdvisoryBoard "deleted" event.
     *
     * @param  \App\Models\AdvisoryBoard  $advisoryBoard
     * @return void
     */
    public function deleted(AdvisoryBoard $advisoryBoard)
    {
        //
    }

    /**
     * Handle the AdvisoryBoard "restored" event.
     *
     * @param  \App\Models\AdvisoryBoard  $advisoryBoard
     * @return void
     */
    public function restored(AdvisoryBoard $advisoryBoard)
    {
        //
    }

    /**
     * Handle the AdvisoryBoard "force deleted" event.
     *
     * @param  \App\Models\AdvisoryBoard  $advisoryBoard
     * @return void
     */
    public function forceDeleted(AdvisoryBoard $advisoryBoard)
    {
        //
    }
}

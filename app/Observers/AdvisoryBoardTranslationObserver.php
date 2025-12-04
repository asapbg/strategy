<?php

namespace App\Observers;

use App\Library\Facebook;
use App\Models\AdvisoryBoardTranslation;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class AdvisoryBoardTranslationObserver
{
    /**
     * Handle the AdvisoryBoard "created" event.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     * @return void
     */
    public function created(AdvisoryBoardTranslation $advisoryBoardTranslation)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $advisoryBoard = $advisoryBoardTranslation->parent;
            if ($advisoryBoard->public) {
//                if (Setting::allowPostingToFacebook()) {
//                    $facebookApi = new Facebook();
//                    $facebookApi->postToFacebook($advisoryBoard);
//                }
//
//                //$this->sendEmails($advisoryBoard, 'created');
//                Log::info('Send subscribe email on creation');
            }
        }
    }
}

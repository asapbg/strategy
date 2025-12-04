<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\AdvisoryBoard;
use App\Models\Setting;

class AdvisoryBoardObserver
{
    /**
     * Handle the AdvisoryBoard "created" event.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     * @return void
     */
    public function created(AdvisoryBoard $advisoryBoard)
    {
        if (!env('DISABLE_OBSERVERS', false)) {

            if ($advisoryBoard->public) {
                if (Setting::allowPostingToFacebook()) {
                    $facebookApi = new Facebook();
                    $facebookApi->postToFacebook($advisoryBoard);
                }

                if ($advisoryBoard->public) {
                    $this->sendEmails($advisoryBoard,'created');
                }

            }
        }
    }

    /**
     * Handle the AdvisoryBoard "updated" event.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     * @return void
     */
    public function updated(AdvisoryBoard $advisoryBoard)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            if ($advisoryBoard->public) {
                if (Setting::allowPostingToFacebook()) {
//                    $facebookApi = new Facebook();
//                    $facebookApi->postToFacebook($advisoryBoard);
                }
            }
        }
    }

    /**
     * Send emails
     *
     * @param AdvisoryBoard $advisoryBoard
     * @param $event
     * @return void
     */
    private function sendEmails(AdvisoryBoard $advisoryBoard, $event): void
    {
        $data['event'] = $event;
        $data['modelInstance'] = $advisoryBoard;
        $data['modelName'] = $advisoryBoard->name;
        $data['markdown'] = 'adv_boards';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\AdvisoryBoardMeeting;
use App\Models\Setting;
use Carbon\Carbon;

class AdvisoryBoardMeetingObserver
{
    /**
     * Handle the AdvisoryBoardMeeting "created" event.
     *
     * @param \App\Models\AdvisoryBoardMeeting $advisoryBoardMeeting
     * @return void
     */
    public function created(AdvisoryBoardMeeting $advisoryBoardMeeting)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $advBoard = $advisoryBoardMeeting->advBoard;
            if ($advBoard && $advBoard->public && $advisoryBoardMeeting->next_meeting >= Carbon::now()->format('Y-m-d H:i:s')) {
                if (Setting::allowPostingToFacebook()) {
                    $facebookApi = new Facebook();
                    $facebookApi->postToFacebook($advisoryBoardMeeting);
                }
                // Notifications are send from the AdvisoryBoardMeetingsController
                //$this->sendEmails($advisoryBoardMeeting, 'created');
            }
        }
    }

    /**
     * Send emails
     *
     * @param AdvisoryBoardMeeting $advisoryBoardMeeting
     * @param $event
     * @return void
     */
    private function sendEmails(AdvisoryBoardMeeting $advisoryBoardMeeting, $event): void
    {
        $data['event'] = $event;
        $data['modelInstance'] = $advisoryBoardMeeting;
        $data['modelName'] = $advisoryBoardMeeting->advBoard?->name;
        $data['markdown'] = 'adv_boards_meeting';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeComment;

class LegislativeInitiativeCommentObserver
{
    /**
     * Handle the LegislativeInitiativeComment "created" event.
     *
     * @param \App\Models\LegislativeInitiativeComment $legislativeInitiativeComment
     * @return void
     */
    public function created(LegislativeInitiativeComment $legislativeInitiativeComment)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $this->sendEmails($legislativeInitiativeComment, 'comment');
        }

    }

    /**
     * Send emails to all subscribers and author
     *
     * @param LegislativeInitiativeComment $legislativeInitiativeComment
     * @param $event
     * @return void
     */
    private function sendEmails(LegislativeInitiativeComment $legislativeInitiativeComment, $event): void
    {
        $legislativeInitiative = LegislativeInitiative::find($legislativeInitiativeComment->legislative_initiative_id);

        $specialUser = null;
        //Send to author if comment is not his
        if ($legislativeInitiative->user && $legislativeInitiative->author_id != $legislativeInitiativeComment->user_id) {
            $specialUser = $legislativeInitiative->user;
        }

        $data['event'] = $event;
        $data['specialUser'] = $specialUser;
        $data['modelInstance'] = $legislativeInitiative;
        $data['secondModelInstance'] = $legislativeInitiativeComment;
        $data['modelName'] = $legislativeInitiative->facebookTitle;
        $data['markdown'] = 'legislative-initiative';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

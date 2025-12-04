<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Comments;

class CommentsObserver
{
    /**
     * Handle the Comments "created" event.
     *
     * @param Comments $comment
     * @return void
     */
    public function created(Comments $comment)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            if ($comment->object_code == Comments::PC_OBJ_CODE) {
                $this->sendEmails($comment);
            }
        }
    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param Comments $comment
     * @return void
     */
    private function sendEmails(Comments $comment): void
    {
        $publicConsultation = $comment->commented;

        if ($comment->object_code == Comments::PC_OBJ_CODE) {

            $data['event'] = 'new-comment';
            $data['modelInstance'] = $publicConsultation;
            $data['secondModelInstance'] = $comment;
            $data['modelName'] = $publicConsultation->translation?->title;
            $data['markdown'] = 'public-consultation';

            SendSubscribedUserEmailJob::dispatch($data);

        }

    }
}

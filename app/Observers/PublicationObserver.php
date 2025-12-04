<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Publication;

class PublicationObserver
{
    /**
     * Handle the Publication "created" event.
     *
     * @param  \App\Models\Publication  $publication
     * @return void
     */
    public function created(Publication $publication)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            if ($publication->isPublishedNewsOrLibrary()) {
                $this->sendEmails($publication, 'created');
            }
        }
    }

    /**
     * Send emails
     *
     * @param Publication $publication
     * @param $event
     * @return void
     */
    public function sendEmails(Publication $publication, $event): void
    {
        $data['event'] = $event;
        $data['modelInstance'] = $publication;
        $data['modelName'] = $publication->title;
        $data['markdown'] = 'publication';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

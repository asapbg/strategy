<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\StrategicDocumentTranslation;
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
            $dirty = $strategicDocumentTranslation->getDirty();
            unset($dirty['updated_at']);

            if (sizeof($dirty)) {
                // Notifications will be handled in StrategicDocumentsController
                //$this->sendEmails($strategicDocumentTranslation, 'updated');
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

        $data['event'] = $event;
        $data['modelInstance'] = $strategicDocument;
        $data['modelName'] = $strategicDocumentTranslation->title;
        $data['markdown'] = 'strategic-document';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

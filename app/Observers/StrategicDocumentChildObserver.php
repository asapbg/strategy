<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\CustomRole;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentChildren;
use App\Models\User;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class StrategicDocumentChildObserver
{

    /**
     * Handle the StrategicDocumentChildren "created" event.
     *
     * @param StrategicDocumentChildren $strategicDocumentChild
     * @return void
     */
    public function created(StrategicDocumentChildren $strategicDocumentChild)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $this->sendEmails($strategicDocumentChild, 'created');
        }
    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param StrategicDocumentChildren $strategicDocumentChild
     * @param $event
     * @return void
     */
    public function sendEmails(StrategicDocumentChildren $strategicDocumentChild, $event): void
    {
        $data['event'] = $event;
        $data['modelInstance'] = $strategicDocumentChild->strategicDocument;
        $data['modelName'] = $strategicDocumentChild->strategicDocument->title;
        $data['secondModelInstance'] = $strategicDocumentChild;
        $data['markdown'] = 'strategic-document';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

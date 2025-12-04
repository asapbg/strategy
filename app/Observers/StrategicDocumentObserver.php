<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\Setting;
use App\Models\StrategicDocument;

class StrategicDocumentObserver
{

    /**
     * Handle the StrategicDocument "created" event.
     *
     * @param StrategicDocument $strategicDocument
     * @return void
     */
    public function created(StrategicDocument $strategicDocument)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            if ($strategicDocument->active) {
                if (!$strategicDocument->parent_document_id) {
                    if (Setting::allowPostingToFacebook()) {
                        $facebookApi = new Facebook();
                        $facebookApi->postOnPage(array(
                            'message' => 'На Портала за обществени консултации е публикуван нов стратегически документ. Запознайте се с документа тук.',
                            //'message' => 'Публикуван е нов Стратегически документ: ' . $strategicDocument->title,
                            'link' => route('strategy-document.view', $strategicDocument->id),
                            'published' => true
                        ));
                    }
                }

                $this->sendEmails($strategicDocument, 'created');
            }
        }
    }

    /**
     * Handle the StrategicDocument "updated" event.
     *
     * @param StrategicDocument $strategicDocument
     * @return void
     */
    public function updated(StrategicDocument $strategicDocument)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $old_active = $strategicDocument->getOriginal('active');

            if (
                !$old_active
                && $strategicDocument->active
                && !$strategicDocument->parent_document_id
                && Setting::allowPostingToFacebook()
            ) {
                $facebookApi = new Facebook();
                $facebookApi->postToFacebook($strategicDocument);
            }
        }

    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param StrategicDocument $strategicDocument
     * @param $event
     * @return void
     */
    public function sendEmails(StrategicDocument $strategicDocument, $event): void
    {
        $data['event'] = $event;
        $data['modelInstance'] = $strategicDocument;
        $data['modelName'] = $strategicDocument->title;
        $data['markdown'] = 'strategic-document';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

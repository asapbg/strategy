<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\LegalActType;
use App\Models\Pris;
use Illuminate\Support\Facades\Log;

class PrisObserver
{
    /**
     * Handle the Pris "created" event.
     *
     * @param \App\Models\Pris $pris
     * @return void
     */
    public function created(Pris $pris)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            if (!empty($pris->published_at)) {
                $this->sendEmails($pris, 'created');
                if ($pris->public_consultation_id) {
                    $this->sendEmails($pris, 'created_with_pc');
                }
            }
        }
    }

    /**
     * Send emails
     *
     * @param Pris $pris
     * @param $event
     * @return void
     */
    public function sendEmails(Pris $pris, $event): void
    {
        if ($pris->legal_act_type_id == LegalActType::TYPE_ORDER) {
            Log::error('Observer pris email sending stopped because its an order.');
            return;
        }

        if ($event == 'created' || $event == 'updated') {

            $data['event'] = $event;
            $data['modelInstance'] = $pris;
            $data['modelName'] = $pris->mcDisplayName;
            $data['markdown'] = 'pris';

        }
        if ($event == 'created_with_pc' || $event == 'updated_with_pc') {

            if ($pris->public_consultation_id) {
                $data['event'] = $event;
                $data['modelInstance'] = $pris->consultation;
                $data['modelName'] = $pris->consultation->translation?->title;
                $data['markdown'] = 'public-consultation';
            }
        }

        if (isset($data)) {
            SendSubscribedUserEmailJob::dispatch($data);
        }
    }
}

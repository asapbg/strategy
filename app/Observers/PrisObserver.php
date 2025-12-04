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
                Log::channel('info')->info('Send pris subscription email on creation');
                if ($pris->public_consultation_id) {
                    $this->sendEmails($pris, 'created_with_pc');
                    Log::channel('info')->info('Send pc subscription email on creation');
                }
            }
        }
    }

    /**
     * Handle the Pris "updated" event.
     *
     * @param \App\Models\Pris $pris
     * @return void
     */
    public function updated(Pris $pris)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $old_public_consultation_id = (int)$pris->getOriginal('public_consultation_id');
            if (!$old_public_consultation_id && $pris->public_consultation_id) {
                $this->sendEmails($pris, 'updated_with_pc');
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

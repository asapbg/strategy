<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Consultations\OperationalProgram;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class OperationalProgramObserver
{
    /**
     * Handle the OperationalProgram "created" event.
     *
     * @param \App\Models\Consultations\OperationalProgram $operationalProgram
     * @return void
     */
    public function created(OperationalProgram $operationalProgram)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            if ($operationalProgram->public) {
                $this->sendEmails($operationalProgram, 'created');
            }
        }
    }

    /**
     * Handle the OperationalProgram "updated" event.
     *
     * @param \App\Models\Consultations\OperationalProgram $operationalProgram
     * @return void
     */
    public function updated(OperationalProgram $operationalProgram)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $old_public = (int)$operationalProgram->getOriginal('public');
            $dirty = $operationalProgram->getDirty();
            unset($dirty['updated_at']);

            if (sizeof($dirty) && $operationalProgram->public) {
                $this->sendEmails($operationalProgram, $old_public ? "updated" : "created");
                Log::info('Send subscribe email on update');
            }
        }
    }

    /**
     * Handle the OperationalProgram "deleted" event.
     *
     * @param \App\Models\Consultations\OperationalProgram $operationalProgram
     * @return void
     */
    public function deleted(OperationalProgram $operationalProgram)
    {
        //
    }

    /**
     * Handle the OperationalProgram "restored" event.
     *
     * @param \App\Models\Consultations\OperationalProgram $operationalProgram
     * @return void
     */
    public function restored(OperationalProgram $operationalProgram)
    {
        //
    }

    /**
     * Handle the OperationalProgram "force deleted" event.
     *
     * @param \App\Models\Consultations\OperationalProgram $operationalProgram
     * @return void
     */
    public function forceDeleted(OperationalProgram $operationalProgram)
    {
        //
    }

    /**
     * Send emails
     *
     * @param OperationalProgram $operationalProgram
     * @param $event
     * @return void
     */
    private function sendEmails(OperationalProgram $operationalProgram, $event): void
    {
        $data['event'] = $event;
        $data['modelInstance'] = $operationalProgram;
        $data['modelName'] = $operationalProgram->name;
        $data['markdown'] = 'op';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

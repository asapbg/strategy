<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Consultations\LegislativeProgram;

class LegislativeProgramObserver
{
    /**
     * Handle the LegislativeProgram "created" event.
     *
     * @param \App\Models\Consultations\LegislativeProgram $legislativeProgram
     * @return void
     */
    public function created(LegislativeProgram $legislativeProgram)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            if ($legislativeProgram->public) {
                $this->sendEmails($legislativeProgram, 'created');
            }
        }
    }

    /**
     * Handle the LegislativeProgram "updated" event.
     *
     * @param \App\Models\Consultations\LegislativeProgram $legislativeProgram
     * @return void
     */
    public function updated(LegislativeProgram $legislativeProgram)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $old_public = (int)$legislativeProgram->getOriginal('public');
            $dirty = $legislativeProgram->getDirty();
            unset($dirty['updated_at']);

            if (sizeof($dirty) && $legislativeProgram->public) {
                $this->sendEmails($legislativeProgram, $old_public ? "updated" : "created");
            }
        }
    }

    /**
     * Send emails
     *
     * @param LegislativeProgram $legislativeProgram
     * @param $event
     * @return void
     */
    private function sendEmails(LegislativeProgram $legislativeProgram, $event): void
    {
        $data['event'] = $event;
        $data['modelInstance'] = $legislativeProgram;
        $data['modelName'] = $legislativeProgram->name;
        $data['markdown'] = 'lp';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

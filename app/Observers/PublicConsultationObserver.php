<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\Consultations\PublicConsultation;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class PublicConsultationObserver
{
    /**
     * Handle the PublicConsultation "created" event.
     *
     * @param PublicConsultation $publicConsultation
     * @return void
     */
    public function created(PublicConsultation $publicConsultation)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            if ($publicConsultation->active) {
                //post on facebook
                if (Setting::allowPostingToFacebook()) {
                    $facebookApi = new Facebook();
                    $facebookApi->postToFacebook($publicConsultation);
                }
                $this->sendEmails($publicConsultation, 'created');
            }
        }
    }

    /**
     * Handle the PublicConsultation "updated" event.
     *
     * @param PublicConsultation $publicConsultation
     * @return void
     */
    public function updated(PublicConsultation $publicConsultation)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $old_active = (int)$publicConsultation->getOriginal('active');

            if (!$old_active && $publicConsultation->active && Setting::allowPostingToFacebook()) {
                $facebookApi = new Facebook();
                $facebookApi->postToFacebook($publicConsultation);
            }
        }
    }

    /**
     * Send emails
     *
     * @param PublicConsultation $publicConsultation
     * @param $event
     * @return void
     */
    public function sendEmails(PublicConsultation $publicConsultation, $event): void
    {
        $data['event'] = $event;
        $data['modelInstance'] = $publicConsultation;
        $data['modelName'] = $publicConsultation->title;
        $data['markdown'] = 'public-consultation';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

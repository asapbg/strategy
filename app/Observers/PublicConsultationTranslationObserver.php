<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\Consultations\PublicConsultationTranslation;
use App\Models\Setting;

class PublicConsultationTranslationObserver
{
    /**
     * Handle the PublicConsultationTranslation "created" event.
     *
     * @param \App\Models\Consultations\PublicConsultationTranslation $publicConsultationTranslation
     * @return void
     */
    public function created(PublicConsultationTranslation $publicConsultationTranslation)
    {

    }

}

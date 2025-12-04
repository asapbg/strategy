<?php

namespace App\Observers;

use App\Models\PrisTranslation;

class PrisTranslationObserver
{
    /**
     * Handle the PrisTranslation "created" event.
     *
     * @param \App\Models\PrisTranslation $prisTranslation
     * @return void
     */
    public function created(PrisTranslation $prisTranslation)
    {

    }

    /**
     * Handle the PrisTranslation "updated" event.
     *
     * @param \App\Models\PrisTranslation $prisTranslation
     * @return void
     */
    public function updated(PrisTranslation $prisTranslation)
    {
        // The email is being handled in PrisObserver

//        if(!env('DISABLE_OBSERVERS', false)) {
//            $old_published_at = $prisTranslation->parent->getOriginal('published_at');
//            $dirty = $prisTranslation->getDirty();
//            unset($dirty['updated_at']);
//
//            if (sizeof($dirty)) {
//                $event = !$old_published_at && !empty($prisTranslation->parent->published_at) ? 'created' : 'updated';
//                $this->sendEmails($prisTranslation, $event);
//            }
//        }
    }

    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param PrisTranslation $prisTranslation
     * @param $event
     * @return void
     */
    private function sendEmails(PrisTranslation $prisTranslation, $event): void
    {

    }
}

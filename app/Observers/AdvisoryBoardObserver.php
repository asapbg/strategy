<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\AdvisoryBoard;
use App\Models\Setting;
use App\Models\UserSubscribe;
use Illuminate\Support\Facades\Log;

class AdvisoryBoardObserver
{
    /**
     * Handle the AdvisoryBoard "created" event.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     * @return void
     */
    public function created(AdvisoryBoard $advisoryBoard)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            if ($advisoryBoard->public) {
                if (Setting::allowPostingToFacebook()) {
                    $facebookApi = new Facebook();
                    $facebookApi->postToFacebook($advisoryBoard);
                }

                //$this->sendEmails($advisoryBoard, 'created');
                Log::info('Send subscribe email on creation');
            }
        }
    }

    /**
     * Handle the AdvisoryBoard "updated" event.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     * @return void
     */
    public function updated(AdvisoryBoard $advisoryBoard)
    {
        if (!env('DISABLE_OBSERVERS', false)) {
            $old_active = $advisoryBoard->getOriginal('active');
            $old_public = $advisoryBoard->getOriginal('public');

            $dirty = $advisoryBoard->getDirty();
            unset($dirty['updated_at']);

            if (!$old_public && $advisoryBoard->public) {
                if (Setting::allowPostingToFacebook()) {
                    $facebookApi = new Facebook();
                    $facebookApi->postToFacebook($advisoryBoard);
                }

                if (boolval($old_active) == boolval($advisoryBoard->active)) {
                    unset($dirty['active']);
                }

                if (sizeof($dirty)) {
                    //$this->sendEmails($advisoryBoard, 'updated');
                    Log::info('Send subscribe email on update');
                }

            }
        }
    }

    /**
     * Handle the AdvisoryBoard "deleted" event.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     * @return void
     */
    public function deleted(AdvisoryBoard $advisoryBoard)
    {
        //
    }

    /**
     * Handle the AdvisoryBoard "restored" event.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     * @return void
     */
    public function restored(AdvisoryBoard $advisoryBoard)
    {
        //
    }

    /**
     * Handle the AdvisoryBoard "force deleted" event.
     *
     * @param \App\Models\AdvisoryBoard $advisoryBoard
     * @return void
     */
    public function forceDeleted(AdvisoryBoard $advisoryBoard)
    {
        //
    }

    /**
     * Send emails
     *
     * @param AdvisoryBoard $advisoryBoard
     * @param $event
     * @return void
     */
    private function sendEmails(AdvisoryBoard $advisoryBoard, $event): void
    {
        $administrators = null;
        $moderators = null;


        if ($event == 'updated') {
            //get users by model ID
            $subscribedUsers = UserSubscribe::where('subscribable_type', AdvisoryBoard::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->where('subscribable_id', '=', $advisoryBoard->id)
                ->get();
        } else {
            $subscribedUsers = UserSubscribe::where('id', 0)->get();
            //get users by model filter
            $filterSubscribtions = UserSubscribe::where('subscribable_type', AdvisoryBoard::class)
                ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                ->whereNull('subscribable_id')
                ->get();

            if ($filterSubscribtions->count()) {
                foreach ($filterSubscribtions as $fSubscribe) {
                    $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                    $modelIds = AdvisoryBoard::list($filterArray)->pluck('id')->toArray();
                    if (in_array($advisoryBoard->id, $modelIds)) {
                        $subscribedUsers->add($fSubscribe);
                    }
                }
            }
        }

        if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
            return;
        }
        $data['event'] = $event;
        $data['administrators'] = $administrators;
        $data['moderators'] = $moderators;
        $data['subscribedUsers'] = $subscribedUsers;
        $data['modelInstance'] = $advisoryBoard;
        $data['modelName'] = $advisoryBoard->name;
        $data['markdown'] = 'adv_boards';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

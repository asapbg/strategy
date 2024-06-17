<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Models\Setting;
use App\Models\UserSubscribe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdvisoryBoardMeetingObserver
{
    /**
     * Handle the AdvisoryBoardMeeting "created" event.
     *
     * @param  \App\Models\AdvisoryBoardMeeting  $advisoryBoardMeeting
     * @return void
     */
    public function created(AdvisoryBoardMeeting $advisoryBoardMeeting)
    {
        $advBoard = $advisoryBoardMeeting->advBoard;
        if($advBoard && $advBoard->public && $advisoryBoardMeeting->next_meeting >= Carbon::now()->format('Y-m-d H:i:s')) {
            if(!env('DISABLE_OBSERVERS', false)){

                //post on facebook
                $activeFB = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
                    ->where('name', '=', Setting::FACEBOOK_IS_ACTIVE)
                    ->get()->first();
                if($activeFB->value){
                    $facebookApi = new Facebook();
                    $facebookApi->postOnPage(array(
                        'message' =>'Предстоящо заседание на '.$advBoard->name.' на '.$advisoryBoardMeeting->next_meeting.'. За повече информация тук.',
                        'link' => route('advisory-boards.view', $advBoard),
                        'published' => true
                    ));
                }

                $this->sendEmails($advisoryBoardMeeting, 'created');
                Log::info('Send subscribe email on creation');
            }
        }
    }

    /**
     * Handle the AdvisoryBoardMeeting "updated" event.
     *
     * @param  \App\Models\AdvisoryBoardMeeting  $advisoryBoardMeeting
     * @return void
     */
    public function updated(AdvisoryBoardMeeting $advisoryBoardMeeting)
    {
        //
    }

    /**
     * Handle the AdvisoryBoardMeeting "deleted" event.
     *
     * @param  \App\Models\AdvisoryBoardMeeting  $advisoryBoardMeeting
     * @return void
     */
    public function deleted(AdvisoryBoardMeeting $advisoryBoardMeeting)
    {
        //
    }

    /**
     * Handle the AdvisoryBoardMeeting "restored" event.
     *
     * @param  \App\Models\AdvisoryBoardMeeting  $advisoryBoardMeeting
     * @return void
     */
    public function restored(AdvisoryBoardMeeting $advisoryBoardMeeting)
    {
        //
    }

    /**
     * Handle the AdvisoryBoardMeeting "force deleted" event.
     *
     * @param  \App\Models\AdvisoryBoardMeeting  $advisoryBoardMeeting
     * @return void
     */
    public function forceDeleted(AdvisoryBoardMeeting $advisoryBoardMeeting)
    {
        //
    }

    /**
     * Send emails
     *
     * @param AdvisoryBoardMeeting $advisoryBoardMeeting
     * @param $event
     * @return void
     */
    private function sendEmails(AdvisoryBoardMeeting $advisoryBoardMeeting, $event): void
    {
        $advisoryBoard = $advisoryBoardMeeting->advBoard;

        $administrators = null;
        $moderators = null;
        //get users by model ID
        $subscribedUsers = UserSubscribe::where('subscribable_type', AdvisoryBoard::class)
            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
            ->where('subscribable_id', '=', $advisoryBoard->id)
            ->get();

//        //get users by model filter
//        $filterSubscribtions = UserSubscribe::where('subscribable_type', AdvisoryBoard::class)
//            ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
//            ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
//            ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
//            ->whereNull('subscribable_id')
//            ->get();
//
//        if($filterSubscribtions->count()){
//            foreach ($filterSubscribtions as $fSubscribe){
//                $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
//                $modelIds = AdvisoryBoard::list($filterArray)->pluck('id')->toArray();
//                if(in_array($advisoryBoard->id, $modelIds)){
//                    $subscribedUsers->add($fSubscribe);
//                }
//            }
//        }
        if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
            return;
        }
        $data['event'] = $event;
        $data['administrators'] = $administrators;
        $data['moderators'] = $moderators;
        $data['subscribedUsers'] = $subscribedUsers;
        $data['modelInstance'] = $advisoryBoardMeeting;
        $data['modelName'] = $advisoryBoardMeeting->advBoard?->name;
        $data['markdown'] = 'adv_boards_meeting';

        SendSubscribedUserEmailJob::dispatch($data);
    }
}

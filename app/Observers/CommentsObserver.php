<?php

namespace App\Observers;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\User;
use App\Models\UserSubscribe;

class CommentsObserver
{
    /**
     * Handle the Comments "created" event.
     *
     * @param Comments $comment
     * @return void
     */
    public function created(Comments $comment)
    {
        if(!env('DISABLE_OBSERVERS', false)){
            if ($comment->object_code == Comments::PC_OBJ_CODE) {
                $this->sendEmails($comment);
            }
        }
    }

    /**
     * Handle the Comments "updated" event.
     *
     * @param Comments $comment
     * @return void
     */
    public function updated(Comments $comment)
    {
        //
    }


    /**
     * Send emails to all administrators, moderators and subscribed users
     *
     * @param Comments $comment
     * @return void
     */
    private function sendEmails(Comments $comment): void
    {
        $administrators = null;
        $moderators = null;
        $subscribedUsers= null;
        $item = $comment->commented;

        $data['event'] = 'new-comment';
        $data['modelInstance'] = $item;

        if ($comment->object_code == Comments::PC_OBJ_CODE) {
            $moderators = User::whereActive(true)
                ->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE, CustomRole::MODERATOR_PUBLIC_CONSULTATION])
                ->where('id', '=', $item->user_id)
//                ->where('institution_id', $publicConsultation->importer_institution_id)
                ->get()
                ->unique('id');

            //get users by model ID
            $commentedUsers = $item->comments->unique('user_id')->pluck('user_id')->toArray();
            if(sizeof($commentedUsers)){
                //get users by model ID
                $subscribedUsers = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                    ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                    ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                    ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                    ->where('subscribable_id', '=', $item->id)
                    ->whereIn('user_id', $commentedUsers)
                    ->get();

                //get users by model filter
                $filterSubscribtions = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                    ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                    ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                    ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                    ->whereNull('subscribable_id')
                    ->whereIn('user_id', $commentedUsers)
                    ->get();

                if($filterSubscribtions->count()){
                    foreach ($filterSubscribtions as $fSubscribe){
                        $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                        $modelIds = PublicConsultation::list($filterArray, 'title', 'desc', 0)->pluck('id')->toArray();
                        if(in_array($item->id, $modelIds)){
                            $subscribedUsers->add($fSubscribe);
                        }
                    }
                }
            }
            if (!$administrators && !$moderators && $subscribedUsers->count() == 0) {
                return;
            }
            $data['administrators'] = $administrators;
            $data['moderators'] = $moderators;
            $data['subscribedUsers'] = $subscribedUsers;
            $data['markdown'] = 'public-consultation';

            SendSubscribedUserEmailJob::dispatch($data);

        }

    }
}

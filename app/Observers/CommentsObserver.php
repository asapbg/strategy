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
            $commentedUsersIds = $item->comments->unique('user_id')->pluck('user_id')->toArray();
            if(sizeof($commentedUsersIds)) {
                $commentedUsers = User::whereIn('id', $commentedUsersIds)->get();
            } else{
                $commentedUsers = User::where('id', 0)->get();
            }
//            if(sizeof($commentedUsers)){
                //get users by model ID
                $subscribedUsers = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                    ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                    ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                    ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                    ->where('subscribable_id', '=', $item->id)
//                    ->whereIn('user_id', $commentedUsersIds)
                    ->get();

//                //get users by model filter
//                $filterSubscribtions = UserSubscribe::where('subscribable_type', PublicConsultation::class)
//                    ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
//                    ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
//                    ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
//                    ->whereNull('subscribable_id')
//                    ->whereIn('user_id', $commentedUsers)
//                    ->get();
//
                if($subscribedUsers->count()){
                    foreach ($subscribedUsers as $fSubscribe){
                        if(!in_array($fSubscribe->id, $commentedUsersIds)){
                            $commentedUsers->add($fSubscribe);
                        }
                    }
                }
//            }
            if (!$administrators && !$moderators && $commentedUsers->count() == 0) {
                return;
            }
            $data['administrators'] = $administrators;
            $data['moderators'] = $moderators;
            $data['subscribedUsers'] = $commentedUsers;
            $data['markdown'] = 'public-consultation';

            SendSubscribedUserEmailJob::dispatch($data);

        }

    }
}

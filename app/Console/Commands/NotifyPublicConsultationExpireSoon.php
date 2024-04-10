<?php

namespace App\Console\Commands;

use App\Jobs\SendSubscribedUserEmailJob;
use App\Models\Consultations\PublicConsultation;
use App\Models\UserSubscribe;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyPublicConsultationExpireSoon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:pc_expire_soon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to subscribers X days before consultation expiration deadline';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->addDays(3)->format('Y-m-d');
        $pcs = PublicConsultation::ActivePublic()->where('open_to', '=', $today)->get();
        if($pcs->count()){
            foreach ($pcs as $pc){
                //Send PC Send notification
                $data['event'] = 'expire';
                $data['administrators'] = null;
                $data['moderators'] = null;
                $data['modelInstance'] = $pc;
                $data['markdown'] = 'public-consultation-expire-soon';

                //get users by model ID
                $subscribedUsers = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                    ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                    ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                    ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                    ->where('subscribable_id', '=', $pc->id)
                    ->get();

                //get users by model filter
                $filterSubscribtions = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                    ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                    ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                    ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                    ->whereNull('subscribable_id')
                    ->get();

                if($filterSubscribtions->count()){
                    foreach ($filterSubscribtions as $fSubscribe){
                        $filterArray = is_null($fSubscribe->search_filters) ? [] : json_decode($fSubscribe->search_filters, true);
                        $modelIds = PublicConsultation::list($filterArray, 'title', 'desc', 0)->pluck('id')->toArray();
                        if(in_array($pc->id, $modelIds)){
                            $subscribedUsers->add($fSubscribe);
                        }
                    }
                }
                $data['subscribedUsers'] = $subscribedUsers;
                if ($data['administrators'] || $data['moderators'] || $data['subscribedUsers']->count()) {
                    SendSubscribedUserEmailJob::dispatch($data);
                }
            }
        }
    }
}

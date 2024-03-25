<?php

namespace App\Console\Commands;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Http\Controllers\SsevController;
use App\Models\LegislativeInitiative;
use App\Models\User;
use App\Notifications\LegislativeInitiativeClosed;
use App\Notifications\SendLegislativeInitiative;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseLegislativeInitiative extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'close:legislative_initiative';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close legislative initiatives when support period is end';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $items = LegislativeInitiative::Expired()->get();
        if(sizeof($items)) {
            foreach ($items as $item){
                \DB::beginTransaction();
                try {
                    $item->end_support_at = $item->active_support;
                    $item->status = LegislativeInitiativeStatusesEnum::STATUS_CLOSED->value;
                    $item->save();
                    //Send notification to author and all voted for unsuccessful closed initiative
                    $likesUserIds = $item->likes->pluck('user_id')->toArray();
                    if(sizeof($likesUserIds)){
                        $users = User::whereIn('id', $likesUserIds)->get();
                        if($users->count()){
                            foreach ($users as $n){
                                $n->notify(new LegislativeInitiativeClosed($item, 'deleted'));
                            }
                        }
                    }
                    if($item->user){
                        $item->user->notify(new LegislativeInitiativeClosed($item, 'closed'));
                    }

                    \DB::commit();
                } catch (\Exception $e){
                    \DB::rollBack();
                    \Log::error('Close legislative initiative (ID '. $item->id.') error: '.$e);
                }
            }
        }
        return Command::SUCCESS;
    }
}

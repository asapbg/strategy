<?php

namespace App\Console\Commands;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Http\Controllers\SsevController;
use App\Models\LegislativeInitiative;
use App\Notifications\SendLegislativeInitiative;
use Illuminate\Console\Command;

class SendLegislativeInitiativeSsev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssev:legislative_initiative';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send legislative initiatives by SSEV when they have enough support';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $items = \DB::select('
            select
                legislative_initiative.id,
                legislative_initiative.cap,
                sum(case when legislative_initiative_votes.is_like then 1 else 0 end) as like_cnt,
                sum(case when legislative_initiative_votes.is_like then 0 else 1 end) as dislike_cnt
            from legislative_initiative
            join legislative_initiative_votes on legislative_initiative_votes.legislative_initiative_id = legislative_initiative.id and legislative_initiative_votes.deleted_at is null
            where
                legislative_initiative.ready_to_send = 1
                and legislative_initiative.status = \''.LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value.'\'
                and legislative_initiative.deleted_at is null
            group by legislative_initiative.id
        ');

        if(sizeof($items)) {
            foreach ($items as $item){
                $li = LegislativeInitiative::find($item->id);
                if((int)$item->cap <= ((int)$item->like_cnt - (int)$item->dislike_cnt)){
                    if($li){
                        if($li->institutions->count()){
                            \DB::beginTransaction();
                            try {
                                //TODO schedule SSEV notification
                                $sendToAtLeastOne = false;
                                foreach ($li->institutions as $institution){
                                    $ssevProfile = SsevController::getInstitutionSsevProfile($institution);
                                    if($ssevProfile){
                                        $sendToAtLeastOne = true;
                                        $institution->notify(new SendLegislativeInitiative($li, $ssevProfile));
                                    }
                                }
                                //legislative initiative status
                                if($sendToAtLeastOne){
                                    $li->status = LegislativeInitiativeStatusesEnum::STATUS_SEND->value;
                                    $li->save();
                                }
                                \DB::commit();
                            } catch (\Exception $e){
                                \DB::rollBack();
                                \Log::error('Send legislative initiative (ID '. $item->id.') error: '.$e);
                            }
                        }
                    }
                } else{
                    \DB::beginTransaction();
                    try {
                        $li->ready_to_send = 0;
                        $li->save();
                        \DB::commit();
                    } catch (\Exception $e){
                        \DB::rollBack();
                        \Log::error('Revert legislative initiative to not ready for send (ID '. $li->id.') error: '.$e);
                    }
                }
            }
        }
        return Command::SUCCESS;
    }
}

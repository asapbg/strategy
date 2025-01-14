<?php

namespace App\Console\Commands;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Http\Controllers\SsevController;
use App\Models\LegislativeInitiative;
use App\Notifications\SendLegislativeInitiative;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $initiatives = DB::select("
            select legislative_initiative.id
              from legislative_initiative
              join legislative_initiative_votes as votes on votes.legislative_initiative_id = legislative_initiative.id and votes.deleted_at is null
             where ready_to_send = 1
                   and status = '". LegislativeInitiativeStatusesEnum::STATUS_SEND->value ."'
                   and send_at is null
                   and legislative_initiative.deleted_at is null
          group by legislative_initiative.id
        ");

        if (count($initiatives)) {
            foreach ($initiatives as $initiative) {
                $li = LegislativeInitiative::find($initiative->id);
                if ($li->institutions->count()) {
                    DB::beginTransaction();
                    try {
                        //TODO schedule SSEV notification
                        $sendToAtLeastOne = false;
                        foreach ($li->institutions as $institution) {
                            $ssev_profile_id = SsevController::getInstitutionSsevProfileId($institution);
                            if ($ssev_profile_id) {
                                $sendToAtLeastOne = true;
                                $institution->notify(new SendLegislativeInitiative($li, $ssev_profile_id));
                            }
                        }
                        //legislative initiative status
                        if ($sendToAtLeastOne) {
                            $li->send_at = Carbon::now()->format('Y-m-d H:i:s');
                            $li->save();
                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        \Log::error("Send legislative initiative (ID $initiative->id) error: " . $e);
                    }
                }
            }
        }
        return Command::SUCCESS;
    }
}

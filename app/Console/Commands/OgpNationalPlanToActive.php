<?php

namespace App\Console\Commands;

use App\Models\OgpPlan;
use App\Models\OgpStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OgpNationalPlanToActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ogp:to_active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Draft Ogp national as active';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $draftStatus = OgpStatus::Draft()->first()->id;
        $toDevelopment = OgpPlan::Active()
            ->where('ogp_status_id', $draftStatus)
            ->where('national_plan', 1)
            ->where('from_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('to_date', '>=', Carbon::now()->format('Y-m-d'))
            ->get();

        if($toDevelopment->count()){
            foreach ($toDevelopment as $plan){
                $plan->ogp_status_id = OgpStatus::ActiveStatus()->first()->id;
                $plan->save();
            }
        }
        return Command::SUCCESS;
    }
}

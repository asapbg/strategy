<?php

namespace App\Console\Commands;

use App\Models\OgpPlan;
use App\Models\OgpStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OgpPlanInDevelopment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ogp:to_development';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Draft Ogp plans in development process';

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
            ->where('from_date_develop', '<=', Carbon::now()->format('Y-m-d'))
            ->where('to_date_develop', '>=', Carbon::now()->format('Y-m-d'))
            ->get();

        if($toDevelopment->count()){
            foreach ($toDevelopment as $plan){
                $plan->ogp_status_id = OgpStatus::InDevelopment()->first()->id;
                $plan->save();
            }
        }
        return Command::SUCCESS;
    }
}

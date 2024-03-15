<?php

namespace App\Console\Commands;

use App\Models\OgpPlan;
use App\Models\OgpStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OgpNationalPlanToFinal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ogp:to_final';

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
        $activeStatus = OgpStatus::ActiveStatus()->first()->id;
        $items = OgpPlan::Active()
            ->where('ogp_status_id', $activeStatus)
            ->where('national_plan', 1)
            ->where('to_date', '<', Carbon::now()->format('Y-m-d'))
            ->get();

        if($items->count()){
            foreach ($items as $plan){
                $plan->ogp_status_id = OgpStatus::Final()->first()->id;
                $plan->save();
            }
        }
        return Command::SUCCESS;
    }
}

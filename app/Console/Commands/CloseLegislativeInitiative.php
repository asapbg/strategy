<?php

namespace App\Console\Commands;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Http\Controllers\SsevController;
use App\Models\LegislativeInitiative;
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

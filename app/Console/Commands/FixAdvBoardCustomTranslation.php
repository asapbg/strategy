<?php

namespace App\Console\Commands;

use App\Models\AdvisoryBoardCustom;
use App\Models\AdvisoryBoardCustomTranslation;
use Illuminate\Console\Command;

class FixAdvBoardCustomTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:adv_board_custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix translation of Custom sections by removing style tags';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $custom = AdvisoryBoardCustomTranslation::get();
        if($custom->count()){
            foreach ($custom as $c){
                $currentBody = $c->body;
                $c->body = $currentBody;
                $c->save();
            }
            $this->comment("Records are updated");
            return Command::SUCCESS;
        } else{
            $this->comment("Didn't find any records for update");
            return Command::SUCCESS;
        }
    }
}

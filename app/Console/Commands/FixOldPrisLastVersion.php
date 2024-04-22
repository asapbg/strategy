<?php

namespace App\Console\Commands;

use App\Enums\PrisDocChangeTypeEnum;
use App\Models\LegalActType;
use App\Models\Pris;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixOldPrisLastVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:pris_last_version {max_old_pris_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix PRIS last version after migrate';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Pris::unsetEventDispatcher();
        activity()->disableLogging();

        $this->comment('Start at '.date('Y-m-d H:i:s'));
        $maxLocalOldPrisId = $this->argument('max_old_pris_id');
        if(!$maxLocalOldPrisId){
            $this->error('Missing max pris id for update');
        }

        $step = 50;
        $currentStep = 0;
        while ($currentStep <= $maxLocalOldPrisId) {
            $records = DB::select(
                'select
                        p.old_id ,
                        count(p.old_id) as cnt,
                        json_agg(json_build_object(\'id\', p.id, \'created_at\', p.created_at, \'last_version\', p.last_version)) as records
                    from pris p
                    where p.old_id <= '.(int)$maxLocalOldPrisId.'
                        and p.deleted_at is null
                    group by p.old_id
                    order by p.created_at desc, p.id desc'
            );

            if(sizeof($records)){
                foreach ($records as $row){
                    $duplicated = json_decode($row->records, true);
                    if(!is_array($duplicated) || !sizeof($duplicated)){
                        continue;
                    }

                    if((int)$row->cnt > 1){
                        usort($duplicated, function ($a, $b) { return $b['created_at'] > $a['created_at']; });
                        dd($duplicated);
                    } else{
//                        $rowToUpdate = Pris::find((int)$duplicated[0]['id']);
//                        $rowToUpdate->last_vestion = 1;
//                        $rowToUpdate->save();
                    }
                }
            }
            $currentStep += $step;
        }

        return Command::SUCCESS;
    }
}

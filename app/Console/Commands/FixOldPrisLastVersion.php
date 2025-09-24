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
    protected $signature = 'fix:pris_last_version';

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
        activity()->disableLogging();
        $this->comment('Start at ' . date('Y-m-d H:i:s'));
        $maxLocalOldPrisId = Pris::max('old_id');
        if (!$maxLocalOldPrisId) {
            $this->error('Missing max pris id for update');
        }

        $step = 10000;
        $currentStep = 0;
        $stop = false;
        while ($currentStep <= $maxLocalOldPrisId && !$stop) {
            //$this->comment("Current step: $currentStep");
            $records = DB::select('
                select p.legal_act_type_id || \' - \' || p.doc_num as duplicated,
                        count(p.id) as cnt,
                        json_agg(json_build_object(\'id\', p.id, \'old_id\', p.old_id, \'created_at\', p.created_at, \'last_version\', p.last_version)) as records
                  from pris p
                 where p.old_id <= ' . (int)$maxLocalOldPrisId . '
                   and p.old_id is not null
                   and p.deleted_at is null
              group by p.legal_act_type_id , p.doc_num, p.doc_date
            ');

            if (sizeof($records)) {
                $lastV = array();
                $notLastV = array();

                foreach ($records as $row) {
                    $duplicated = json_decode($row->records, true);
                    if (!is_array($duplicated) || !sizeof($duplicated)) {
                        continue;
                    }

                    if ((int)$row->cnt > 1) {
                        //if only one has last vesrion
                        $foundLastV = array_sum(array_column($duplicated, 'last_version'));
                        if ($foundLastV == 1) {
//                            $this->comment('Found only one last version');
                            foreach ($duplicated as $r) {
                                if ($r['last_version']) {
                                    $lastV[(int)$r['id']] = (int)$r['id'];
                                } else {
                                    $notLastV[(int)$r['id']] = (int)$r['id'];
                                }
                            }
                        } else {
                            //Many or missing last version
//                            $this->comment('Many or missing last version');
                            usort($duplicated, function ($a, $b) {
                                return $b['id'] > $a['id'];
                            });
                            $first = true;
                            foreach ($duplicated as $r) {
                                if ($first) {
                                    $first = false;
                                    $lastV[(int)$r['id']] = (int)$r['id'];
                                } else {
                                    $notLastV[(int)$r['id']] = (int)$r['id'];
                                }
                            }
                        }
                    } else {
                        //Only one record
//                        $this->comment('Only one record');
                        $lastV[(int)$duplicated[0]['id']] = (int)$duplicated[0]['id'];
                    }
                }

                if (sizeof($lastV)) {
                    $lastVChunk = array_chunk($lastV, 50);
                    foreach ($lastVChunk as $ids) {
                        Pris::whereIn('id', $ids)->update(['asap_last_version' => 1]);
                    }
                }
                if (sizeof($notLastV)) {
                    $lastVChunk = array_chunk($notLastV, 50);
//                    $this->comment('Found not last versions');
                    foreach ($lastVChunk as $ids) {
                        Pris::whereIn('id', $ids)->update(['asap_last_version' => 0]);
                    }
                }
            }

            if ($currentStep == $maxLocalOldPrisId) {
                $stop = true;
            } else {
                $currentStep += $step;
                if ($currentStep > $maxLocalOldPrisId) {
                    $currentStep = $maxLocalOldPrisId;
                }
            }
        }

        $this->comment('Ended at ' . date('Y-m-d H:i:s'));

        return Command::SUCCESS;
    }
}

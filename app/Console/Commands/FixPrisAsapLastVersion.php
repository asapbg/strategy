<?php

namespace App\Console\Commands;

use App\Models\Pris;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPrisAsapLastVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:pris_asap_last_version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //file_put_contents('pris_asap_last_version_fixed.txt', '');
        $results = DB::select("
                select
                    doc_num, legal_act_type_id, protocol, count(*),
                    json_agg(
                        json_build_object(
                            'id', id,
                            'old_id', old_id,
                            'created_at', created_at,
                            'last_version', last_version,
                            'asap_last_version', asap_last_version
                        )
                    ) as records
                from
                    pris
                where
                    in_archive = 0
                    and published_at is not null
                    and deleted_at is null
                    and asap_last_version = 1
                    and old_id is not null
                group by
                    asap_last_version, doc_num, legal_act_type_id, protocol
                having
                    count(*) > 1
                order by doc_num
        ");

        foreach ($results as $result) {
            $our_old_ids = [];
            foreach (json_decode($result->records, true) as $record) {
                $our_old_ids[] = $record['old_id'];
            }
            $rootid = DB::connection('pris')->select('
                SELECT rootid FROM "archimed".e_items where id in ('.join(",", $our_old_ids).') GROUP BY rootid HAVING COUNT(*) > 1
            ');
            if (!count($rootid)) {
                continue;
            }
            $old_ids = DB::connection('pris')->select('
                select id
                from "archimed".e_items
                where rootid = '.$rootid[0]->rootid.' and id in ('.join(",", $our_old_ids).')
                order by rootid
            ');
            $pris_ids = array_map(function($item) {
                return $item->id;
            }, $old_ids);
            Pris::whereIn('old_id', $pris_ids)->where('asap_last_version', true)->where('last_version', false)->update(['asap_last_version' => false]);
            $text = "Fixed asap last version for old pris ids: ".implode(",", $pris_ids);
            $this->info($text);
            file_put_contents('pris_asap_last_version_fixed.txt', $text . PHP_EOL, FILE_APPEND);
        }
        return Command::SUCCESS;
    }
}

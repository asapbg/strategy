<?php

namespace App\Console\Commands;

use App\Models\Pris;
use App\Models\PrisChangePris;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PrisDocumentLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pris:document-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use old pris e_items and ei_links tables to insert links into pris_change_pris table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $links = DB::connection('pris')->select("
            SELECT items1.id as old_id_1, items2.id as old_id_2, rootitemid1, rootitemid2, links.description
              FROM archimed.ei_links as links
        INNER JOIN archimed.e_items items1 on links.rootitemid1 = items1.rootid and items1.islatestrevision = true and items1.activestate = 1
        INNER JOIN archimed.e_items items2 on links.rootitemid2 = items2.rootid and items2.islatestrevision = true and items2.activestate = 1
             WHERE (linktype = 'EDocs.Link')
               AND (rootitemid1 IS NOT NULL)
               AND (rootitemid2 IS NOT NULL)
               AND (links.description is not null and links.description <> '')
          ORDER BY items1 ASC, items2 ASC
        ");

        //dd($links);
        foreach ($links as $link) {
            $old_id_1 = $link->old_id_1;
            $old_id_2 = $link->old_id_2;

            $pris_1 = Pris::select('id')
                ->where('old_id', $old_id_1)
                ->where('asap_last_version', '=', 1)
                ->whereActive(true)
                ->first();
            $pris_2 = Pris::select('id')
                ->where('old_id', $old_id_2)
                ->where('asap_last_version', '=', 1)
                ->whereActive(true)
                ->first();

            if (!$pris_1 || !$pris_2) {
                $this->error("Missing or inactive pris doc with old_id $old_id_1 or old_id $old_id_2");
                continue;
            }

            $existingLink = PrisChangePris::whereRaw("
                (pris_id = $pris_1->id and changed_pris_id = $pris_2->id) or (pris_id = $pris_2->id and changed_pris_id = $pris_1->id)
            ")->first();
            if ($existingLink) {
                $this->error("Existing connection for pris doc with ID $pris_1->id (old_id $old_id_1) and doc with ID $pris_2->id (old_id $old_id_2)");
                continue;
            }
            PrisChangePris::create([
                'pris_id' => $pris_1->id,
                'changed_pris_id' => $pris_2->id,
            ]);
            $this->info("New connection inserted for doc with ID $pris_1->id (old_id $old_id_1) and doc with ID $pris_2->id (old_id $old_id_2)");
        }

        return Command::SUCCESS;
    }
}

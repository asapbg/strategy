<?php

namespace App\Console\Commands;

use App\Models\Pris;
use App\Models\StrategicDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class joinStrategicDocumentsPris extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:sd_join_pris';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Joins the strategic document in our system with the PRIS record according to the document number and date of the old strategic document.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $prisQuery = Pris::whereNotNull('old_id')->withTrashed()->get();
        $pris = $prisQuery->pluck('id', 'old_id')->toArray();
        $prisOldIds = implode(',', $prisQuery->pluck('old_id')->toArray());

        $oldDocuments = DB::connection('old_strategy_app')->select(
            "SELECT
                sd.id AS old_id,
                sd.documentnumber AS old_doc_number,
                sd.documentdate as old_doc_date
            FROM dbo.strategicdocuments AS sd
            WHERE sd.languageid = 1 AND sd.documentnumber <> '0'"
        );

        foreach ($oldDocuments as $oldDocument) {
            $oldPris = DB::connection('pris')->select("SELECT
                            id
                            FROM archimed.e_items pris
                            WHERE pris.date = '$oldDocument->old_doc_date'
                            AND pris.number = '$oldDocument->old_doc_number'
                            AND pris.id IN ($prisOldIds)
                            group by pris.id
                            order by pris.id DESC
                            LIMIT 1");

            if (count($oldPris) === 0) {
                $this->info('Old PRIS not found with number: ' . $oldDocument->old_doc_number . ' and date: ' . $oldDocument->old_doc_date);
                continue;
            }

            $oldPris = $oldPris[0];

            $doc = StrategicDocument::where('old_id', $oldDocument->old_id)->first();

            if (isset($doc)) {
                $doc->update([
                    'pris_act_id' => $pris[$oldPris->id]
                ]);

                $this->info('Updated Doc with ID: ' . $doc->id);
            } else {
                $this->info('Couldn\'t find document or pris!');
            }
        }
    }
}

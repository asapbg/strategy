<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\LegalActType;
use App\Models\Pris;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class updatePrisTranscriptFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pris:update-transcript-files';

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
        $this->info('Start at ' . date('Y-m-d H:i:s'));
        activity()->disableLogging();

        $path = File::PAGE_UPLOAD_PRIS;

        $ourLastVersionPris = Pris::withTrashed()
            ->where('asap_last_version', '=', 1)
            ->whereNotNull('old_id')
            //->whereIn('old_id', [40564,40494])
            ->where('legal_act_type_id', LegalActType::TYPE_TRANSCRIPTS)
            ->orderBy('old_id')
            ->get()
            ->pluck('id', 'old_id')
            ->toArray();
        if (!sizeof($ourLastVersionPris)) {
            $this->info('No pris records was found in our database');
            return Command::SUCCESS;
        }

        try {
            foreach ($ourLastVersionPris as $oldPrisId => $ourPrisId) {
                $prisFiles = DB::connection('pris')->select('
                    select split_part(b.bloburi, \'/\', -1) as uuid,
                           b.bloburi as old_pris_bloburi,
                           b.filename  as filename,
                           b.contenttype as content_type,
                           b.datecreated as created_at,
                           b.datemodified as updated_at,
                           bt."text" as file_text,
                           bc."content" as file_content
                      from edocs.document_pages dp2
                      join edocs.attachments a on a.pageid = dp2.pageid
                      join archimed.blobs b on b.id = a.blobid
                 left join archimed.blobtexts bt on bt.blobid = b.id
                      join blobs.blobcontents bc on bc.id::text = split_part(b.bloburi, \'/\', -1)
                     where true
                       and dp2.rootpageid in (select dp.rootpageid  from edocs.document_pages dp where dp.documentid = ' . $oldPrisId . ')
                  order by dp2.pageid desc
                ');

                if (!sizeof($prisFiles)) {
                    continue;
                }
                $this->comment('Found files for pris with ID ' . $ourPrisId);
                $pris = Pris::withTrashed()->find((int)$ourPrisId);
                if (!$pris) {
                    $this->error('Found files but can\'t find pris with ID ' . $ourPrisId);
                    continue;
                }

                foreach ($prisFiles as $f) {
                    if (empty($f->file_content)) {
                        file_put_contents('pris_files_without_content.txt', 'File Blob ID (' . $f->old_pris_bloburi . ')' . PHP_EOL, FILE_APPEND);
                        continue;
                    }

                    $fileNameToStore = trim($f->filename);
                    $fullPath = $path . $fileNameToStore;
                    if (Storage::disk('public_uploads')->exists($fullPath)) {
                        Storage::disk('public_uploads')->delete($fullPath);
                    }

                    $fullPath = $path . $ourPrisId . DIRECTORY_SEPARATOR .$fileNameToStore;
                    Storage::disk('public_uploads')->put($fullPath, $f->file_content);
                    File::where('id_object', $ourPrisId)->update([
                        'path' => $fullPath,
                    ]);
                    $this->comment('File updated for pris with ID ' . $pris->id);
                }
            }
        } catch (\Exception $e) {
            Log::error('Migration old pris files: ' . $e);
        }
        $this->info('End at ' . date('Y-m-d H:i:s'));

        return Command::SUCCESS;
    }
}

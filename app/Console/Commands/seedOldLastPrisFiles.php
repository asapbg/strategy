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

class seedOldLastPrisFiles extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'old:pris_files {clear=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate last PRIS data to application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start at ' . date('Y-m-d H:i:s'));
        activity()->disableLogging();
        $clearBeforeStart = (bool)$this->argument('clear');
        try {
            if ($clearBeforeStart) {
                //$this->clearFiles();
            }
        } catch (\Exception $e) {
            Log::error('Migration old pris files Can\'t clear files before start: ' . $e);
        }

        file_put_contents('pris_files_without_content.txt', '');
        $path = File::PAGE_UPLOAD_PRIS;

        $ourLastVersionPris = Pris::withTrashed()
            ->where('asap_last_version', '=', 1)
            ->whereNotNull('old_id')
            ->where('id', '>', 5000)
            //->where('id', '=', 137452)
            //->where('legal_act_type_id', LegalActType::TYPE_DECISION)
            ->orderBy('old_id')
            ->get()
            ->pluck('id', 'old_id')
            ->toArray();
        if (!sizeof($ourLastVersionPris)) {
            $this->info('No pris records was found in our database');
            return Command::SUCCESS;
        }

        $ourLastVersionFiles = File::where('code_object', '=', File::CODE_OBJ_PRIS)
            ->whereNotNull('old_pris_bloburi')
            ->where('locale', '=', 'bg')
            ->get()
            ->pluck('id_object', 'old_pris_bloburi')
            ->toArray();

        $formatTimestamp = 'Y-m-d H:i:s';

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
                //$this->comment('Found files for pris with ID ' . $ourPrisId);
//                $pris = Pris::withTrashed()->find((int)$ourPrisId);
//                if (!$pris) {
//                    $this->error('Found files but can\'t find pris with ID ' . $ourPrisId);
//                    continue;
//                }

                foreach ($prisFiles as $prisFile) {
                    if (isset($ourLastVersionFiles[$prisFile->old_pris_bloburi]) && $ourLastVersionFiles[$prisFile->old_pris_bloburi] == $ourPrisId) {
                        $this->comment('File for pris id ' . $ourPrisId . ' already exist');
                        continue;
                    }
                    if (empty($prisFile->file_content)) {
                        file_put_contents('pris_files_without_content.txt', 'File Blob ID (' . $prisFile->old_pris_bloburi . ')' . PHP_EOL, FILE_APPEND);
                        continue;
                    }

                    //create file
                    $fileNameToStore = trim($prisFile->filename);
                    $fullPath = $path . $ourPrisId . DIRECTORY_SEPARATOR .$fileNameToStore;
                    if (!Storage::disk('public_uploads')->exists($fullPath)) {
                        Storage::disk('public_uploads')->put($fullPath, $prisFile->file_content);
                    }

                    foreach (['bg', 'en'] as $code) {
                        $newFile = new File([
                            'id_object' => $ourPrisId,
                            'code_object' => File::CODE_OBJ_PRIS,
                            'filename' => $fileNameToStore,
                            'file_text' => $prisFile->file_text,
                            'content_type' => Storage::disk('public_uploads')->mimeType($fullPath),
                            'path' => $fullPath,
                            'description_' . $code => $prisFile->filename,
                            'sys_user' => null,
                            'locale' => $code,
                            'version' => '1.0',
                            'created_at' => Carbon::parse($prisFile->created_at)->format($formatTimestamp),
                            'updated_at' => Carbon::parse($prisFile->updated_at)->format($formatTimestamp),
                            'old_pris_bloburi' => $prisFile->old_pris_bloburi
                        ]);
                        $newFile->save();
                        if ($code == 'bg') {
                            $ourLastVersionFiles[$prisFile->old_pris_bloburi] = $newFile->id;
                        }
                        //$ocr = new FileOcr($newFile->refresh());
                        //$ocr->extractText();
                    }
                    $this->info("File inserted for pris with ID $ourPrisId");
                }
            }
        } catch (\Exception $e) {
            $this->error('Error: '. $e->getMessage());
            Log::error('Migration old pris files: ' . $e->getMessage());
        }
        $this->info('End at ' . date('Y-m-d H:i:s'));
    }

    /**
     * @return void
     */
    private function clearFiles(): void
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $cntFiles = 1;
        while ($cntFiles > 0) {
            $files = File::where('code_object', '=', File::CODE_OBJ_PRIS)->limit(1000)->get();
            $cntFiles = $files->count();
            if ($cntFiles) {
                File::whereIn('id', $files->pluck('id')->toArray())->update([
                    'deleted_at' => $now,
                    'old_pris_bloburi' => null
                ]);
            }
        }
    }
}

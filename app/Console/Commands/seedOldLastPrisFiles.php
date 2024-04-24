<?php

namespace App\Console\Commands;

use App\Models\CustomActivity;
use App\Models\File;
use App\Models\InstitutionLevel;
use App\Models\Pris;
use App\Models\StrategicDocuments\Institution;
use App\Models\Tag;
use App\Services\FileOcr;
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
        $this->info('Start at '.date('Y-m-d H:i:s'));
        activity()->disableLogging();
        $clearBeforeStart = (bool)$this->argument('clear');
        $now = Carbon::now()->format('Y-m-d H:i:s');
        try {
            if($clearBeforeStart){
                $cntFiles = 1;
                while ($cntFiles > 0){
                    $files = File::where('code_object', '=', File::CODE_OBJ_PRIS)->limit(1000)->get();
                    $cntFiles = $files->count();
                    if($cntFiles) {
                        File::whereIn('id', $files->pluck('id')->toArray())->update([
                            'deleted_at' => $now,
                            'old_pris_bloburi' => null
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Migration old pris files Can\'t clear files before start: '.$e);
        }

dd('ok');
        file_put_contents('pris_files_without_content.txt', '');
        $path = File::PAGE_UPLOAD_PRIS;

        $ourLastVersionPris = Pris::withTrashed()
            ->where('asap_last_version', '=', 1)
            ->whereNotNull('old_id')
            ->orderBy('old_id')
            ->get()
            ->pluck('id', 'old_id')
            ->toArray();

        $ourLastVersionFiles = File::where('code_object', '=', File::CODE_OBJ_PRIS)
            ->whereNotNull('old_pris_bloburi')
            ->where('locale', '=', 'bg')
            ->get()
            ->pluck('id', 'old_pris_bloburi')
            ->toArray();

        $formatTimestamp = 'Y-m-d H:i:s';

        if(sizeof($ourLastVersionPris)){
            foreach ($ourLastVersionPris as $oldPrisId => $ourPrisId){
                try {
                    $prisFiles = DB::connection('pris')
                        ->select('
                            select
                                split_part(b.bloburi, \'/\', -1) as uuid,
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
                                and dp2.rootpageid in (select dp.rootpageid  from edocs.document_pages dp where dp.documentid = '.$oldPrisId.')
                            order by dp2.pageid desc');

                    if (sizeof($prisFiles)) {
                        $this->comment('Found files for pris with ID '.$ourPrisId);
                        $pris = Pris::withTrashed()->find((int)$ourPrisId);
                        if(!$pris){
                            $this->error('Found files but can\'t find pris with ID '.$ourPrisId);
                            continue;
                        }

                        foreach ($prisFiles as $f) {
                            if(!isset($ourLastVersionFiles[$f->old_pris_bloburi])){
                                if(!empty($f->file_content)) {
                                    //create file
                                    $fileNameToStore = trim($f->filename);
                                    $fullPath = $path.$fileNameToStore;
                                    Storage::disk('public_uploads')->put($fullPath, $f->file_content);
                                    $file = Storage::disk('public_uploads')->get($fullPath);

                                    if($file) {
                                        foreach (['bg', 'en'] as $code) {
                                            $newFile = new File([
                                                'id_object' => $pris->id,
                                                'code_object' => File::CODE_OBJ_PRIS,
                                                'filename' => $fileNameToStore,
                                                'file_text' => $f->file_text,
                                                'content_type' => Storage::disk('public_uploads')->mimeType($fullPath),
                                                'path' => $fullPath,
                                                'description_'.$code => $f->filename,
                                                'sys_user' => null,
                                                'locale' => $code,
                                                'version' => '1.0',
                                                'created_at' => Carbon::parse($f->created_at)->format($formatTimestamp),
                                                'updated_at' => Carbon::parse($f->updated_at)->format($formatTimestamp),
                                                'old_pris_bloburi' => $f->old_pris_bloburi
                                            ]);
                                            $newFile->save();
                                            if($code == 'bg'){
                                                $ourLastVersionFiles[$f->old_pris_bloburi] = $newFile->id;
                                            }
//                                                    $ocr = new FileOcr($newFile->refresh());
//                                                    $ocr->extractText();
                                        }
                                    }
                                    $this->comment('File inserted for pris with ID '.$pris->id);
                                } else{
                                    file_put_contents('pris_files_without_content.txt', 'File Blob ID ('.$f->old_pris_bloburi.')'.$f.PHP_EOL, FILE_APPEND);
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Migration old pris files: ' . $e);
                }
            }
        }
        $this->info('End at '.date('Y-m-d H:i:s'));
    }
}

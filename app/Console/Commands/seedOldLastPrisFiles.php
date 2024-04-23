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
    protected $signature = 'old:pris_files';

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
        activity()->disableLogging();

        $path = File::PAGE_UPLOAD_PRIS;
        $this->info('Start at '.date('Y-m-d H:i:s'));
        file_put_contents('pris_files_without_content.txt', '');

        //Check how many are old pris
//        select count(ei.id) from e_items ei where ei.itemtypeid <> 5017;

        $ourPris = Pris::whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();

        $formatTimestamp = 'Y-m-d H:i:s';
        //records per query
        $step = 50;
        //max id in old db
        //start from this id in old database
        $maxOldId = Pris::max('old_id');
        $currentStep = Pris::min('old_id');

        if($maxOldId){
            try {
                while ($currentStep < $maxOldId) {
                    echo "From Id: " . $currentStep . PHP_EOL;

                    $oldPages = DB::connection('pris')
                        ->select('
                                        select
                                             split_part(f.bloburi, \'/\', -1) as uuid,
                                             f.filename  as filename,
                                             f.contenttype as content_type,
                                             f.datecreated as created_at,
                                             f.datemodified as updated_at,
                                             ft."text" as file_text,
                                             b."content" as file_content,
                                             att.documentid as pris_old_id
                                        from edocs.attachments att
                                        join archimed.blobs f on f.id = att.blobid
                                        join archimed.blobtexts ft on ft.blobid = f.id
                                        join blobs.blobcontents b on b.id::text = split_part(f.bloburi, \'/\', -1)
                                        where true
                                            and att.documentid >= ' . $currentStep . '
                                            and att.documentid < ' . ($currentStep + $step) . '
                                        order by att.documentid asc, att.pageid asc');

                    if (sizeof($oldPages)) {
                        foreach ($oldPages as $f) {
                            if(!isset($ourPris[(int)$f->pris_old_id])){
                                $this->info('Missing pris with old id: '.$f->pris_old_id.' or file is not connected to pris');
                                continue;
                            }
                            $prisId = $ourPris[(int)$f->pris_old_id];
                            $file = null;
                            $fileExist = null;
                            if(!empty($f->file_content)) {
                                //$fileNameToStore = str_replace('.', '', microtime(true)).strtolower($f->doc_type);
                                $fileNameToStore = trim($f->filename);
                                $fullPath = $path.$fileNameToStore;
                                $fileExist = File::where('path', '=', $fullPath)
                                    ->where('filename', '=', $fileNameToStore)
                                    ->where('id_object','=', $prisId)
                                    ->where('code_object','=', File::CODE_OBJ_PRIS)
                                    ->get()
                                    ->first();

                                if(is_null($fileExist)){
                                    Storage::disk('public_uploads')->put($fullPath, $f->file_content);
                                    $file = Storage::disk('public_uploads')->get($fullPath);
                                }
//                                else{
//                                    $this->info('File '.$fullPath. ' exist.');
//                                }
                            } else{
                                file_put_contents('pris_files_without_content.txt', 'Pris ID ('.$f->pris_old_id.')'.$f.PHP_EOL, FILE_APPEND);
                            }

                            if($file) {
                                $fileIds = [];
                                foreach (['bg', 'en'] as $code) {
                                    //TODO catch file version
                                    //$version = File::where('locale', '=', $code)->where('id_object', '=', $newItem->id)->where('code_object', '=', File::CODE_OBJ_PRIS)->count();
                                    $version = 0;
                                    $newFile = new File([
                                        'id_object' => $prisId,
                                        'code_object' => File::CODE_OBJ_PRIS,
                                        'filename' => $fileNameToStore,
                                        'content_type' => Storage::disk('public_uploads')->mimeType($fullPath),
                                        'path' => $fullPath,
                                        'description_'.$code => $f->filename,
                                        'sys_user' => null,
                                        'locale' => $code,
                                        'version' => ($version + 1).'.0',
                                        'created_at' => Carbon::parse($f->created_at)->format($formatTimestamp),
                                        'updated_at' => Carbon::parse($f->updated_at)->format($formatTimestamp)
                                    ]);
                                    $newFile->save();
                                    $fileIds[] = $newFile->id;
//                                                    $ocr = new FileOcr($newFile->refresh());
//                                                    $ocr->extractText();
                                }

                                File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
                                File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);
                                $this->comment('Pris with old id '.$f->pris_old_id.' files updated');
                            }
                        }
                    }
                    $currentStep += $step;
                }
            } catch (\Exception $e) {
                Log::error('Migration old pris files: ' . $e);
            }
        }
        $this->info('End at '.date('Y-m-d H:i:s'));
    }
}

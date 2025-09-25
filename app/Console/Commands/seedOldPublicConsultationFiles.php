<?php

namespace App\Console\Commands;

use App\Models\Consultations\PublicConsultation;
use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class seedOldPublicConsultationFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:pc_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old Strategy public consultations files to application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        file_put_contents('missing_pc_files_in_old_files.txt', '');
        activity()->disableLogging();
        $this->info('Start at ' . date('Y-m-d H:i:s'));
        $formatTimestamp = 'Y-m-d H:i:s';

        $directory = File::PUBLIC_CONSULTATIONS_UPLOAD_DIR;
        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('old_strategy_app')->select('select max(dbo.publicconsultations.id) from dbo.publicconsultations');
        //start from this id in old database
        $currentStep = 0;

        if (!isset($maxOldId[0])) {
            $this->error('Max Old Id not found');
            return COMMAND::FAILURE;
        }

        $ourPc = PublicConsultation::whereNotNull('old_id')->withTrashed()->get()->pluck('id', 'old_id')->toArray();
        $ourFiles = File::where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)->whereNotNull('import_old_id')->get()->pluck('id', 'import_old_id')->toArray();
        $ourUsers = User::whereNotNull('old_id')->withTrashed()->get()->pluck('id', 'old_id')->toArray();

        $stop = false;
        $maxOldId = (int)$maxOldId[0]->max;
        //$maxOldId = 0;
        while ($currentStep <= $maxOldId && !$stop) {
            //$this->comment("Current step: $currentStep");
            $oldDbFiles = DB::connection('old_strategy_app')
                ->select('
                    select
                        uf.fileid as file_old_id,
                        uf.recordid as id,
                        f."name" as name,
                        f.description,
                        case when f.isdeleted = true then 1 else 0 end as deleted,
                        case when f.isactive = true then 1 else 0 end as active,
                        f.createdbyuserid as old_user_id,
                        f.datecreated as created_at,
                        f.datemodified as updated_at,
                        folders.id as folder_id,
                        folders."name" as folder_name,
                        folders.description as folder_description
                    from dbo.publicconsultations p
                    left join dbo.used_files uf on uf.recordid = p.id
                    left join dbo.files f on f.id = uf.fileid
                    left join dbo.filefolders folders on folders.id = f.folderid
                    where true
                        and p.id >= ' . $currentStep . '
                        and p.id < ' . ($currentStep + $step) . '
                        and p.languageid = 1
                        and f.id is not null
                        and folders.id is not null
                        and uf.tabletype = 3
                        --and p.id = 1926
                        -- check if uf.tabletype should be 3
                    order by p.id asc
                ');

            if (!sizeof($oldDbFiles)) {
                $this->comment("No files was found for Current step: $currentStep");
                if ($currentStep == $maxOldId) {
                    $stop = true;
                } else {
                    $currentStep += $step;
                    if ($currentStep > $maxOldId) {
                        $currentStep = $maxOldId;
                    }
                }
                continue;
            }

            foreach ($oldDbFiles as $oldDbFile) {

                if (isset($ourFiles[(int)$oldDbFile->file_old_id])) {
                    $this->comment('File with old id ' . $oldDbFile->file_old_id . ' already exist');
                    continue;
                }

                if (!isset($ourPc[$oldDbFile->id])) {
                    $this->comment('Missing public consultation with old ID (' . $oldDbFile->file_old_id . ') for file with old id ' . $oldDbFile->file_old_id);
                    continue;
                }

                try {
                    $info = pathinfo($oldDbFile->name);
                    $filename = $info['filename'];
                    $extension = $info['extension'];
                    if (isset($info['extension'])) {
                        $newName = str_replace('-', '_', Str::slug(str_replace(' ', '_', $filename), '_')) . '.' . $extension;
                    } else {
                        $newName = str_replace('-', '_', Str::slug(str_replace(' ', '_', $filename), '_'));
                    }
                    $folder_path = 'oldfiles' . DIRECTORY_SEPARATOR . 'Folder_' . $oldDbFile->folder_id . DIRECTORY_SEPARATOR;
                    $copy_from = base_path($folder_path . $oldDbFile->name);

                    /**
                     * As the name of the files are case-sensitive, if the file is not found with the name in the
                     * database, we are going to check with all upper and lower case letters, and finally with capitalized first letter
                     */
                    if (!file_exists($copy_from)) {
                        $copy_from = base_path($folder_path .mb_strtoupper($filename).".".$extension);
                        if (!file_exists($copy_from)) {
                            $copy_from = base_path($folder_path .mb_strtolower($filename).".".$extension);
                        }
                        if (!file_exists($copy_from)) {
                            $copy_from = base_path($folder_path .capitalize(mb_strtolower($filename)).".".$extension);
                        }
                        if (!file_exists($copy_from)) {
                            $this->error('File '.$copy_from. ' do not exist!');
                            file_put_contents('missing_pc_files_in_old_files.txt', $folder_path . $oldDbFile->name . PHP_EOL, FILE_APPEND);
                            continue;
                        }
                    }

                    $path = $directory . $ourPc[$oldDbFile->id] . DIRECTORY_SEPARATOR;
                    $to = base_path('public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $path . $newName);

                    if (!Storage::disk('public_uploads')->exists($path)) {
                        Storage::disk('public_uploads')->makeDirectory($path);
                    }

                    $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);

                    if (!$copied_file) {
                        $this->error('Can\'t copy file');
                    }
                    $contentType = Storage::disk('public_uploads')->mimeType($path . $newName);

                    $fileIds = [];
                    foreach (['bg', 'en'] as $code) {
                        //TODO catch file version
                        //$version = File::where('locale', '=', $code)->where('id_object', '=', $newItem->id)->where('code_object', '=', File::CODE_OBJ_PRIS)->count();
                        $version = 0;
                        $newFile = new File([
                            'id_object' => $ourPc[$oldDbFile->id],
                            'code_object' => File::CODE_OBJ_PUBLIC_CONSULTATION,
                            'filename' => $newName,
                            'content_type' => $contentType,
                            'path' => $path . $newName,
                            'description_' . $code => !empty($oldDbFile->description) ? $oldDbFile->description : $oldDbFile->name,
                            'sys_user' => $ourUsers[(int)$oldDbFile->old_user_id] ?? null,
                            'locale' => $code,
                            'version' => ($version + 1) . '.0',
                            'created_at' => Carbon::parse($oldDbFile->created_at)->format($formatTimestamp),
                            'updated_at' => Carbon::parse($oldDbFile->updated_at)->format($formatTimestamp),
                            'import_old_id' => $oldDbFile->file_old_id
                        ]);
                        $newFile->save();
                        $fileIds[] = $newFile->id;
                        //$ocr = new FileOcr($newFile->refresh());
                        //$ocr->extractText();
                    }

                    File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
                    File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);
                    $this->info('File ID ' . $newFile->id . ' successfully saved for PC ID ' . $ourPc[$oldDbFile->id] . ' Old ID: ' . $oldDbFile->file_old_id);

                } catch (\Exception $e) {
                    Log::error('Migration old strategy public consultations, comment and files: ' . $e->getMessage());
                    $this->error("Error: ". $e->getMessage());
                }
            }

            if ($currentStep == $maxOldId) {
                $stop = true;
            } else {
                $currentStep += $step;
                if ($currentStep > $maxOldId) {
                    $currentStep = $maxOldId;
                }
            }
        }

        $this->info('End at ' . date('Y-m-d H:i:s'));
    }
}

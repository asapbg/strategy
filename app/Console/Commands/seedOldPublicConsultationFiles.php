<?php

namespace App\Console\Commands;

use App\Enums\DocTypesEnum;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\File;
use App\Models\InstitutionLevel;
use App\Models\StrategicDocuments\Institution;
use App\Models\User;
use App\Services\FileOcr;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $this->info('Start at '.date('Y-m-d H:i:s'));
        $locales = config('available_languages');
        $formatTimestamp = 'Y-m-d H:i:s';
        $formatDate = 'Y-m-d';

        $directory = File::PUBLIC_CONSULTATIONS_UPLOAD_DIR;
        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('old_strategy_app')->select('select max(dbo.publicconsultations.id) from dbo.publicconsultations');
        //start from this id in old database
        $currentStep = 1;

//        $ourUsersInstitutions = User::get()->pluck('institution_id', 'old_id')->toArray();
        $ourPc = PublicConsultation::whereNotNull('old_id')->withTrashed()->get()->pluck('id', 'old_id')->toArray();
        $ourFiles = File::where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)->whereNotNull('import_old_id')->get()->pluck('id', 'import_old_id')->toArray();
        $ourUsers = User::whereNotNull('old_id')->withTrashed()->get()->pluck('id', 'old_id')->toArray();

        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;
            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.PHP_EOL;
                $oldDbFiles= DB::connection('old_strategy_app')
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
                        -- check if uf.tabletype should be 3
                    order by p.datecreated desc
                    ');

                if (sizeof($oldDbFiles)) {
                    foreach ($oldDbFiles as $item) {
                        if(isset($ourFiles[(int)$item->file_old_id])) {
                            $this->comment('File with old id '.$item->file_old_id.' already exist');
                            continue;
                        }

                        if(!isset($ourPc[$item->id])) {
                            $this->comment('Missing public consultation with old ID ('.$item->id.') for file with old id '.$item->file_old_id);
                            continue;
                        }

                        DB::beginTransaction();
                        try {
                            $info = pathinfo($item->name);
                            if(isset($info['extension'])) {
                                $newName = str_replace('-', '_', Str::slug(str_replace(' ', '_', $info['filename']), '_')).'.'.$info['extension'];
                            } else{
                                $newName = str_replace('-', '_', Str::slug(str_replace(' ', '_', $info['filename']), '_'));
                            }

                            $copy_from = base_path('oldfiles'.DIRECTORY_SEPARATOR.'Folder_'. $item->folder_id.DIRECTORY_SEPARATOR.$item->name);
                            $to = base_path('public' . DIRECTORY_SEPARATOR . 'files'. DIRECTORY_SEPARATOR .$directory.$newName);

//                            if(!file_exists($copy_from)) {
//                                $this->comment('File '.$copy_from. 'do not exist!');
//                                continue;
//                            }
                            if(file_exists($copy_from)) {
                                $copied_file = \Illuminate\Support\Facades\File::copy($copy_from, $to);

                                if($copied_file) {
                                    $contentType = Storage::disk('public_uploads')->mimeType($directory.$newName);
                                    $fileIds = [];
                                    foreach (['bg', 'en'] as $code) {
                                        //TODO catch file version
                                        //$version = File::where('locale', '=', $code)->where('id_object', '=', $newItem->id)->where('code_object', '=', File::CODE_OBJ_PRIS)->count();
                                        $version = 0;
                                        $newFile = new File([
                                            'id_object' => $ourPc[$item->id],
                                            'code_object' => File::CODE_OBJ_PUBLIC_CONSULTATION,
                                            'filename' => $newName,
                                            'content_type' => $contentType,
                                            'path' => $directory.$newName,
                                            'description_' . $code => !empty($item->description) ? $item->description : $item->name,
                                            'sys_user' => $ourUsers[(int)$item->old_user_id] ?? null,
                                            'locale' => $code,
                                            'version' => ($version + 1) . '.0',
                                            'created_at' => Carbon::parse($item->created_at)->format($formatTimestamp),
                                            'updated_at' => Carbon::parse($item->updated_at)->format($formatTimestamp),
                                            'import_old_id' => $item->file_old_id
                                        ]);
                                        $newFile->save();
                                        $fileIds[] = $newFile->id;
                                        //$ocr = new FileOcr($newFile->refresh());
                                        //$ocr->extractText();
                                    }

                                    File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
                                    File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);
                                    $this->comment('File ID '.$newFile->id.' Successfully saved for PC ID '.$ourPc[$item->id]. ' Old ID: '.$item->id );
                                } else{
                                    $this->comment('Can\'t copy file');
                                }
                            }
                            DB::commit();

                        } catch (\Exception $e) {
                            Log::error('Migration old strategy public consultations, comment and files: ' . $e);
                            DB::rollBack();
                            dd($item, $copied_files ?? 'no copied files');
                        }
                    }
                }
                $currentStep += $step;
            }
        }
        $this->info('End at '.date('Y-m-d H:i:s'));
    }
}

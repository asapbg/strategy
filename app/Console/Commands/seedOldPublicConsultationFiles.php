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

        $locales = config('available_languages');
        $formatTimestamp = 'Y-m-d H:i:s';
        $formatDate = 'Y-m-d';

        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('old_strategy_app')->select('select max(dbo.publicconsultations.id) from dbo.publicconsultations');
        //start from this id in old database
        $currentStep = 1;

//        $ourUsersInstitutions = User::get()->pluck('institution_id', 'old_id')->toArray();
        $ourPc = PublicConsultation::get()->whereNotNull('old_id')->pluck('id', 'old_id')->toArray();
        $ourUsers = User::get()->whereNotNull('old_id')->pluck('id', 'old_id')->toArray();
//        $ourInstitutions = Institution::with(['level'])->get()->pluck('level.nomenclature_level', 'id')->toArray();

        $directory = File::PUBLIC_CONSULTATIONS_UPLOAD_DIR;
        mkdirIfNotExists($directory);

        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;
            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.PHP_EOL;
                $oldDbFiles= DB::connection('old_strategy_app')
                    ->select('
                    select p.id ,
                           f.folderid
                        from dbo.publicconsultations p
                        join dbo.files f on f.id = p.mainfileid
                        where
                            p.id >= ' . $currentStep . '
                            and p.id < ' . ($currentStep + $step) . '
                    order by p.datecreated desc
                    limit 100');

                if (sizeof($oldDbFiles)) {
                    foreach ($oldDbFiles as $item) {
                    DB::beginTransaction();
                        try {
                            $directory_to_copy_from = base_path('document_library' . DIRECTORY_SEPARATOR . '10108' . DIRECTORY_SEPARATOR . $item->folderid);
                            //Storage::move($directory_to_copy_from, Storage::disk('public_uploads')->path($directory));

                            $copied_files = copyFiles($directory_to_copy_from, Storage::disk('public_uploads')->path($directory), $item->folderid);
dd($copied_files, $directory_to_copy_from, Storage::disk('public_uploads')->path($directory));
                            if(!empty($copied_files)) {
                                foreach ($copied_files as $file) {
                                    $fileIds = [];
                                    foreach (['bg', 'en'] as $code) {
                                        //TODO catch file version
                                        //$version = File::where('locale', '=', $code)->where('id_object', '=', $newItem->id)->where('code_object', '=', File::CODE_OBJ_PRIS)->count();
                                        $version = 0;
                                        $newFile = new File([
                                            'id_object' => $ourPc[$item->id],
                                            'code_object' => File::CODE_OBJ_PUBLIC_CONSULTATION,
                                            'filename' => $file['filename'],
                                            'content_type' => $file['content_type'],
                                            'path' => $directory.$file['filename'],
                                            'description_' . $code => !empty($item->name) ? $item->name : $item->description,
                                            'sys_user' => null,
                                            'locale' => $code,
                                            'version' => ($version + 1) . '.0'
                                        ]);
                                        $newFile->save();
                                        $fileIds[] = $newFile->id;
                                        $ocr = new FileOcr($newFile->refresh());
                                        $ocr->extractText();
                                    }

                                    File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
                                    File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);
                                }
                            }

                            DB::commit();
                        } catch (\Exception $e) {
                            Log::error('Migration old startegy public consultations, comment and files: ' . $e);
                            DB::rollBack();
                            dd($item, $copied_files ?? 'no copied files');
                        }
                    }
                }
                $currentStep += $step;
            }
        }
    }
}

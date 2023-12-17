<?php

namespace App\Console\Commands;

use App\Http\Controllers\CommonController;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\Consultations\PublicConsultationTranslation;
use App\Models\File;
use App\Models\Pris;
use App\Models\PrisTranslation;
use App\Models\PublicConsultationContact;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentTranslation;
use App\Models\Timeline;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class clearDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clear {section}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear db by section';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $section = $this->argument('section');
        if(empty($section)) {
            $this->error('Missing section parameter!');
        }

        switch ($section){
            case 'pris':
                $fromId = DB::table('pris')->select(DB::raw('min(old_id) as max'), 'id')->groupBy('id')->first();
                if($fromId) {
                    Schema::disableForeignKeyConstraints();
                    DB::table('pris_tag')->truncate();
                    DB::table('pris_change_pris')->truncate();
                    DB::table('pris_translations')->truncate();

                    $deleted = 1;
                    while ($deleted > 0) {
                        $files = File::where('id_object', '>=', $fromId->id)->where('code_object', '=', File::CODE_OBJ_PRIS)->limit(100)->get();
                        if($files->count()){
                            foreach ($files as $f) {
                                Storage::disk('public_uploads')->delete($f->path);
                            }
                            File::whereIn('id', $files->pluck('id')->toArray())->forceDelete();
                            $this->comment('100 files are deleted');
                            sleep(1);
                        } else{
                            $deleted = 0;
                        }
                    }
                    DB::table('pris')->truncate();
                    Schema::enableForeignKeyConstraints();
                }
                //DB::table('tag')->truncate();
                //DB::table('tag_translations')->truncate();

                break;
            case 'users':
                //TODO get only imported users and connected to them relations
                DB::table('model_has_roles')->truncate();
                DB::table('users')->truncate();
                break;
            case 'pc':
                //TODO get only imported pc and connected to them relations
                $fromId = DB::table('public_consultation')->select(DB::raw('min(old_id) as max'), 'id')->groupBy('id')->first();
                if($fromId) {
                    Schema::disableForeignKeyConstraints();

                    DB::table('public_consultation_poll')->truncate();
                    DB::table('public_consultation_connection')->truncate();
                    DB::table('public_consultation_timeline')->truncate();
                    DB::table('public_consultation_contact')->truncate();

                    $deleted = 1;
                    while ($deleted > 0) {
                        $files = File::where('id_object', '>=', $fromId->id)->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)->limit(100)->get();
                        if($files->count()){
                            foreach ($files as $f) {
                                Storage::disk('public_uploads')->delete($f->path);
                            }
                            File::whereIn('id', $files->pluck('id')->toArray())->forceDelete();
                            $this->comment('100 files are deleted');
                            sleep(1);
                        } else{
                            $deleted = 0;
                        }
                    }
                    Comments::where('object_code', '>=', Comments::PC_OBJ_CODE)->forceDelete();
                    DB::table('public_consultation_translations')->truncate();
                    DB::table('public_consultation')->truncate();
                    Schema::enableForeignKeyConstraints();
                }

                break;

            case 'sd':
                $ids = StrategicDocument::whereNotNull('old_id')->pluck('id');

                StrategicDocumentTranslation::whereIn('strategic_document_id', $ids)->forceDelete();
                CommonController::fixSequence('strategic_document_translations');

                StrategicDocument::whereIn('id', $ids)->forceDelete();
                CommonController::fixSequence('strategic_document');
                break;
            default:
                $this->error('Section not found!');
        }
        $this->comment('Clear end');
    }
}

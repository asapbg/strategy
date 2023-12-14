<?php

namespace App\Console\Commands;

use App\Http\Controllers\CommonController;
use App\Models\Consultations\PublicConsultation;
use App\Models\Consultations\PublicConsultationTranslation;
use App\Models\File;
use App\Models\Pris;
use App\Models\PrisTranslation;
use App\Models\PublicConsultationContact;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
                    DB::table('pris_tag')->where('pris_id', '>=', $fromId->id)->delete();
                    DB::table('pris_change_pris')->where('pris_id', '>=', $fromId->id)->delete();

                    PrisTranslation::where('pris_id', '>=', $fromId->id)->forceDelete();
                    CommonController::fixSequence('pris_translations');

                    $deleted = 1;
                    while ($deleted > 0) {
                        $deleted = File::where('id_object', '>=', $fromId->id)->where('code_object', '=', File::CODE_OBJ_PRIS)->limit(100)->forceDelete();
                        $this->comment('100 files are deleted');
                        sleep(1);
                    };

                    CommonController::fixSequence('files');

                    Pris::where('id', '>=', $fromId->id)->forceDelete();
                    CommonController::fixSequence('pris');
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
                    DB::table('public_consultation_connection')->where('public_consultation_id', '>=', $fromId->id)->delete();

                    PublicConsultationContact::where('public_consultation_id', '>=', $fromId->id)->forceDelete();
                    CommonController::fixSequence('public_consultation_contact');

                    $deleted = 1;
                    while ($deleted > 0) {
                        $deleted = File::where('id_object', '>=', $fromId->id)->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)->limit(100)->forceDelete();
                        sleep(1);
                        $this->comment('100 files are deleted');
                    }

                    PublicConsultationTranslation::where('public_consultation_id', '>=', $fromId->id)->forceDelete();
                    CommonController::fixSequence('pris_translations');

                    PublicConsultation::where('id', '>=', $fromId->id)->forceDelete();
                    CommonController::fixSequence('public_consultation');
                    Schema::enableForeignKeyConstraints();
                }

                break;
            default:
                $this->error('Section not found!');
        }
        $this->comment('Clear end');
    }
}

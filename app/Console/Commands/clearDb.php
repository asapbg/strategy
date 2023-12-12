<?php

namespace App\Console\Commands;

use App\Http\Controllers\CommonController;
use App\Models\CustomRole;
use App\Models\File;
use App\Models\Pris;
use App\Models\PrisTranslation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
                    DB::table('pris_tag')->where('pris_id', '>=', $fromId->id)->delete();
                    DB::table('pris_change_pris')->where('pris_id', '>=', $fromId->id)->delete();

                    PrisTranslation::where('pris_id', '>=', $fromId->id)->forceDelete();
                    CommonController::fixSequence('pris_translations');

                    File::where('id_object', '>=', $fromId->id)
                        ->where('code_object', '=', File::CODE_OBJ_PRIS)->forceDelete();
                    CommonController::fixSequence('files');

                    Pris::where('id', '>=', $fromId->id)->forceDelete();
                    CommonController::fixSequence('pris');
                }
                DB::table('tag')->truncate();
                DB::table('tag_translations')->truncate();

                break;
            case 'users':
                DB::table('model_has_roles')->truncate();
                DB::table('users')->truncate();
                break;
            case 'pc':
                DB::table('public_consultation_connection')->truncate();
                DB::table('public_consultation_contact')->truncate();
                DB::table('public_consultation_translations')->truncate();
                DB::table('public_consultation')->truncate();
                break;
            default:
                $this->error('Section not found!');
        }
        $this->comment('Clear end');
    }
}

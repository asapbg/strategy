<?php

namespace App\Console\Commands;

use App\Models\CustomRole;
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
                DB::table('pris_tag')->truncate();
                DB::table('pris_change_pris')->truncate();
                DB::table('pris_translations')->truncate();
                DB::table('pris')->truncate();
                DB::table('tag')->truncate();
                DB::table('tag_translations')->truncate();
                //
                //DB::table('files')->truncate();
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

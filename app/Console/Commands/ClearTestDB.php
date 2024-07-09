<?php

namespace App\Console\Commands;

use App\Models\AdvisoryBoard;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\FormInput;
use App\Models\LegislativeInitiative;
use App\Models\OgpPlan;
use App\Models\Page;
use App\Models\Poll;
use App\Models\Pris;
use App\Models\Publication;
use App\Models\StrategicDocument;
use App\Models\User;
use App\Models\UserCertificate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearTestDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clear_test_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all test data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //=========================
        //Users
        //=========================
        //Remove admins roles
        $usersByMailToExternalRole = ['joro.penchev@gmail.com'];
        if(sizeof($usersByMailToExternalRole)){
            $uExternal = User::whereIn('email', $usersByMailToExternalRole)->get();
            if($uExternal->count()){
                foreach ($uExternal as $u){
                    $u->syncRoles(CustomRole::EXTERNAL_USER_ROLE);
                }
            }
        }

        //Delete Users
        $usersByMailToDelete = [
            'isivanov.ams@gmail.com',
            'iivanov2@abv.bg',
            'iivanov@abv.bg',
            'gehennas@abv.bg',
        ];
        if(sizeof($usersByMailToDelete)){
            $uDelete = User::whereIn('email', $usersByMailToDelete)->get();
            if($uDelete->count()){
                foreach ($uDelete as $row){
                    DB::statement('update users set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
                }
            }
        }

        //Delete ASAP Users
        $excludeAsapUsers = ['admin@asap.bg', 'service-user@asap.bg', 'sanctum@asap.bg'];
        $uAsapDelete = User::whereRaw('email ilike \'%asap%\'')->whereNull('old_id')->whereNotIn('email', $excludeAsapUsers)->get();
        if($uAsapDelete->count()){
            foreach ($uAsapDelete as $row){
                DB::statement('update users set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
        }

        //=========================
        //Subscriptions
        //=========================
        $deletedSUsers = User::onlyTrashed()->get();
        if($deletedSUsers->count()){
            $this->info('Start delete Subscriptions');
            foreach ($deletedSUsers as $u){
                if($u->subscriptions->count()){
                    foreach ($u->subscriptions as $row){
                        DB::statement('update user_subscribes set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
                    }
                }
            }
            $this->info('End delete Subscriptions');
        }

        //=========================
        //Certificates
        //=========================
        $deletedUsers = User::onlyTrashed()->get();
        if($deletedUsers->count()){
            $this->info('Start delete Certificates');
            foreach ($deletedUsers as $u){
                if($u->certificates->count()){
                    foreach ($u->certificates as $row){
                        DB::statement('update user_certificate set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
                    }
                }
            }
            $this->info('End delete Certificates');
        }

        //=========================
        //Public Consultations
        //=========================

        $pcDelete = PublicConsultation::whereNull('old_id')->get();
        if($pcDelete->count()){
            $this->info('Start delete Public Consultations');
            foreach ($pcDelete as $row){
                DB::statement('update public_consultation set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete Public Consultations');
        }

        //=========================
        //Legislative initiatives
        //=========================
        $liRows = LegislativeInitiative::get();
        if($liRows->count()){
            $this->info('Start delete Legislative initiatives');
            foreach ($liRows as $row){
                DB::statement('update legislative_initiative set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete Legislative initiatives');
        }

        //=========================
        //Legislative programs
        //=========================
        //=========================
        //Operational programs
        //=========================
        //=========================
        //Pris - Delete all not imported
        //=========================
        $pris = Pris::whereNull('old_id')->get();
        if($pris->count()){
            $this->info('Start delete Pris');
            foreach ($pris as $row){
                DB::statement('update pris set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete Pris');
        }
        //=========================
        //Advisory boards
        //=========================
        $advBoardIds = [604,2106, 2107, 2114, 2115, 2103, 2104, 2105, 2120, 2119, 2108];
        $advBoards = AdvisoryBoard::whereIn('id', $advBoardIds)->get();
        if($advBoards->count()){
            $this->info('Start delete Advisory boards');
            foreach ($advBoards as $row){
                DB::statement('update advisory_boards set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete Advisory boards');
        }
        //=========================
        //Strategic documents
        //=========================
        $sdDelete = StrategicDocument::whereNull('old_id')->get();
        if($sdDelete->count()){
            $this->info('Start delete Strategic documents');
            foreach ($sdDelete as $row){
                DB::statement('update strategic_document set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete Strategic documents');
        }
        //=========================
        //Polls
        //=========================
        $pollRows = Poll::get();
        if($pollRows->count()){
            $this->info('Start delete Polls');
            foreach ($pollRows as $row){
                DB::statement('update poll set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete Polls');
        }
        //=========================
        //Publications
        //=========================
        $newsIds = [24329, 24328, 21268, 21267, 21266, 21265, 21264, 21263, 21262, 21215, 21214];
        $news = Publication::whereIn('id', $newsIds)->get();
        if($news->count()){
            $this->info('Start delete Publications');
            foreach ($news as $row){
                DB::statement('update publication set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete Publications');
        }
        //=========================
        //Impact assessments
        //=========================
        $iaRows = FormInput::get();
        if($iaRows->count()){
            $this->info('Start delete Impact assessments');
            foreach ($iaRows as $row){
                DB::statement('update form_input set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete Impact assessments');
        }
        //=========================
        //OGP plans
        //=========================
        //Developed plans
        $devPlanIds = [14,15];
        $devPlans = OgpPlan::whereIn('id', $devPlanIds)->where('national_plan', '=', 0)->get();
        if($devPlans->count()){
            $this->info('Start delete Developed plans');
            foreach ($devPlans as $row){
                DB::statement('update ogp_plan set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete Developed plans');
        }

        //National plans
        $nationalPlanIds = [7,20];
        $nationalPlans = OgpPlan::whereIn('id', $nationalPlanIds)->where('national_plan', '=', 1)->get();
        if($nationalPlans->count()){
            $this->info('Start delete National plans');
            foreach ($nationalPlans as $row){
                DB::statement('update ogp_plan set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete National plans');
        }

        //=========================
        //Static page
        //=========================
        $pageIds = [1,15,17,18,20,21,22];
        $pages = Page::whereIn('id', $pageIds)->get();
        if($pages->count()){
            $this->info('Start delete Static pages');
            foreach ($pages as $row){
                DB::statement('update page set deleted_at = \''.date('Y-m-d H:i:s').'\' where deleted_at is null and id = '.$row->id);
            }
            $this->info('End delete Static pages');
        }


        return Command::SUCCESS;
    }
}

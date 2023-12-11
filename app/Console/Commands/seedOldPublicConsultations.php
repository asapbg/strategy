<?php

namespace App\Console\Commands;

use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class seedOldPublicConsultations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:pc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old Strategy public consultations to application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        DB::table('public_consultation_connection')->truncate();
//        DB::table('public_consultation_contact')->truncate();
//        DB::table('public_consultation_translations')->truncate();
//        DB::table('public_consultation')->truncate();

        $locales = config('available_languages');
        $formatTimestamp = 'Y-m-d H:i:s';
        $formatDate = 'Y-m-d';

        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('old_strategy')->select('select max(publicconsultations.id) from publicconsultations');
        //start from this id in old database
        $currentStep = (int)DB::table('public_consultation')->select(DB::raw('max(old_id) as max'))->first()->max + 1;

        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;
            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.PHP_EOL;
                $oldDbResult = DB::connection('old_strategy')
                    ->select('select
                        pc.id as old_id,
                        -- consultation_level_id
                        -- act_type_id
                        -- legislative_program_id
                        -- operational_program_id
                        pc.openningdate as open_from,
                        pc.closingdate as open_to,
                        -- importer_institution_id
                        -- responsible_institution_id
                        -- responsible_institution_address
                        case when pc.isactive = true then 1 else 0 end as active,
                        case when pc.isdeleted = true then CURRENT_TIMESTAMP else null end as deleted_at,
                        pc.datecreated as created_at,
                        pc.datemodified as updated_at,
                        -- reg_num
                        -- monitorstat ????
                        -- operational_program_row_id
                        -- legislative_program_row_id
                        pc.categoryid as field_of_actions_id,
                        -- law_id
                        -- pris_id
                        -- translation
                        pc.title
                        -- description
                        -- short_term_reason
                        -- short_term_reason
                        -- responsible_unit
                        -- importer
                    from dbo.publicconsultations pc
                        where pc.languageid = 1
                    order by pc.id ');

                if (sizeof($oldDbResult)) {
                    DB::beginTransaction();
                    try {
                        foreach ($oldDbResult as $item) {
                            $comments = [];
                            $prepareNewPc = [
                                'old_id' => $item->old_id,
                                'consultation_level_id' => null, //TODO get level ?????????????
                                'act_type_id' => null, //TODO get act type ?????????????
                                'legislative_program_id' => null,
                                'operational_program_id' => null,
                                'open_from' => !empty($item->open_from) ? Carbon::parse($item->open_from)->format($formatTimestamp) : null,
                                'open_to' => !empty($item->open_to) ? Carbon::parse($item->open_to)->format($formatTimestamp) : null,
                                'importer_institution_id' => null, //TODO get institution ????????????? we can get this by author if we receive a mapping for user institution to IISDA
                                'responsible_institution_id' => null, //TODO get institution ????????????? we can get this by author if we receive a mapping for user institution to IISDA
                                'active' => $item->active,
                                'deleted_at' => !empty($item->deleted_at) ? Carbon::parse($item->deleted_at)->format($formatTimestamp) : null,
                                'created_at' => !empty($item->created_at) ? Carbon::parse($item->created_at)->format($formatTimestamp) : null,
                                'updated_at' => !empty($item->updated_at) ? Carbon::parse($item->updated_at)->format($formatTimestamp) : null,
                                'reg_num' => null,
                                'monitorstat' => null,
                                'operational_program_row_id' => null,
                                'legislative_program_row_id' => null,
                                'field_of_actions_id' => $item->field_of_actions_id,
                                'law_id' => null,
                                'pris_id' => null,
                                'title' => $item->title
                            ];

                            $newPc = new PublicConsultation();
                            $newPc->fill($prepareNewPc);
                            $newPc->save();
                            if($newPc) {
                                $newPc->reg_num = $newPc->id.'-K';
                                foreach ($locales as $locale) {
                                    $newPc->translateOrNew($locale['code'])->title = $prepareNewPc['title'];
                                }
                                $newPc->save();

                            //TODO add comments Try to get comments with publication
                                $oldDbComments = DB::connection('old_strategy')
                                    ->select('select
                                        pcomments.createdbyuserid as user_id,
                                        pcomments.title || \'\n\' || pcomments."text" as content,
                                        pcomments.consultationid as object_id,
                                        pcomments.datecreated as created_at,
                                        case when pcomments.isdeleted = true then CURRENT_TIMESTAMP else null end as deleted_at,
                                        case when pcomments.isactive = true then 1 else 0 end as active,
                                        case when pcomments.isapproved  = true then 1 else 0 end as approved
                                    from dbo.publicconsultationcomments pcomments
                                    where pcomments.consultationid = '.$newPc->id.'
                                    order by pcomments.datecreated asc');

                                if(sizeof($oldDbComments)) {
                                    foreach ($oldDbComments as $c) {
                                        $user = User::where('old_id', '=', $c->user_id)->first();
                                        $newComment = Comments::create([
                                            'user_id' => $user ? $user->id : null,
                                            'content' => $c->content,
                                            'object_code' => Comments::PC_OBJ_CODE,
                                            'object_id' => $c->object_id,
                                            'created_at' => $c->created_at,
                                            'deleted_at' => $c->deleted_at,
                                            'active' => $c->active,
                                            'approved' => $c->approved
                                        ]);
                                        $comments[] = $newComment;
                                    }
                                }
                            }
                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::error('Migration old startegy users: ' . $e);
                        DB::rollBack();
                        dd($prepareNewPc, $comments);
                    }
                }
                $currentStep += $step;
            }
        }

        Artisan::call('db:seed UsersSeeder');
        Artisan::call('db:seed UsersAZSeeder');
    }
}

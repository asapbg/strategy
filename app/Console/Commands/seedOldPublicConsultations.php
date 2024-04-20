<?php

namespace App\Console\Commands;

use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\FieldOfAction;
use App\Models\InstitutionLevel;
use App\Models\StrategicDocuments\Institution;
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
        $this->info('Start at '.date('Y-m-d H:i:s'));
        $locales = config('available_languages');
        $formatTimestamp = 'Y-m-d H:i:s';
        $formatDate = 'Y-m-d';

        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('old_strategy_app')->select('select max(dbo.publicconsultations.id) from dbo.publicconsultations');
        //start from this id in old database
//        $currentStep = (int)DB::table('public_consultation')->select(DB::raw('max(old_id) as max'))->first()->max + 1;
        $currentStep = 0;

        $ourPc = PublicConsultation::whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();
        $fieldOfActionsDBArea = FieldOfAction::withTrashed()->with('translations')->get();
        $fieldOfActions = array();
        if($fieldOfActionsDBArea->count()){
            foreach ($fieldOfActionsDBArea as $p){
                $fieldOfActions[mb_strtolower($p->translate('bg')->name)] = $p->id;
            }
        }

        //Create default institution
        $diEmail = 'magdalena.mitkova+egov@asap.bg';
        $dInstitution = Institution::where('email', '=', $diEmail)->withTrashed()->first();
        if(!$dInstitution) {
            $insLevel = InstitutionLevel::create([
                'system_name' => 'default'
            ]);
            if(!$insLevel) {
                $this->error('Cant create default institution');
            }
            if($insLevel) {
                foreach ($locales as $locale) {
                    $insLevel->translateOrNew($locale['code'])->name = 'Default Level';
                }
                $insLevel->save();
            }

            $dInstitution = Institution::create([
                'email' => $diEmail,
                'institution_level_id' => $insLevel->id
            ]);

            if(!$dInstitution) {
                $this->error('Cant create default institution');
            }
            foreach ($locales as $locale) {
                $dInstitution->translateOrNew($locale['code'])->name = 'Default';
            }
            $dInstitution->save();
        }

        $ourUsersInstitutions = User::withTrashed()->get()->pluck('institution_id', 'old_id')->toArray();
        $ourUsers = User::withTrashed()->where('email', 'not like', '%duplicated-%')->whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();

        //$ourInstitutions = Institution::withTrashed()->with(['level'])->get()->pluck('level.nomenclature_level', 'id')->toArray();

        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;
            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.PHP_EOL;
                $oldDbResult = DB::connection('old_strategy_app')
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
                        c.categoryname as field_of_actions_name,
                        -- law_id
                        -- pris_id
                        -- translation
                        pc.title,
                        -- description
                        -- short_term_reason
                        -- short_term_reason
                        -- responsible_unit
                        -- importer
                        pc.createdbyuserid as author_id,
                        pc.summary as description
                    from dbo.publicconsultations pc
                    left join dbo.categories c on c.id = pc.categoryid
                        where pc.languageid = 1
                        and pc.id >= ' . $currentStep . '
                        and pc.id < ' . ($currentStep + $step) . '
                    order by pc.id ');

                if (sizeof($oldDbResult)) {
                    file_put_contents('old_pc_field_of_actions', '');
                    foreach ($oldDbResult as $item) {
                        DB::beginTransaction();
                        try {
                            if(isset($ourPc[(int)$item->old_id])) {
                                $this->comment('Consultation with old id '.$item->old_id.' already exist');
                                $existPc = PublicConsultation::find($ourPc[(int)$item->old_id]);
                                if($existPc){
                                    if(isset($fieldOfActions) && sizeof($fieldOfActions) && isset($fieldOfActions[mb_strtolower($item->field_of_actions_name)])){
                                        if($existPc){
                                            $existPc->field_of_actions_id = (int)$fieldOfActions[mb_strtolower($item->field_of_actions_name)];
                                            $existPc->save();
                                        }
                                    } else {
                                        $existPc->field_of_actions_id = null;
                                        $existPc->save();
                                        //Collect not existing fields of actions or create mapping on fly
                                        file_put_contents('old_pc_field_of_actions', $item->field_of_actions_name.PHP_EOL, FILE_APPEND);
                                    }

                                    //Update institution
                                    $institutionId = $ourUsersInstitutions[$item->author_id] ?? $dInstitution->id;
                                    $institution = Institution::withTrashed()->find((int)$ourUsersInstitutions[$item->author_id]);
                                    //$institutionId = $dInstitution->id;
                                    //$institutionLevel = $ourInstitutions[$institutionId] > 0 ? $ourInstitutions[$institutionId] : ($dInstitution->level->nomenclature_level == 0 ? null : $dInstitution->level->nomenclature_level);
                                    $institutionLevel = $institution ? ($institution->level->nomenclature_level == 0 ? null : $institution->level->nomenclature_level) : null;
                                    $existPc->importer_institution_id = $institutionId;
                                    $existPc->responsible_institution_id = $institutionId;
                                    $existPc->consultation_level_id = $institutionLevel;
                                    $existPc->save();
                                }
                                DB::commit();
                                continue;
                            }

                            $institutionId = $ourUsersInstitutions[$item->author_id] ?? $dInstitution->id;
                            $institution = Institution::withTrashed()->find((int)$ourUsersInstitutions[$item->author_id]);
                            //$institutionId = $dInstitution->id;
                            //$institutionLevel = $ourInstitutions[$institutionId] > 0 ? $ourInstitutions[$institutionId] : ($dInstitution->level->nomenclature_level == 0 ? null : $dInstitution->level->nomenclature_level);
                            $institutionLevel = $institution ? ($institution->level->nomenclature_level == 0 ? null : $institution->level->nomenclature_level) : null;

                            $prepareNewPc = [
                                'old_id' => $item->old_id,
                                'consultation_level_id' => $institutionLevel,
                                'act_type_id' => null,
                                'legislative_program_id' => null,
                                'operational_program_id' => null,
                                'open_from' => !empty($item->open_from) ? Carbon::parse($item->open_from)->format($formatDate) : null,
                                'open_to' => !empty($item->open_to) ? Carbon::parse($item->open_to)->format($formatDate) : null,
                                'importer_institution_id' => $institutionId,
                                'responsible_institution_id' => $institutionId,
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
                                'title' => $item->title,
                                'description' => $item->description
                            ];

                            $newPc = new PublicConsultation();
                            $newPc->fill($prepareNewPc);
                            $newPc->save();

                            if($newPc) {
                                $comments = [];
                                $newPc->reg_num = $newPc->id.'-K';
                                foreach ($locales as $locale) {
                                    $newPc->translateOrNew($locale['code'])->title = $prepareNewPc['title'];
                                    $newPc->translateOrNew($locale['code'])->description = stripHtmlTags(html_entity_decode($prepareNewPc['description']));
                                }
                                $newPc->save();

                                $oldDbComments = DB::connection('old_strategy_app')
                                    ->select('select
                                        pcomments.createdbyuserid as user_id,
                                        pcomments.title || \'\n\' || pcomments."text" as content,
                                        pcomments.consultationid as object_id,
                                        pcomments.datecreated as created_at,
                                        case when pcomments.isdeleted = true then CURRENT_TIMESTAMP else null end as deleted_at,
                                        case when pcomments.isactive = true then 1 else 0 end as active,
                                        case when pcomments.isapproved  = true then 1 else 0 end as approved
                                    from dbo.publicconsultationcomments pcomments
                                    where pcomments.consultationid = '.$item->old_id.'
                                    order by pcomments.datecreated asc');

                                if(sizeof($oldDbComments)) {
                                    foreach ($oldDbComments as $c) {
                                        $content = str_replace('&quot;', '"', $c->content);
                                        $content = str_replace('\n', '<br>', $content);
                                        $newComment = Comments::create([
                                            'user_id' => $ourUsers[$c->user_id] ?? null,
                                            'content' => $content,
                                            'object_code' => Comments::PC_OBJ_CODE,
                                            'object_id' => $newPc->id,
                                            'created_at' => $c->created_at,
                                            'deleted_at' => $c->deleted_at,
                                            'active' => $c->active,
                                            'approved' => $c->approved
                                        ]);
                                        $comments[] = $newComment;
                                    }
                                }
                                //TODO migrate files
                                $this->comment('Finish import of public consultation with old ID '.$item->old_id);
                            }
                            DB::commit();
                        } catch (\Exception $e) {
                            Log::error('Migration old startegy public consultations, comment and files: ' . $e);
                            DB::rollBack();
                            //dd($prepareNewPc, $comments ?? []);
                        }

                    }
                }
                $currentStep += $step;
            }
        }
        $this->info('End at '.date('Y-m-d H:i:s'));
    }
}

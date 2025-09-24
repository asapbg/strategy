<?php

namespace App\Console\Commands;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\ActType;
use App\Models\Comments;
use App\Models\Consultations\PublicConsultation;
use App\Models\FieldOfAction;
use App\Models\InstitutionLevel;
use App\Models\StrategicDocuments\Institution;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        file_put_contents('old_pc_field_of_actions', '');
        activity()->disableLogging();
        $this->info('Start at ' . date('Y-m-d H:i:s'));
        $locales = config('available_languages');
        $formatTimestamp = 'Y-m-d H:i:s';
        $formatDate = 'Y-m-d';

        $ourPc = PublicConsultation::withTrashed()->whereNotNull('old_id')->orderBy('old_id')->get()->pluck('id', 'old_id')->toArray();

        $fieldOfActionsDBArea = FieldOfAction::withTrashed()->Area()->with('translations')->get();
        $fieldOfActionsArea = array();
        if ($fieldOfActionsDBArea->count()) {
            foreach ($fieldOfActionsDBArea as $p) {
                $fieldOfActionsArea[mb_strtolower($p->translate('bg')->name)] = $p->id;
            }
        }

        $fieldOfActionsDBMunicipal = FieldOfAction::withTrashed()->Municipal()->with('translations')->get();
        $fieldOfActionsMunicipal = array();
        if ($fieldOfActionsDBMunicipal->count()) {
            foreach ($fieldOfActionsDBMunicipal as $p) {
                $fieldOfActionsMunicipal[mb_strtolower($p->translate('bg')->name)] = $p->id;
            }
        }

        $fieldOfActionsDBNational = FieldOfAction::withTrashed()->Central()->with('translations')->get();
        $fieldOfActionsNational = array();
        if ($fieldOfActionsDBNational->count()) {
            foreach ($fieldOfActionsDBNational as $p) {
                $fieldOfActionsNational[mb_strtolower($p->translate('bg')->name)] = $p->id;
            }
        }

        //Create default institution
        $diEmail = 'magdalena.mitkova+egov@asap.bg';
        $dInstitution = Institution::where('email', '=', $diEmail)->withTrashed()->first();
        if (!$dInstitution) {
            $insLevel = InstitutionLevel::create([
                'system_name' => 'default'
            ]);
            if (!$insLevel) {
                $this->error('Cant create default institution');
            }
            if ($insLevel) {
                foreach ($locales as $locale) {
                    $insLevel->translateOrNew($locale['code'])->name = 'Default Level';
                }
                $insLevel->save();
            }

            $dInstitution = Institution::create([
                'email' => $diEmail,
                'institution_level_id' => $insLevel->id
            ]);

            if (!$dInstitution) {
                $this->error('Cant create default institution');
            }
            foreach ($locales as $locale) {
                $dInstitution->translateOrNew($locale['code'])->name = 'Default';
            }
            $dInstitution->save();
        }

        $ourUsersIds = User::withTrashed()->where('email', 'not like', '%duplicated-%')->get()->pluck('id', 'old_id')->toArray();
        $ourUsersInstitutions = User::withTrashed()->where('email', 'not like', '%duplicated-%')->get()->pluck('institution_id', 'old_id')->toArray();
        $ourUsersInstitutionsByMail = User::withTrashed()->where('user_type', '=', 1)
            ->where('email', 'not like', '%duplicated-%')
            ->get()->pluck('institution_id', 'email')->toArray();
        $ourUsers = User::withTrashed()->where('email', 'not like', '%duplicated-%')->whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();

        //$ourInstitutions = Institution::withTrashed()->with(['level'])->get()->pluck('level.nomenclature_level', 'id')->toArray();

        $step = 50;
        $maxOldId = DB::connection('old_strategy_app')->select('select max(dbo.publicconsultations.id) from dbo.publicconsultations');
        $currentStep = 0;
        if (!(int)$maxOldId[0]->max) {
            $this->error('No records found in old database');
            return Command::FAILURE;
        }

        DB::beginTransaction();
        try {
            $stop = false;
            $maxOldId = (int)$maxOldId[0]->max;
            //$maxOldId = 50;
            while ($currentStep <= $maxOldId && !$stop) {
                //$this->comment("Current step: $currentStep");
                $central = InstitutionCategoryLevelEnum::CENTRAL->value;
                $area = InstitutionCategoryLevelEnum::AREA->value;
                $municipal = InstitutionCategoryLevelEnum::MUNICIPAL->value;

                $oldDbResult = DB::connection('old_strategy_app')
                    ->select("
                        select
                            pc.id as old_id,
                            pc.openningdate as open_from,
                            pc.closingdate as open_to,
                            case when pc.isactive = true then 1 else 0 end as active,
                            case when pc.isdeleted = true then CURRENT_TIMESTAMP else null end as deleted_at,
                            pc.datecreated as created_at,
                            pc.datemodified as updated_at,
                            pc.categoryid as field_of_actions_id,
                            c.categoryname as field_of_actions_name,
                            (
                                case
                                    when c.parentid = 1 then $central
                                    else (
                                        case
                                            when c.parentid = 2 then $area
                                            else (
                                                case
                                                    when c.parentid = 3 then $municipal
                                                    else null
                                                end
                                            )
                                        end
                                    )
                                end
                            ) as consultation_level_id,
                            pc.title,
                            pc.createdbyuserid as author_id,
                            m.email,
                            pc.summary as description
                        from dbo.publicconsultations pc
                        left join dbo.users u on u.userid = pc.createdbyuserid
                        left join dbo.membership m on m.userid = u.userid
                        left join dbo.categories c on c.id = pc.categoryid
                        where pc.languageid = 1
                              --and pc.id = 177
                              and pc.id >= $currentStep
                              and pc.id < ".($currentStep + $step)."
                        order by pc.id
                ");

                if (sizeof($oldDbResult)) {
                    foreach ($oldDbResult as $result) {

                        $fieldOfAct = null;

                        if ((int)$result->consultation_level_id == InstitutionCategoryLevelEnum::CENTRAL->value) {

                            $fieldOfAct = (
                                isset($fieldOfActionsNational) && sizeof($fieldOfActionsNational)
                                && isset($fieldOfActionsNational[mb_strtolower($result->field_of_actions_name)])
                            )
                                ? (int)$fieldOfActionsNational[mb_strtolower($result->field_of_actions_name)]
                                : null;

                        } elseif ((int)$result->consultation_level_id == InstitutionCategoryLevelEnum::AREA->value) {

                            $fieldOfAct = (
                                isset($fieldOfActionsArea) && sizeof($fieldOfActionsArea) && isset($fieldOfActionsArea[mb_strtolower($result->field_of_actions_name)])
                            )
                                ? (int)$fieldOfActionsArea[mb_strtolower($result->field_of_actions_name)]
                                : null;

                        } elseif ((int)$result->consultation_level_id == InstitutionCategoryLevelEnum::MUNICIPAL->value) {

                            $fieldOfAct = (
                                isset($fieldOfActionsMunicipal) && sizeof($fieldOfActionsMunicipal)
                                && isset($fieldOfActionsMunicipal[mb_strtolower($result->field_of_actions_name)])
                            )
                                ? (int)$fieldOfActionsMunicipal[mb_strtolower($result->field_of_actions_name)]
                                : null;

                        }

                        if (!$fieldOfAct) {
                            //Collect not existing fields of actions or create mapping on fly
                            file_put_contents('old_pc_field_of_actions', $result->field_of_actions_name . PHP_EOL, FILE_APPEND);
                        }

                        if (isset($ourUsersInstitutions[$result->author_id])) {
                            $institutionId = $ourUsersInstitutions[$result->author_id] ?? $dInstitution->id;
                        } else if (isset($ourUsersInstitutionsByMail[$result->email])) {
                            $institutionId = $ourUsersInstitutionsByMail[$result->email];
                        } else {
                            $institutionId = $dInstitution->id;
                        }
                        //dd($result->author_id, $result->email, $institutionId);
                        //$institution = Institution::withTrashed()->find($institutionId);
                        //$institutionLevel = $institution ? ($institution->level->nomenclature_level == 0 ? null : $institution->level->nomenclature_level) : null;

                        if (isset($ourUsersIds[$result->author_id])) {
                            $author = (int)$ourUsersIds[$result->author_id];
                        } else {
                            $author = null;
                        }

                        $actType = $this->getActType($result);

                        if ($result->old_id == 120) {
                            //dd($result,$currentStep);
                        }
                        if (isset($ourPc[(int)$result->old_id])) {
                            $this->comment('Consultation with old id ' . $result->old_id . ' already exist');
                            $existPc = PublicConsultation::withTrashed()->find($ourPc[(int)$result->old_id]);

                            if ($existPc) {
                                $existPc->importer_institution_id = $institutionId;
                                $existPc->responsible_institution_id = $institutionId;
                                $existPc->deleted_at = !empty($result->deleted_at) ? Carbon::parse($result->deleted_at)->format($formatTimestamp) : null;
                                $existPc->updated_at = !empty($result->updated_at) ? Carbon::parse($result->updated_at)->format($formatTimestamp) : null;
                                $existPc->consultation_level_id = $result->consultation_level_id;
                                $existPc->open_from = !empty($result->open_from) ? Carbon::parse($result->open_from)->format($formatDate) : null;
                                $existPc->open_to = !empty($result->open_to) ? Carbon::parse($result->open_to)->format($formatDate) : null;
                                $existPc->active = $result->active;
                                $existPc->field_of_actions_id = (int)$fieldOfAct;
                                $existPc->act_type_id = $actType;
                                $existPc->user_id = $author;
                                $existPc->save();

                                foreach ($locales as $locale) {
                                    $existPc->translateOrNew($locale['code'])->title = $result->title;
                                    $existPc->translateOrNew($locale['code'])->description = stripHtmlTags(html_entity_decode($result->description));
                                }
                                $existPc->save();
                                PublicConsultation::withTrashed()->where('old_id', '=', $existPc->old_id)->where('id', '<>', $existPc->id)->update(['old_id' => null]);
                            }
                            continue;
                        }

                        $prepareNewPc = [
                            'old_id' => $result->old_id,
                            'consultation_level_id' => $result->consultation_level_id,
                            'act_type_id' => $actType,
                            'legislative_program_id' => null,
                            'operational_program_id' => null,
                            'open_from' => !empty($result->open_from) ? Carbon::parse($result->open_from)->format($formatDate) : null,
                            'open_to' => !empty($result->open_to) ? Carbon::parse($result->open_to)->format($formatDate) : null,
                            'importer_institution_id' => $institutionId,
                            'responsible_institution_id' => $institutionId,
                            'active' => $result->active,
                            'deleted_at' => !empty($result->deleted_at) ? Carbon::parse($result->deleted_at)->format($formatTimestamp) : null,
                            'created_at' => !empty($result->created_at) ? Carbon::parse($result->created_at)->format($formatTimestamp) : null,
                            'updated_at' => !empty($result->updated_at) ? Carbon::parse($result->updated_at)->format($formatTimestamp) : null,
                            'reg_num' => null,
                            'monitorstat' => null,
                            'operational_program_row_id' => null,
                            'legislative_program_row_id' => null,
                            'field_of_actions_id' => (int)$fieldOfAct,
                            'law_id' => null,
                            'pris_id' => null,
                            'title' => $result->title,
                            'description' => $result->description,
                            'user_id' => $author
                        ];

                        $newPc = new PublicConsultation();
                        $newPc->fill($prepareNewPc);
                        $newPc->save();

                        if ($newPc) {
                            $comments = [];
                            $newPc->reg_num = $newPc->id . '-K';
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
                            where pcomments.consultationid = ' . $result->old_id . '
                            order by pcomments.datecreated asc');

                            if (sizeof($oldDbComments)) {
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
                            $this->comment('Finish import of public consultation with old ID ' . $result->old_id);
                        }
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
            DB::commit();
        } catch (\Exception $e) {
            $this->comment("Error while migrating consultation with old ID $result->old_id. Error: "  . $e->getMessage());
            Log::error('Migration of old strategy public consultations, comment and files: ' . $e->getMessage());
            DB::rollBack();
        }
        $this->info('End at ' . date('Y-m-d H:i:s'));
    }

    /**
     * @param $result
     * @return int|null
     */
    private function getActType($result): int|null
    {
        $actType = null;
        if (!empty($result->title)) {
            if (str_contains($result->title, 'Постановление на Министерския съвет')) {
                $actType = ActType::ACT_COUNCIL_OF_MINISTERS;
            } elseif (str_contains($result->title, 'Решение на Министерския съвет')) {
                $actType = ActType::ACT_COUNCIL_OF_MINISTERS;
            } elseif (str_contains($result->title, 'Рамкова позиция')) {
                $actType = ActType::ACT_FRAME_POSITION;
            } elseif (str_contains($result->title, 'Правилник')) {
                $actType = ActType::ACT_NON_NORMATIVE_COUNCIL_OF_MINISTERS;
            } elseif (str_contains($result->title, 'Наредба')) {
                $actType = ActType::ACT_MINISTER;
            } elseif (str_contains($result->title, 'Закон')) {
                $actType = ActType::ACT_LAW;
            }
        }
        return $actType;
    }
}

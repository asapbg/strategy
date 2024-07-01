<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\FieldOfAction;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdvisoryBoardController extends ApiController
{
    public function list(Request $request){

        if(isset($this->request_inputs['active'])){
            $active = (bool)$this->request_inputs['active'];
        }
        if(isset($this->request_inputs['npo-representative'])){
            $hasNpo = (bool)$this->request_inputs['npo-representative'];
        }

        if(isset($this->request_inputs['policy-area']) && !empty($this->request_inputs['policy-area'])){
            $policyAreasIds = $this->request_inputs['policy-area'];
        }

        if(isset($this->request_inputs['institution-id']) && !empty($this->request_inputs['institution-id'])){
            $institutions = explode(',', $this->request_inputs['institution-id']);
            $fa = FieldOfAction::whereHas('institution', function($q) use($institutions){
                $q->whereIn('id', $institutions);
            })->get()->pluck('id');
            if(sizeof($fa)){
                if(isset($policyAreasIds)){
                    $policyAreasIds .= implode(',', $fa);
                } else{
                    $policyAreasIds = implode(',', $fa);
                }
            }
        }

        if(isset($this->request_inputs['date-established-after']) && !empty($this->request_inputs['date-established-after'])){
            if(!$this->checkDate($this->request_inputs['date-established-after'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-established-after\'');
            }
            $from = Carbon::parse($this->request_inputs['date-established-after'])->format('Y-m-d');
        }

        if(isset($this->request_inputs['date-established-before']) && !empty($this->request_inputs['date-established-before'])){
            if(!$this->checkDate($this->request_inputs['date-established-before'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-established-before\'');
            }
            $to = Carbon::parse($this->request_inputs['date-established-before'])->format('Y-m-d');
        }

        $data = DB::select('
                    select
                        ab.id as id,
                        max(abt.name) as title,
                        max(aabt."name") as establishment_act_type,
                        max(actt.name) as chairman_type,
                        json_agg(distinct(ifoa.institution_id)) filter(where ifoa.institution_id is not null) as institutions_id,
                        ab.created_at::date as date_established,
                        ab.meetings_per_year as meetings_year,
                        ab.has_npo_presence as npo_representative
                    from advisory_boards ab
                    join advisory_board_translations abt on abt.advisory_board_id = ab.id and abt.locale = \''.$this->locale.'\'
                    left join authority_advisory_board aab on aab.id = ab.authority_id
                    left join authority_advisory_board_translations aabt on aabt.authority_advisory_board_id = aab.id and aabt.locale = \''.$this->locale.'\'
                    left join advisory_chairman_type act on act.id = ab.advisory_chairman_type_id
                    left join advisory_chairman_type_translations actt on actt.advisory_chairman_type_id = act.id and actt.locale = \''.$this->locale.'\'
                    left join field_of_actions foa2 on foa2.id = ab.policy_area_id
                    left join institution_field_of_action ifoa on ifoa.field_of_action_id = foa2.id
                    where true
                        '.(!$this->authanticated ? ' and ab.deleted_at is null and ab.public = true ' : '').'
                        '.(isset($active) ? ' and ab.active = '.($active ? 'true' : 'false') : '').'
                        '.(isset($hasNpo) ? ' and ab.has_npo_presence = '.($hasNpo ? 'true' : 'false') : '').'
                        '.(isset($from) ? ' and ab.created_at >= \''.$from.' 00:00:00'.'\'' : '').'
                        '.(isset($to) ? ' and ab.created_at <= \''.$to.' 23:59:59'.'\'' : '').'
                        '.(isset($policyAreasIds) && !empty($policyAreasIds)? ' and foa2.id in ('.$policyAreasIds.')' : '').'
                    group by ab.id
                    '.($this->request_limit ? ' limit '.$this->request_limit : '').'
                    '.($this->request_offset ? ' offset '.$this->request_offset : '').'
                ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->institutions_id)){
                    $row->institutions_id = json_decode($row->institutions_id, true);
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;

        return $this->output($data);
    }

    public function show(Request $request, $id = 0)
    {
        $data = DB::select('
                    select
                        ab.id,
                        max(abt.name) as name,
                        ab.created_at::date as date_established,
                        max(aabt."name") as establishment_act_type,
                        -- max(abort2.description) as establishment_act,
                        (
                            select jsonb_build_object(\'description\', max(abet.description), \'links\', A.files)
                            from (
                                select jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id) filter (where f.id is not null) as files
                                from files f
                                where
                                    f.deleted_at is null
                                and f.id_object = max(abe.id)
                                and f.doc_type = '.DocTypesEnum::AB_ESTABLISHMENT_RULES->value.'
                                and f.code_object = '.File::CODE_AB.'
                                and f.locale = \''.$this->locale.'\'
                            ) A
                        ) as establishment_act,
                         -- max(abort2.description) as rules_guide,
                        (
                        select jsonb_build_object(\'description\', max(abort2.description), \'links\', A.files)
                            from (
                                select jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id) filter (where f.id is not null) as files
                                from files f
                                where
                                    f.deleted_at is null
                                and f.id_object = max(abor.id)
                                and f.doc_type = '.DocTypesEnum::AB_ORGANIZATION_RULES->value.'
                                and f.code_object = '.File::CODE_AB.'
                            ) A
                        ) as rules_guide,
                        max(actt.name) as chairman_type,
                        (
                        select jsonb_agg(jsonb_build_object(ct.type_name, ct.members))
                            from (
                                select type_name, jsonb_agg(jsonb_build_object(\'name\', abmt.member_name , \'role\', abmt.member_job)) as members
                                from advisory_board_members abm
                                join advisory_board_member_translations abmt on abmt.advisory_board_member_id = abm.id and abmt.locale = \''.$this->locale.'\'
                                join (select type_id, type_name from (
                                    values ('.AdvisoryTypeEnum::CHAIRMAN->value.', \'chairmen\'),
                                        ('.AdvisoryTypeEnum::VICE_CHAIRMAN->value.', \'vice-chairmen\'),
                                        ('.AdvisoryTypeEnum::SECRETARY->value.', \'secretaries\'),
                                        ('.AdvisoryTypeEnum::MEMBER->value.', \'members\')
                                    ) E(type_id, type_name)) enums on enums.type_id = abm.advisory_type_id::int
                                where
                                    abm.deleted_at is null
                                and abm.advisory_board_id = ab.id
                                group by type_name
                            ) ct
                        ) as members,
                        ab.meetings_per_year as meetings_year,
                        ab.has_npo_presence as npo_representative,
                        max(abst.description) as secretariate_details,
                        (
                        select jsonb_agg(jsonb_build_object(\'name\', f.custom_name , \'date\', f.created_at::date, \'link\', \''.url('/').'\' || \'/download/\' || f.id)) as files
                                from files f
                                where
                                    f.id_object = max(abs2.id)
                                    and f.deleted_at is null
                                and f.locale = \''.$this->locale.'\'
                                and f.code_object = '.File::CODE_AB.'
                                and f.doc_type = '.DocTypesEnum::AB_SECRETARIAT->value.'
                        ) as secretariate_files,
                        (
                        jsonb_build_object(\'info\', max(abmit.description), \'files\', (
                        select jsonb_agg(jsonb_build_object(\'name\', f.custom_name , \'date\', f.created_at::date, \'link\', \''.url('/').'\' || \'/download/\' || f.id)) as files
                                from files f
                                where
                                    f.id_object = max(abmi.id)
                                    and f.deleted_at is null
                                and f.locale = \''.$this->locale.'\'
                                and f.code_object = '.File::CODE_OBJ_AB_MODERATOR.'
                                and f.doc_type = '.DocTypesEnum::AB_MODERATOR->value.'
                            ), \'persons\',
                                (
                                select jsonb_agg(case when u.first_name is not null then u.first_name else \'\' end || \' \' || case when u.last_name is not null then u.last_name else \'\' end)
                                    from advisory_board_moderators abm2
                                    join users u on u.id = abm2.user_id
                                    where
                                        abm2.deleted_at is null
                                and abm2.advisory_board_id = ab.id
                                )
                            )
                        ) as moderators,
                        (
                        select jsonb_agg(jsonb_build_object(\'id\', WP.id, \'year\', WP.working_year::date, \'description\',  WP.description, \'reports\', WP.files))
                            from (
                                select abf.id, max(abf.working_year) as working_year, max(abft.description) as description, jsonb_agg(\''.url('/').'\' || \'/download/\' || f2.id) filter (where f2.id is not null) as files
                                from advisory_board_functions abf
                                join advisory_board_function_translations abft on abft.advisory_board_function_id = abf.id and abft.locale = \''.$this->locale.'\'
                                left join files f2 on f2.id_object = abf.id
                                and f2.deleted_at is null
                                and f2.locale = \''.$this->locale.'\'
                                and f2.code_object = '.File::CODE_AB.'
                                and f2.doc_type = '.DocTypesEnum::AB_FUNCTION->value.'
                                where
                                    abf.deleted_at is null
                                and abf.advisory_board_id = ab.id
                                group by abf.id
                            ) WP
                        ) as work_program,
                        (
                        select jsonb_agg(jsonb_build_object(\'id\', M.id, \'date\', M.next_meeting::date, \'description\',  M.description, \'files\', M.files))
                            from (
                                select abm3.id, max(abm3.next_meeting) as next_meeting, max(abmt2.description) as description, jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id) filter (where f.id is not null) as files
                                from advisory_board_meetings abm3
                                join advisory_board_meeting_translations abmt2 on abmt2.advisory_board_meeting_id = abm3.id and abmt2.locale = \''.$this->locale.'\'
                                left join files f on f.id_object = abm3.id
                                and f.deleted_at is null
                                and f.locale = \''.$this->locale.'\'
                                and f.code_object = '.File::CODE_AB.'
                                and f.doc_type = '.DocTypesEnum::AB_MEETINGS_AND_DECISIONS->value.'
                                where
                                    abm3.deleted_at is null
                                and abm3.advisory_board_id = ab.id
                                group by abm3.id
                            ) M
                        ) as meetings,
                        (
                        select jsonb_agg(jsonb_build_object(\'id\', p.id, \'date\', p.published_at::date, \'title\',  pt.title , \'content\', pt."content"))
                            from "publication" p
                            join publication_translations pt on pt.publication_id = p.id and pt.locale = \''.$this->locale.'\'
                            where
                                p.deleted_at is null
                                and p.active = true
                                and p.published_at is not null
                                and p.advisory_boards_id = ab.id
                        ) as news
                    from advisory_boards ab
                    join advisory_board_translations abt on abt.advisory_board_id = ab.id and abt.locale = \''.$this->locale.'\'
                    left join field_of_actions foa2 on foa2.id = ab.policy_area_id
                    left join authority_advisory_board aab on aab.id = ab.authority_id
                    left join authority_advisory_board_translations aabt on aabt.authority_advisory_board_id = aab.id and aabt.locale = \''.$this->locale.'\'
                    left join advisory_board_establishments abe on abe.advisory_board_id = ab.id
                    left join advisory_board_establishment_translations abet on abet.advisory_board_establishment_id = abe.id and abet.locale = \''.$this->locale.'\'
                    left join advisory_board_organization_rules abor on abor.advisory_board_id = ab.id
                    left join advisory_board_organization_rule_translations abort2 on abort2.advisory_board_organization_rule_id = abor.id and abort2.locale = \''.$this->locale.'\'
                    left join advisory_chairman_type act on act.id = ab.advisory_chairman_type_id
                    left join advisory_chairman_type_translations actt on actt.advisory_chairman_type_id = act.id and actt.locale = \''.$this->locale.'\'
                    left join advisory_board_secretariats abs2 on abs2.advisory_board_id = ab.id and abs2.deleted_at is null
                    left join advisory_board_secretariat_translations abst on abst.advisory_board_secretariat_id = abs2.id and abst.locale = \''.$this->locale.'\'
                    left join advisory_board_moderator_information abmi on abmi.advisory_board_id = ab.id and abmi.deleted_at is null
                    left join advisory_board_moderator_information_translations abmit on abmit.advisory_board_moderator_information_id = abmi.id and abmit.locale = \''.$this->locale.'\'
                    where true
                        '.(!$this->authanticated ? ' and ab.deleted_at is null and ab.public = true ' : '').'
                        and ab.id = '.$id.'
                    group by ab.id
                ');

        if(sizeof($data)){
            $data = $data[0];
            if(!empty($data->establishment_act)){
                $data->establishment_act = json_decode($data->establishment_act, true);
                if (empty($data->establishment_act['links'])) {
                    $data->establishment_act['links'] = [];
                }
            }
            if(!empty($data->rules_guide)){
                $data->rules_guide = json_decode($data->rules_guide, true);
                if (empty($data->rules_guide['links'])) {
                    $data->rules_guide['links'] = [];
                }
            }
            if(!empty($data->members)){
                $data->members = json_decode($data->members, true);
            }
            if(!empty($data->secretariate_files)){
                $data->secretariate_files = json_decode($data->secretariate_files, true);
            } else{
                $data->secretariate_files = [];
            }
            if(!empty($data->moderators)){
                $data->moderators = json_decode($data->moderators, true);
                if (empty($data->moderators['files'])) {
                    $data->moderators['files'] = [];
                }
                if (empty($data->moderators['persons'])) {
                    $data->moderators['persons'] = [];
                }
            }
            if(!empty($data->work_program)){
                $data->work_program = json_decode($data->work_program, true);
                if(sizeof($data->work_program)){
                    foreach ($data->work_program as $k => $r) {
                        if (empty($r['reports'])) {
                            $data->work_program[$k]['reports'] = [];
                        }
                    }
                }
            } else{
                $data->work_program = [];
            }

            if(!empty($data->meetings)){
                $data->meetings = json_decode($data->meetings, true);
                if(sizeof($data->meetings)){
                    foreach ($data->meetings as $k => $r) {
                        if (empty($r['files'])) {
                            $data->meetings[$k]['files'] = [];
                        }
                    }
                }
            } else {
                $data->meetings = [];
            }
            if(!empty($data->news)){
                $data->news = json_decode($data->news, true);
            }
        }
        if(empty($data)){
            return $this->returnError(Response::HTTP_NOT_FOUND, 'Not found');
        }
        return $this->output($data);
    }

    public function meetings(Request $request, int $id = 0)
    {
        $data = DB::select('
            select abm3.id, max(abm3.next_meeting::date) as date, max(abmt2.description) as description, jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id) filter (where f.id is not null) as files
                from advisory_board_meetings abm3
                join advisory_boards ab on ab.id = abm3.advisory_board_id
                join advisory_board_meeting_translations abmt2 on abmt2.advisory_board_meeting_id = abm3.id and abmt2.locale = \''.$this->locale.'\'
                left join files f on f.id_object = abm3.id
                and f.deleted_at is null
                and f.locale = \''.$this->locale.'\'
                and f.code_object = '.File::CODE_AB.'
                and f.doc_type = '.DocTypesEnum::AB_MEETINGS_AND_DECISIONS->value.'
                where true
                    '.(!$this->authanticated ? ' and abm3.deleted_at is null and ab.deleted_at is null and ab.public = true ' : '').'
                    and abm3.advisory_board_id = '.$id.'
                group by abm3.id
                '.($this->request_limit ? ' limit '.$this->request_limit : '').'
                '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');
        return $this->output($data);
    }

    public function news(Request $request, int $id = 0)
    {
        $data = DB::select('
            select
                p.id,
                p.published_at::date as date,
                pt.title, pt."content"
            from "publication" p
            join publication_translations pt on pt.publication_id = p.id and pt.locale = \''.$this->locale.'\'
            join advisory_boards ab on ab.id = p.advisory_boards_id
            where true
                '.(!$this->authanticated ? ' and p.deleted_at is null and p.active = true and p.published_at is not null and ab.deleted_at is null and ab.public = true ' : '').'
                and p.advisory_boards_id = ab.id
                and ab.id = '.$id.'
                '.($this->request_limit ? ' limit '.$this->request_limit : '').'
                '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');
        return $this->output($data);
    }
}

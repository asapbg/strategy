<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LegislativeInitiativeController extends ApiController
{
    public function list(Request $request)
    {
        $from = $to = null;
        if(isset($this->request_inputs['date-after']) && !empty($this->request_inputs['date-after'])){
            if(!$this->checkDate($this->request_inputs['date-after'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-after\'');
            }
            $from = Carbon::parse($this->request_inputs['date-after'])->format('Y-m-d');
        }

        if(isset($this->request_inputs['datе-before']) && !empty($this->request_inputs['datе-before'])){
            if(!$this->checkDate($this->request_inputs['datе-before'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'datе-before\'');
            }
            $to = Carbon::parse($this->request_inputs['datе-before'])->format('Y-m-d');
        }

        $q = DB::table('legislative_initiative')
            ->select([
                DB::raw('\''.__('custom.change_f').' '.__('custom.in').'\' || \' \' || max(law_translations.name) as name'),
                DB::raw('case when max(users.id) is not null then max(users.first_name) || \' \' || max(users.last_name) else \'\' end as author_name'),
                DB::raw('legislative_initiative.created_at::date as date_open'),
                DB::raw('legislative_initiative.active_support::date as date_close'),
                DB::raw('case when legislative_initiative.status::int = '.LegislativeInitiativeStatusesEnum::STATUS_SEND->value.'
                        then \''.__('custom.legislative_' . \Illuminate\Support\Str::lower(LegislativeInitiativeStatusesEnum::STATUS_SEND->name)).'\'
                        else (
                            case when legislative_initiative.status::int = '.LegislativeInitiativeStatusesEnum::STATUS_CLOSED->value.'
                            then \''.__('custom.legislative_' . \Illuminate\Support\Str::lower(LegislativeInitiativeStatusesEnum::STATUS_CLOSED->name)).'\'
                            else \''.__('custom.legislative_' . \Illuminate\Support\Str::lower(LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->name)).'\' end
                        ) end as status'),
                DB::raw('legislative_initiative.description as description'),
                DB::raw('max(law_translations.name) as law_name'),
                DB::raw('legislative_initiative.law_paragraph as law_paragraph'),
                DB::raw('legislative_initiative.law_text as law_text_change'),
                DB::raw('legislative_initiative.motivation as motivation'),
                'legislative_initiative.cap',
                DB::raw('case when max(ric.id) is not null then true else false end as sent'),
                DB::raw('sum(case when legislative_initiative_votes.is_like = true then 1 else 0 end) as votes_for'),
                DB::raw('sum(case when legislative_initiative_votes.is_like = false then 1 else 0 end) as votes_against'),
                DB::raw('json_agg(ic_translation.name) filter (where ic_translation.name is not null) as institutions'),
                DB::raw('(
                            select
                                    json_agg(json_build_object(\'date\', date_part(\'year\', legislative_initiative_comments.created_at), \'author_name\', (case when u.id is not null then u.first_name || \' \' || u.last_name else \'\' end), \'text\', legislative_initiative_comments.description))
                                 from legislative_initiative_comments
                                 join users as u on u.id = legislative_initiative_comments.user_id
                                 where
                                    legislative_initiative_comments.legislative_initiative_id = legislative_initiative.id
                                    and legislative_initiative_comments.deleted_at is null
                             ) as comments'),

            ])
            ->leftJoin('users', 'users.id', '=', 'legislative_initiative.author_id')
            ->join('law', 'law.id', '=', 'legislative_initiative.law_id')
            ->join('law_translations', function ($q){
                $q->on('law_translations.law_id', '=', 'law.id')->where('law_translations.locale', '=', 'bg');
            })
            ->leftJoin('legislative_initiative_votes', 'legislative_initiative_id', '=', 'legislative_initiative.id')
            ->join('legislative_initiative_institution as ic', 'ic.legislative_initiative_id', '=', 'legislative_initiative.id')
            ->join('institution as ici', 'ici.id', '=', 'ic.institution_id')
            ->join('institution_translations as ic_translation', function ($q){
                $q->on('ic_translation.institution_id', '=', 'ici.id')->where('ic_translation.locale', '=', 'bg');
            })
            ->leftJoin('legislative_initiative_receiver as ric', 'ric.legislative_initiative_id', '=', 'legislative_initiative.id')
            ->leftJoin('institution as rici', 'rici.id', '=', 'ric.institution_id')
            ->leftJoin('institution_translations as ric_translation', function ($q){
                $q->on('ric_translation.institution_id', '=', 'rici.id')->where('ric_translation.locale', '=', 'bg');
            })
            ->whereNull('legislative_initiative.deleted_at')
            ->groupBy('legislative_initiative.id')
            ->orderBy('legislative_initiative.created_at', 'desc');

        $data = $q->get()->map(function ($row) {
            if(!empty($row->comments)){
                $row->comments = json_decode($row->comments, true);
            }
            return (array)$row;
        })->toArray();

        return $this->output($data);
    }

    public function show(Request $request, $id = 0)
    {
        $data = DB::select('
            select
                C.operational_program_id as id,
                max(C.date_from) as date_from,
                max(C.date_to) as date_to,
                (
                    select jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id)
                    from files f
                        where
                            f.id_object = C.operational_program_id
                            and f.deleted_at is null
                        and f.locale = \'bg\'
                        and f.code_object = '.File::CODE_OBJ_OPERATIONAL_PROGRAM_GENERAL.'
                ) as files,
                jsonb_agg(jsonb_build_object(C.month, C.records)) as program
            from (
                select
                    B.operational_program_id,
                    max(B.date_from) as date_from,
                    max(B.date_to) as date_to,
                    B.month,
                    jsonb_agg(B.records) as records
                from (
                    select
                        A.month,
                        A.row_num,
                        jsonb_object_agg(A."label", case when A.col = '.config('lp_op_programs.op_ds_col_institution_id').' then A.institutions else A.value end) as records,
                        A.operational_program_id,
                        max(A.from_date) as date_from,
                        max(A.to_date) as date_to
                    from (
                        select
                            oprm.month,
                            oprm.row_num,
                            max(dsct."label") as label,
                            max(oprm.value) as value,
                            string_agg(it."name", \', \')  as institutions,
                            oprm.dynamic_structures_column_id as col,
                            oprm.operational_program_id,
                            max(op.from_date) as from_date,
                            max(op.to_date) as to_date
                        from operational_program_row oprm
                        join dynamic_structure_column dsc on dsc.id = oprm.dynamic_structures_column_id
                        join dynamic_structure_column_translations dsct on dsct.dynamic_structure_column_id = dsc.id and dsct.locale = \'bg\'
                        join operational_program op on op.id = oprm.operational_program_id and op.deleted_at is null and op.public = 1
                        left join operational_program_row_institution opri on opri.operational_program_row_id = oprm.id
                        left join institution_translations it on it.institution_id = opri.institution_id and it.locale = \'bg\'
                        where
                            oprm.deleted_at is null
                            '.(isset($from) ? ' and op.from_date >= \''.$from.'\'' : '').'
                            '.(isset($to) ? ' and op.to_date <= \''.$to.'\'' : '').'
                            and op.id = '.$id.'
                        group by oprm.id, oprm."month", oprm.row_num
                        order by oprm."month", oprm.row_num, oprm.dynamic_structures_column_id
                    ) A
                    group by A.operational_program_id, A."month", A.row_num
                    order by A.operational_program_id, A."month", A.row_num
                ) B
                group by B.operational_program_id, B.month
            ) C
            group by C.operational_program_id
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        if(sizeof($data)){
            $data = $data[0];
            if(!empty($data->program)){
                $data->program = json_decode($data->program, true);
            }
        }

        return $this->output($data);
    }
}

<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OperationalProgramController extends ApiController
{
    public function list(Request $request)
    {
        if(isset($this->request_inputs['date-period-after']) && !empty($this->request_inputs['date-period-after'])){
            if(!$this->checkDate($this->request_inputs['date-period-after'], 'Y-m')){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-period-after\'');
            }
            $from = Carbon::parse($this->request_inputs['date-period-after'])->startOfMonth()->format('Y-m-d');
        }

        if(isset($this->request_inputs['date-period-before']) && !empty($this->request_inputs['date-period-before'])){
            if(!$this->checkDate($this->request_inputs['date-period-before'], 'Y-m')){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-period-before\'');
            }
            $to = Carbon::parse($this->request_inputs['date-period-before'])->endOfMonth()->format('Y-m-d');
        }

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
                        where true
                            '.(!$this->authanticated ? ' and oprm.deleted_at is null ' : '').'
                            '.(isset($from) ? ' and op.from_date >= \''.$from.'\'' : '').'
                            '.(isset($to) ? ' and op.to_date <= \''.$to.'\'' : '').'
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

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->program)){
                    $row->program = json_decode($row->program, true);
                }
                if(!empty($row->files)){
                    $row->files = json_decode($row->files, true);
                } else{
                    $row->files = [];
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
                        where true
                            '.(!$this->authanticated ? ' and oprm.deleted_at is null ' : '').'
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
            if(!empty($data->files)){
                $data->files = json_decode($data->files, true);
            } else{
                $data->files = [];
            }
        }

        if(empty($data)){
            return $this->returnError(Response::HTTP_NOT_FOUND, 'Not found');
        }
        return $this->output($data);
    }
}

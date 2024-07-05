<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LegislativeProgramController extends ApiController
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
                C.legislative_program_id as id,
                max(C.date_from) as date_from,
                max(C.date_to) as date_to,
                (
                    select jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id)
                    from files f
                        where
                            f.id_object = C.legislative_program_id
                            and f.deleted_at is null
                        and f.locale = \''.$this->locale.'\'
                        and f.code_object = '.File::CODE_OBJ_LEGISLATIVE_PROGRAM_GENERAL.'
                ) as files,
                jsonb_agg(jsonb_build_object(C.month, C.records)) as program
            from (
                select
                    B.legislative_program_id,
                    max(B.date_from) as date_from,
                    max(B.date_to) as date_to,
                    B.month,
                    jsonb_agg(B.records) as records
                from (
                    select
                        A.month,
                        A.row_num,
                        jsonb_object_agg(A."label", case when A.col = '.config('lp_op_programs.lp_ds_col_institution_id').' then A.institutions else A.value end) as records,
                        A.legislative_program_id,
                        max(A.from_date) as date_from,
                        max(A.to_date) as date_to
                    from (
                        select
                            lprm.month,
                            lprm.row_num,
                            max(dsct."label") as label,
                            max(lprm.value) as value,
                            string_agg(it."name", \', \') as institutions,
                            lprm.dynamic_structures_column_id as col,
                            lprm.legislative_program_id,
                            max(lp.from_date) as from_date,
                            max(lp.to_date) as to_date
                        from legislative_program_row lprm
                        join dynamic_structure_column dsc on dsc.id = lprm.dynamic_structures_column_id
                        join dynamic_structure_column_translations dsct on dsct.dynamic_structure_column_id = dsc.id and dsct.locale = \''.$this->locale.'\'
                        join legislative_program lp on lp.id = lprm.legislative_program_id and lp.deleted_at is null and lp.public = 1
                        left join legislative_program_row_institution lpri on lpri.legislative_program_row_id = lprm.id
                        left join institution_translations it on it.institution_id = lpri.institution_id and it.locale = \''.$this->locale.'\'
                        where true
                            '.(!$this->authanticated ? ' and lprm.deleted_at is null ' : '').'
                            '.(isset($from) ? ' and lp.from_date >= \''.$from.'\'' : '').'
                            '.(isset($to) ? ' and lp.to_date <= \''.$to.'\'' : '').'
                        group by lprm.id, lprm."month", lprm.row_num
                        order by lprm."month", lprm.row_num, lprm.dynamic_structures_column_id
                    ) A
                    group by A.legislative_program_id, A."month", A.row_num
                    order by A.legislative_program_id, A."month", A.row_num
                ) B
                group by B.legislative_program_id, B.month
            ) C
            group by C.legislative_program_id
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
                C.legislative_program_id as id,
                max(C.date_from) as date_from,
                max(C.date_to) as date_to,
                (
                    select jsonb_agg(\''.url('/').'\' || \'/download/\' || f.id)
                    from files f
                        where
                            f.id_object = C.legislative_program_id
                            and f.deleted_at is null
                        and f.locale = \''.$this->locale.'\'
                        and f.code_object = '.File::CODE_OBJ_LEGISLATIVE_PROGRAM_GENERAL.'
                ) as files,
                jsonb_agg(jsonb_build_object(C.month, C.records)) as program
            from (
                select
                    B.legislative_program_id,
                    max(B.date_from) as date_from,
                    max(B.date_to) as date_to,
                    B.month,
                    jsonb_agg(B.records) as records
                from (
                    select
                        A.month,
                        A.row_num,
                        jsonb_object_agg(A."label", case when A.col = '.config('lp_op_programs.lp_ds_col_institution_id').' then A.institutions else A.value end) as records,
                        A.legislative_program_id,
                        max(A.from_date) as date_from,
                        max(A.to_date) as date_to
                    from (
                        select
                            lprm.month,
                            lprm.row_num,
                            max(dsct."label") as label,
                            max(lprm.value) as value,
                            string_agg(it."name", \', \') as institutions,
                            lprm.dynamic_structures_column_id as col,
                            lprm.legislative_program_id,
                            max(lp.from_date) as from_date,
                            max(lp.to_date) as to_date
                        from legislative_program_row lprm
                        join dynamic_structure_column dsc on dsc.id = lprm.dynamic_structures_column_id
                        join dynamic_structure_column_translations dsct on dsct.dynamic_structure_column_id = dsc.id and dsct.locale = \''.$this->locale.'\'
                        join legislative_program lp on lp.id = lprm.legislative_program_id and lp.deleted_at is null and lp.public = 1
                        left join legislative_program_row_institution lpri on lpri.legislative_program_row_id = lprm.id
                        left join institution_translations it on it.institution_id = lpri.institution_id and it.locale = \''.$this->locale.'\'
                        where true
                            '.(!$this->authanticated ? ' and lprm.deleted_at is null ' : '').'
                            '.(isset($from) ? ' and lp.from_date >= \''.$from.'\'' : '').'
                            '.(isset($to) ? ' and lp.to_date <= \''.$to.'\'' : '').'
                            and lp.id = '.$id.'
                        group by lprm.id, lprm."month", lprm.row_num
                        order by lprm."month", lprm.row_num, lprm.dynamic_structures_column_id
                    ) A
                    group by A.legislative_program_id, A."month", A.row_num
                    order by A.legislative_program_id, A."month", A.row_num
                ) B
                group by B.legislative_program_id, B.month
            ) C
            group by C.legislative_program_id
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

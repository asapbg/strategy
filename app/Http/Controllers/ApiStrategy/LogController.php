<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LogController extends ApiController
{
    public function list(Request $request){
        if(isset($this->request_inputs['subject_type']) && !empty($this->request_inputs['subject_type'])){
            $subjectTypeIds = $this->request_inputs['subject_type'];
        }

        if(isset($this->request_inputs['causer_id']) && !empty($this->request_inputs['causer_id'])){
            $causerIds = $this->request_inputs['causer_id'];
        }

        if(isset($this->request_inputs['date-after']) && !empty($this->request_inputs['date-after'])){
            if(!$this->checkDate($this->request_inputs['date-after'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-after\'');
            }
            $from = Carbon::parse($this->request_inputs['date-after'])->format('Y-m-d');
        }

        if(isset($this->request_inputs['date-before']) && !empty($this->request_inputs['date-before'])){
            if(!$this->checkDate($this->request_inputs['date-before'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-before\'');
            }
            $to = Carbon::parse($this->request_inputs['date-before'])->format('Y-m-d');
        }

        $data = DB::select('
            select
                al.id,
                al.log_name,
                al.description,
                al.subject_type,
                al.subject_id,
                al.causer_type,
                al.causer_id,
                al.properties,
                al.created_at::date as date,
                al.updated_at::date as date,
                al."event"
            from activity_log al
            where true
                '.(isset($subjectTypeIds) ? ' and al.subject_id in ('.$subjectTypeIds.')' : '').'
                '.(isset($causerIds) ? ' and al.causer_id in ('.$causerIds.')' : '').'
                '.(isset($from) ? ' and al.created_at >= \''.$from.' 00:00:00'.'\'' : '').'
                '.(isset($to) ? ' and al.created_at <= \''.$to.' 23:59:59'.'\'' : '').'
            order by al.created_at desc
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->properties)){
                    $row->properties = json_decode($row->properties, true);
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;
        return $this->output($data);
    }

    public function causers(Request $request)
    {
        $data = DB::select('
            select
                u.id,
                max(u.first_name || \' \' || case when u.middle_name is not null then u.middle_name else \'\' end || \' \' || u.last_name) as title
            from activity_log al
            join users u on u.id = al.causer_id
            where al.causer_type = \'App\Models\User\'
                and (
                    u.first_name is not null
                    or u.middle_name  is not null
                    or u.last_name is not null
                    )
            group by u.id
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        return $this->output($data);
    }

    public function subjects(Request $request)
    {
        $data = DB::select('
            select distinct subject_type as type from activity_log al
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        if(sizeof($data)){
            foreach ($data as $row){
                $row->title = trans_choice($row->type::MODULE_NAME, 1);
            }
        }

        return $this->output($data);
    }
}

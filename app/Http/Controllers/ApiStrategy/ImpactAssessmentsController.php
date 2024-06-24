<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Enums\OgpStatusEnum;
use App\Enums\PublicationTypesEnum;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentChildren;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ImpactAssessmentsController extends ApiController
{
    public function list(Request $request){
        $from = $to = $formType = null;
        if(isset($this->request_inputs['type-id']) && !empty($this->request_inputs['type-id'])){
            $formTypeExplode = explode(',', $this->request_inputs['type-id']);
            $formType = sizeof($formTypeExplode) ? $formTypeExplode : null;
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

        $q = DB::table('form_input')
            ->select([
                'form_input.id',
                DB::raw('to_char(form_input.created_at, \'DD.MM.YYYY\') as date'),
                DB::raw('case when form_input.form = \'form1\'
                    then \'Частична предварителна оценка на въздействието\'
                    else case when form_input.form = \'form2\'
                        then \'Резюме на цялостна предварителна оценка на въздействието\'
                        else case when form_input.form = \'form3\'
                            then \'Доклад на цялостна предварителна оценка на въздействието\'
                            else \'Цялостна предварителна-доклад\' end
                        end
                    end as type'),
                DB::raw('trim(\'"\' FROM (data::json->\'institution\')::text) as institution_name'),
                DB::raw('case when users.id is not null then users.first_name || \' \' || users.middle_name || \' \' || users.last_name else \'\' end as user_name'),
                'form_input.data',
            ])
            ->leftJoin('users', 'users.id' , '=', 'form_input.user_id')
            ->whereNull('form_input.deleted_at')
            ->when($from, function (Builder $query) use ($from){
                $query->where('form_input.created_at', '>=', $from);
            })
            ->when($to, function (Builder $query) use ($to){
                $query->where('form_input.created_at', '<=', $to);
            })
            ->when($formType, function (Builder $query) use ($formType){
                $query->whereIn('form_input.form', $formType);
            })
            ->orderBy('form_input.created_at', 'desc');

        if($this->request_limit){
            $q->limit($this->request_limit);
        }
        if($this->request_offset){
            $q->offset($this->request_offset);
        }

        $data = $q->get()->map(fn ($row) => (array)$row)->toArray();

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row['data'])){
                    $row['data'] = json_decode($row['data'], true);
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;

        return $this->output($data);
    }

    public function executors(Request $request){

        $q = DB::table('executors')
            ->select([
                'executors.eik',
                DB::raw('max(executor_translations.executor_name) as executor'),
                DB::raw('json_agg(json_build_object(\'date_contract\', executors.contract_date, \'price\', executors.price, \'active\', executors.active, \'institution_id\', institution.id, \'contract_subject\', executor_translations.contract_subject, \'services_description\', executor_translations.services_description)) as contracts')
            ])
            ->leftJoin('executor_translations', function ($j){
                $j->on('executor_translations.executor_id', '=', 'executors.id')
                    ->where('executor_translations.locale', '=', $this->locale);
            })
            ->leftJoin('institution', 'institution.id', '=', 'executors.institution_id')
            ->leftJoin('institution_translations', function ($j){
                $j->on('institution_translations.institution_id', '=', 'institution.id')
                    ->where('institution_translations.locale', '=', app()->getLocale());
            })
            ->whereNull('executors.deleted_at')
            ->where('executors.active', true)
            ->groupBy('executors.eik');

        if($this->request_limit){
            $q->limit($this->request_limit);
        }
        if($this->request_offset){
            $q->offset($this->request_offset);
        }

        $data = $q->get()->map(fn ($row) => (array)$row)->toArray();

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row['contracts'])){
                    $row['contracts'] = json_decode($row['contracts'], true);
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;

        return $this->output($data);
    }

    public function showExecutor(Request $request, string $eik = ''){

        $q = DB::table('executors')
            ->select([
                'executors.eik',
                DB::raw('max(executor_translations.executor_name) as executor'),
                DB::raw('json_agg(json_build_object(\'date_contract\', executors.contract_date, \'price\', executors.price, \'active\', executors.active, \'institution_id\', institution.id, \'contract_subject\', executor_translations.contract_subject, \'services_description\', executor_translations.services_description)) as contracts')
            ])
            ->leftJoin('executor_translations', function ($j){
                $j->on('executor_translations.executor_id', '=', 'executors.id')
                    ->where('executor_translations.locale', '=', $this->locale);
            })
            ->leftJoin('institution', 'institution.id', '=', 'executors.institution_id')
            ->leftJoin('institution_translations', function ($j){
                $j->on('institution_translations.institution_id', '=', 'institution.id')
                    ->where('institution_translations.locale', '=', app()->getLocale());
            })
            ->whereNull('executors.deleted_at')
            ->where('executors.active', true)
            ->where('executors.eik', '=', $eik)
            ->groupBy('executors.eik');

        if($this->request_limit){
            $q->limit($this->request_limit);
        }
        if($this->request_offset){
            $q->offset($this->request_offset);
        }

        $data = $q->get()->map(fn ($row) => (array)$row)->toArray();

        if(sizeof($data)){
            $data = $data[0];
            if(!empty($data['contracts'])){
                $data['contracts'] = json_decode($data['contracts'], true);
            }
        }

        return $this->output($data);
    }
}

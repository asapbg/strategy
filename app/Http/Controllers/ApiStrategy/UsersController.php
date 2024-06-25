<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends ApiController
{
    public function users(Request $request){
        if(isset($this->request_inputs['role_id']) && !empty($this->request_inputs['role_id'])){
            $roleIds = $this->request_inputs['role_id'];
        }

        if(isset($this->request_inputs['institution_id']) && !empty($this->request_inputs['institution_id'])){
            $institutionIds = $this->request_inputs['institution_id'];
        }

        if(isset($this->request_inputs['is_org']) && !empty($this->request_inputs['is_org'])){
            $isOrg = $this->request_inputs['is_org'] ? 1 : 2;
        }

        if(isset($this->request_inputs['date-after']) && !empty($this->request_inputs['date-after'])){
            if(!$this->checkDate($this->request_inputs['date-after '])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-after\'');
            }
            $from = Carbon::parse($this->request_inputs['date-after '])->format('Y-m-d');
        }

        if(isset($this->request_inputs['date-before']) && !empty($this->request_inputs['date-before'])){
            if(!$this->checkDate($this->request_inputs['date-before'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-before\'');
            }
            $to = Carbon::parse($this->request_inputs['date-before'])->format('Y-m-d');
        }

        $data = DB::select('
            select
                u.id,
                u.username,
                u.is_org,
                u.org_name,
                u.first_name,
                u.middle_name,
                u.last_name,
                case when u.user_type = 1 then \'Вътрешен\' else \'Външен\' end as user_type,
                u.email,
                u.phone,
                u.description,
                u.last_login_at::date as last_login_at,
                u.active,
                u.created_at::date,
                u.updated_at::date,
                u.deleted_at::date,
                u.institution_id,
                u.person_identity,
                u.company_identity,
                jsonb_agg(jsonb_build_object(\'id\', r.id, \'name\', r.display_name)) as roles
            from users u
            left join model_has_roles mhr on mhr.model_id = u.id
            left join roles r on r.id = mhr.role_id
            where true
                '.(isset($roleIds) ? ' and r.id in ('.$roleIds.')' : '').'
                '.(isset($institutionIds) ? ' and u.institution_id in ('.$institutionIds.')' : '').'
                '.(isset($isOrg) ? ' and u.is_org = '.($isOrg == 1 ? 'true' : 'false') : '').'
                '.(isset($from) ? ' and u.created_at >= \''.$from.' 00:00:00'.'\'' : '').'
                '.(isset($to) ? ' and u.created_at <= \''.$to.' 23:59:59'.'\'' : '').'
            group by u.id
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->roles)){
                    $row->roles = json_decode($row->roles, true);
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;
        return $this->output($data);
    }

    public function viewUser(Request $request, $id)
    {
        $data = DB::select('
            select
                u.id,
                u.username,
                u.is_org,
                u.org_name,
                u.first_name,
                u.middle_name,
                u.last_name,
                case when u.user_type = 1 then \'Вътрешен\' else \'Външен\' end as user_type,
                u.email,
                u.phone,
                u.description,
                u.last_login_at::date as last_login_at,
                u.active,
                u.created_at::date,
                u.updated_at::date,
                u.deleted_at::date,
                u.institution_id,
                u.person_identity,
                u.company_identity,
                jsonb_agg(jsonb_build_object(\'id\', r.id, \'name\', r.display_name)) as roles
            from users u
            left join model_has_roles mhr on mhr.model_id = u.id
            left join roles r on r.id = mhr.role_id
            where
                u.id = '.$id.'
            group by u.id
        ');

        if(sizeof($data)){
            $data = $data[0];
            if(!empty($data->roles)){
                $data->roles = json_decode($data->roles, true);
            }
        }
        return $this->output($data);
    }

    public function roles(Request $request){
        $data = DB::select('
            select
                r.id,
                r.name,
                r.display_name,
                r.active,
                jsonb_agg(p.display_name) filter (where p.id is not null) as permissions
            from roles r
            left join role_has_permissions rhp on rhp.role_id = r.id
            left join permissions p on p.id = rhp.permission_id
            group by r.id
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->permissions)){
                    $row->permissions = json_decode($row->permissions, true);
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;
        return $this->output($data);
    }
}

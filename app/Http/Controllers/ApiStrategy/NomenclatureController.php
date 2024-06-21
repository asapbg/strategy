<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\PublicationTypesEnum;
use App\Exports\AdvBoardReportExport;
use App\Models\AdvisoryActType;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardCustom;
use App\Models\AdvisoryBoardFunction;
use App\Models\AdvisoryBoardMeeting;
use App\Models\AdvisoryChairmanType;
use App\Models\AuthorityAdvisoryBoard;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\FieldOfAction;
use App\Models\Page;
use App\Models\Publication;
use App\Models\Setting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class NomenclatureController extends ApiController
{
    public function institutions(Request $request){
        $data = DB::select('
            select
                i.id,
                max(it."name") as title,
                jsonb_agg(ifoa.field_of_action_id) as policy_areas
            from institution i
            join institution_translations it on it.institution_id = i.id and it.locale = \''.$this->locale.'\'
            left join institution_field_of_action ifoa on ifoa.institution_id = i.id
            where
                i.deleted_at is null
                and i.id <> '.env('DEFAULT_INSTITUTION_ID').'
            group by i.id
            order by max(it."name")
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        return $this->output($data);
    }

    public function laws(Request $request){
        $institutionsIds = isset($this->request_inputs['institution-id']) && empty($this->request_inputs['institution-id']) ? $this->request_inputs['institution-id'] : '';

        $data = DB::select('
            select * from (
                select
                    l.id,
                    max(lt."name") as title,
                    jsonb_agg(i.id) filter (where i.id is not null)
                from law l
                join law_translations lt on lt.law_id = l.id and lt.locale = \''.$this->locale.'\'
                left join law_institution li on li.law_id = l.id
                left join institution i on i.id = li.institution_id
                where
                    l.deleted_at is null
                    '.(sizeof($institutionsIds) ? '' : '').'
                group by l.id
                order by max(lt."name")
            ) A
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        return $this->output($data);
    }

    public function actTypes(Request $request){
        $data = DB::select('
            select
                at2.id,
                att."name" as title
            from act_type at2
            join act_type_translations att on att.act_type_id = at2.id and att.locale = \''.$this->locale.'\'
            where
                at2.deleted_at is null
            order by att."name"
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        return $this->output($data);
    }

    public function legalActTypes(Request $request){
        $data = DB::select('
            select
                lat.id,
                latt."name" as title
            from legal_act_type lat
            join legal_act_type_translations latt on latt.legal_act_type_id = lat.id and latt.locale = \''.$this->locale.'\'
            where
                lat.deleted_at is null
            order by latt."name"
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        return $this->output($data);
    }

    public function policyAreas(Request $request){
        $data = DB::select('
            select
                foa.id,
                foat."name" as title
            from field_of_actions foa
            join field_of_action_translations foat on foat.field_of_action_id = foa.id and foat.locale = \''.$this->locale.'\'
            where
                foa.deleted_at is null
                and foa.parentid is not null
            order by foat."name"
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        return $this->output($data);
    }
}

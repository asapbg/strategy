<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Enums\PrisDocChangeTypeEnum;
use App\Models\File;
use App\Models\LegalActType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PrisController extends ApiController
{
    public function list(Request $request)
    {
        $authorsWhereClause = '';
        if(isset($this->request_inputs['date-after']) && !empty($this->request_inputs['date-after'])){
            if(!$this->checkDate($this->request_inputs['date-after'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-after\'');
            }
            $from = Carbon::parse($this->request_inputs['date-after'])->startOfMonth()->format('Y-m-d');
        }

        if(isset($this->request_inputs['date-before']) && !empty($this->request_inputs['date-before'])){
            if(!$this->checkDate($this->request_inputs['date-before'])){
                return $this->returnError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Invalid date format for \'date-before\'');
            }
            $to = Carbon::parse($this->request_inputs['date-before'])->endOfMonth()->format('Y-m-d');
        }

        if(isset($this->request_inputs['legal-act-type']) && !empty($this->request_inputs['legal-act-type'])){
            $legalActTypeIds = $this->request_inputs['legal-act-type'];
        }
        if(isset($this->request_inputs['author']) && !empty($this->request_inputs['author'])){
            $realAuthors = [];
            $noAuthor = null;
            $explodeAuthors = explode(',', $this->request_inputs['author']);
            if(sizeof($explodeAuthors)){
                foreach ($explodeAuthors as $a){
                    if((int)$a > 0){
                        $realAuthors[] = $a;
                    }
                    if((int)$a == -1){
                        $noAuthor = 1;
                    }
                }
            }
            if(sizeof($realAuthors)){
                $authorsWhereClause = ' and (pi2.institution_id in ('.implode(',', $realAuthors).') ';
            }
            if($noAuthor){
                $authorsWhereClause .= empty($authorsWhereClause) ? ' and (pi2.institution_id = '.env('DEFAULT_INSTITUTION_ID').' or pi2.institution_id is null) ' : ' or (pi2.institution_id = '.env('DEFAULT_INSTITUTION_ID').' or pi2.institution_id is null) )';
            } else if (sizeof($realAuthors)){
                $authorsWhereClause .= ')';
            }
        }
        if(isset($this->request_inputs['tags']) && !empty($this->request_inputs['tags'])){
            $splitTags = explode(',', $this->request_inputs['tags']);
            if(sizeof($splitTags)){
                $tags = $splitTags;
            }
        }

        $data = DB::select('
            select
                p.id as pris_id,
                p.doc_num,
                p.doc_date as doc_accepted_date,
                max(pt.about) as doc_about,
                p.legal_act_type_id as legal_act_type,
                json_agg(tt."label") filter(where tt.id is not null) as tags
            from pris p
            join pris_translations pt on pt.pris_id = p.id and pt.locale = \''.$this->locale.'\'
            left join pris_tag pt2 on pt2.pris_id = p.id
            left join tag t on t.id = pt2.tag_id and t.deleted_at is null
            left join tag_translations tt on tt.tag_id = t.id and tt.locale = \''.$this->locale.'\'
            left join pris_institution pi2 on pi2.pris_id = p.id
            where true
                and p.last_version = 1
                '.(!$this->authanticated ? ' and p.deleted_at is null and p.published_at is not null and p.active = 1 ' : '').'
                '.(isset($from) ? ' and p.doc_date >= \''.$from.'\'' : '').'
                '.(isset($to) ? ' and p.doc_date <= \''.$to.'\'' : '').'
                '.(isset($legalActTypeIds) ? ' and p.legal_act_type_id in ('.$legalActTypeIds.')' : '').'
                '.(isset($tags) ? ' and tt.label in (\''.implode('\',\'', $tags).'\')' : '').'
                '.$authorsWhereClause.'
            group by p.id
            order by p.created_at
            '.($this->request_limit ? ' limit '.$this->request_limit : '').'
            '.($this->request_offset ? ' offset '.$this->request_offset : '').'
        ');

        $finalData = array();
        if(sizeof($data)){
            foreach ($data as $row){
                if(!empty($row->tags)){
                    $row->tags = json_decode($row->tags, true);
                } else {
                    $row->tags = [];
                }
                $finalData[] = $row;
            }
        }
        $data = $finalData;

        return $this->output($data);
    }

    public function show(Request $request, $id = 0)
    {
        $q = DB::table('pris')
            ->select([
                'pris.id as pris_id',
                'pris.doc_num',
                'pris.doc_date as doc_accepted_date',
                DB::raw('max(pris_translations.about) as doc_about'),
                DB::raw('max(legal_act_type_translations.name) as legal_act_type'),
                DB::raw('max(pris_translations.legal_reason) as legal_reason'),
                DB::raw('max(pris_translations.importer) as importer'),
                DB::raw('(
                            select
                                json_agg(json_build_object(\'id\', institution.id, \'name\', institution_translations.name)) filter (where institution.id is not null)
                            from pris_institution
                            join institution on institution.id = pris_institution.institution_id
                            join institution_translations on institution_translations.institution_id = institution.id and institution_translations.locale = \'' . app()->getLocale() . '\'
                            where
                                pris_institution.pris_id = pris.id
                                and pris_institution.institution_id <> '.env('DEFAULT_INSTITUTION_ID').'
                            ) as institutions'),
                DB::raw('pris.version'),
                DB::raw('case
                                            when max(pp.id) is null then pris.protocol
                                            else
                                                case
                                                    when max(pp.protocol_point) is null
                                                    then (max(pp_lat_tr.name_single) || \' \' || \'' . __('custom.number_symbol') . '\' || max(pp.doc_num) || \' \' || \'' . __('custom.of_council') . '\' || \' \' || date_part(\'year\',max(pp.doc_date)))
                                                    else (\'' . __('site.point_short') . '\' || \' \' || \'' . __('custom.from') . '\' || \' \' || max(pp_lat_tr.name_single) || \' \' || \'' . __('custom.number_symbol') . '\' || max(pp.doc_num) || \' \' || \'' . __('custom.of_council') . '\' || \' \' || date_part(\'year\',max(pp.doc_date))) end
                                            end as protocol'),
                DB::raw('max(public_consultation.reg_num) as public_consultation_number'),
                'pris.newspaper_number as state_gazette_number',
                'pris.newspaper_year as state_gazette_year',
                DB::raw('(pris.active::int)::bool as active'),
                DB::raw('pris.published_at::date as date_published_at'),
                DB::raw('pris.deleted_at::date as date_deleted_at'),
                DB::raw('(
                            select
                                json_agg(tag_translations.label) filter (where tag_translations.label is not null)
                            from pris_tag
                            join tag_translations on tag_translations.tag_id = pris_tag.tag_id and tag_translations.locale = \'' . app()->getLocale() . '\'
                            where pris_tag.pris_id = pris.id
                            ) as tags'),
                DB::raw('(
                            select
                                    json_agg(json_build_object(\'relation_type\', case
                                        when pris_change_pris.connect_type = ' . PrisDocChangeTypeEnum::CHANGE->value . ' then (case when pris_change_pris.pris_id <> pc.id then \'' . __('custom.pris.change_enum.CHANGE') . '\' else \'' . __('custom.pris.change_enum.reverse.CHANGE') . '\' end)
                                        else
                                            case when pris_change_pris.connect_type = ' . PrisDocChangeTypeEnum::COMPLEMENTS->value . ' then (case when pris_change_pris.pris_id <> pc.id then \'' . __('custom.pris.change_enum.COMPLEMENTS') . '\' else \'' . __('custom.pris.change_enum.reverse.COMPLEMENTS') . '\' end)
                                            else
                                                case when pris_change_pris.connect_type = ' . PrisDocChangeTypeEnum::CANCEL->value . ' then (case when pris_change_pris.pris_id <> pc.id then \'' . __('custom.pris.change_enum.CANCEL') . '\' else \'' . __('custom.pris.change_enum.reverse.CANCEL') . '\' end)
                                                else
                                                    case when pris_change_pris.connect_type = ' . PrisDocChangeTypeEnum::SEE_IN->value . ' then (case when pris_change_pris.pris_id <> pc.id then \'' . __('custom.pris.change_enum.SEE_IN') . '\' else \'' . __('custom.pris.change_enum.reverse.SEE_IN') . '\' end)
                                                    else \'\' end
                                                end
                                            end
                                        end, \'pris_id\', pc.id, \'act_type\', pc_act_tr.name_single, \'act_name\', (pc_act_tr.name_single || \' \' || \'' . __('custom.number_symbol') . '\' || pc.doc_num || \' \' || \'' . __('custom.of_council') . '\' || \' \' || date_part(\'year\',pc.doc_date)) ))
                                 from pris_change_pris
                                 join pris as pc on pc.id = (case when pris_change_pris.pris_id = pris.id then pris_change_pris.changed_pris_id else pris_change_pris.pris_id end)
                                 join pris_translations as pc_tr on pc_tr.pris_id = pc.id and pc_tr.locale = \'bg\'
                                 join legal_act_type as pc_act on pc_act.id = pc.legal_act_type_id
                                 join legal_act_type_translations as pc_act_tr on pc_act_tr.legal_act_type_id = pc_act.id and pc_act_tr.locale = \'bg\'
                                 where
                                    (pris_change_pris.pris_id = pris.id
                                    or pris_change_pris.changed_pris_id = pris.id)
                                    and pc.deleted_at is null
                             ) as related'),
            ])
            ->join('pris_translations', function ($q) {
                $q->on('pris_translations.pris_id', '=', 'pris.id')->where('pris_translations.locale', '=', 'bg');
            })
            ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
            ->join('legal_act_type_translations', function ($j) {
                $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
                    ->where('legal_act_type_translations.locale', '=', app()->getLocale());
            })
            //Public consultation
            ->leftJoin('public_consultation', 'public_consultation.id', '=', 'pris.public_consultation_id')
            //Protocol
            ->leftJoin('pris as pp', 'pp.id', '=', 'pris.decision_protocol')
            ->leftJoin('pris_translations as pp_tr', function ($q) {
                $q->on('pp_tr.pris_id', '=', 'pp.id')->where('pp_tr.locale', '=', 'bg');
            })
            ->leftJoin('legal_act_type as pp_lat', 'pp_lat.id', '=', 'pp.legal_act_type_id')
            ->leftJoin('legal_act_type_translations as pp_lat_tr', function ($j) {
                $j->on('pp_lat_tr.legal_act_type_id', '=', 'pp_lat.id')
                    ->where('pp_lat_tr.locale', '=', app()->getLocale());
            })
            ->where('pris.id', '=', $id)
            ->whereIn('pris.legal_act_type_id', [LegalActType::TYPE_DECREES, LegalActType::TYPE_DECISION, LegalActType::TYPE_PROTOCOL_DECISION, LegalActType::TYPE_DISPOSITION, LegalActType::TYPE_PROTOCOL, LegalActType::TYPE_ARCHIVE])
            ->where('pris.asap_last_version', '=', 1);

            if(!$this->authanticated){
                $q->whereNull('pris.deleted_at')
                    ->whereNotNull('pris.published_at');
            }
            $q->groupBy('pris.id');

        $data = $q->get()->map(function ($row) {
            if(!empty($row->tags)){
                $row->tags = json_decode($row->tags, true);
            } else{
                $row->tags = [];
            }
            if (!empty($row->institutions)) {
                $row->institutions = json_decode($row->institutions, true);
            } else{
                $row->institutions = [];
            }
            if (!empty($row->related)) {
                $row->related = json_decode($row->related, true);
            } else{
                $row->related = [];
            }

            return (array)$row;
        })->toArray();

        if(sizeof($data)){
            $data = $data[0];
        }

        if(empty($data)){
            return $this->returnError(Response::HTTP_NOT_FOUND, 'Not found');
        }
        return $this->output($data);
    }
}

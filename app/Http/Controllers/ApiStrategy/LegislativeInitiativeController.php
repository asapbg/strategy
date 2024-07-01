<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LegislativeInitiativeController extends ApiController
{
    public function list(Request $request)
    {
        $from = $to = $isSend = $institutionsIds = null;
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

        if(isset($this->request_inputs['sent'])){
            $isSend = $this->request_inputs['sent'] ? 1 : 2; //2 - not send
        }

        if(isset($this->request_inputs['institution']) && !empty($this->request_inputs['institution'])){
            $institutionsIds = $this->request_inputs['institution'];
        }

        $q = DB::table('legislative_initiative')
            ->select([
                'legislative_initiative.id',
                DB::raw('case when max(users.id) is not null then max(users.first_name) || \' \' || max(users.last_name) else \'\' end as author_name'),
                DB::raw('\''.__('custom.change_f').' '.__('custom.in').'\' || \' \' || max(law_translations.name) as name'),
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
                DB::raw('case when max(ric.id) is not null then true else false end as sent'),
                DB::raw('sum(case when legislative_initiative_votes.is_like = true then 1 else 0 end) as votes_for'),
                DB::raw('sum(case when legislative_initiative_votes.is_like = false then 1 else 0 end) as votes_against'),
                DB::raw('json_agg(ici.id) filter (where ici.id is not null) as institutions'),
            ])
            ->leftJoin('users', 'users.id', '=', 'legislative_initiative.author_id')
            ->join('law', 'law.id', '=', 'legislative_initiative.law_id')
            ->join('law_translations', function ($q){
                $q->on('law_translations.law_id', '=', 'law.id')->where('law_translations.locale', '=', $this->locale);
            })
            ->leftJoin('legislative_initiative_votes', 'legislative_initiative_id', '=', 'legislative_initiative.id')
            ->join('legislative_initiative_institution as ic', 'ic.legislative_initiative_id', '=', 'legislative_initiative.id')
            ->join('institution as ici', 'ici.id', '=', 'ic.institution_id')
            ->join('institution_translations as ic_translation', function ($q){
                $q->on('ic_translation.institution_id', '=', 'ici.id')->where('ic_translation.locale', '=', $this->locale);
            })
            ->leftJoin('legislative_initiative_receiver as ric', 'ric.legislative_initiative_id', '=', 'legislative_initiative.id')
            ->leftJoin('institution as rici', 'rici.id', '=', 'ric.institution_id')
            ->leftJoin('institution_translations as ric_translation', function ($q){
                $q->on('ric_translation.institution_id', '=', 'rici.id')->where('ric_translation.locale', '=', $this->locale);
            });
            if(!$this->authanticated){
                $q->whereNull('legislative_initiative.deleted_at');
            }

            $q->when($isSend, function (Builder $query) use ($isSend){
                if($isSend == 1){
                    $query->whereNotNUll('legislative_initiative.send_at');
                } else{
                    $query->whereNUll('legislative_initiative.send_at');
                }
            })
            ->when($from, function (Builder $query) use ($from){
                $query->where('legislative_initiative.created_at', '>=', $from.' 00:00:00');
            })
            ->when($to, function (Builder $query) use ($to){
                $query->where('legislative_initiative.created_at', '<=', $to.' 23:59:59');
            })
            ->when($institutionsIds, function (Builder $query) use ($institutionsIds){
                $query->whereRaw('ic.institution_id in ('.$institutionsIds.')');
            })
            ->groupBy('legislative_initiative.id')
            ->orderBy('legislative_initiative.created_at', 'desc');

        if($this->request_limit){
            $q->limit($this->request_limit);
        }
        if($this->request_offset){
            $q->offset($this->request_offset);
        }

        $data = $q->get()->map(function ($row) {
            if(!empty($row->comments)){
                $row->comments = json_decode($row->comments, true);
            }
            if(!empty($row->institutions)){
                $row->institutions = json_decode($row->institutions, true);
            }
            return (array)$row;
        })->toArray();

        return $this->output($data);
    }

    public function show(Request $request, $id = 0)
    {
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
                DB::raw('max(law.id) as law_name'),
                DB::raw('max(law_translations.name) as law_id'),
                DB::raw('legislative_initiative.law_paragraph as law_paragraph'),
                DB::raw('legislative_initiative.law_text as law_text_change'),
                DB::raw('legislative_initiative.motivation as motivation'),
                'legislative_initiative.cap',
                DB::raw('case when max(ric.id) is not null then true else false end as sent'),
                DB::raw('sum(case when legislative_initiative_votes.is_like = true then 1 else 0 end) as votes_for'),
                DB::raw('sum(case when legislative_initiative_votes.is_like = false then 1 else 0 end) as votes_against'),
                DB::raw('json_agg(ici.id) filter (where ici.id is not null) as institutions'),
                DB::raw('(
                            select
                                    json_agg(json_build_object(\'date\', legislative_initiative_comments.created_at::date, \'author_name\', (case when u.id is not null then u.first_name || \' \' || u.last_name else \'\' end), \'text\', legislative_initiative_comments.description))
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
                $q->on('law_translations.law_id', '=', 'law.id')->where('law_translations.locale', '=', $this->locale);
            })
            ->leftJoin('legislative_initiative_votes', 'legislative_initiative_id', '=', 'legislative_initiative.id')
            ->join('legislative_initiative_institution as ic', 'ic.legislative_initiative_id', '=', 'legislative_initiative.id')
            ->join('institution as ici', 'ici.id', '=', 'ic.institution_id')
            ->join('institution_translations as ic_translation', function ($q){
                $q->on('ic_translation.institution_id', '=', 'ici.id')->where('ic_translation.locale', '=', $this->locale);
            })
            ->leftJoin('legislative_initiative_receiver as ric', 'ric.legislative_initiative_id', '=', 'legislative_initiative.id')
            ->leftJoin('institution as rici', 'rici.id', '=', 'ric.institution_id')
            ->leftJoin('institution_translations as ric_translation', function ($q){
                $q->on('ric_translation.institution_id', '=', 'rici.id')->where('ric_translation.locale', '=', $this->locale);
            });
            if(!$this->authanticated){
                $q->whereNull('legislative_initiative.deleted_at');
            }

            $q->where('legislative_initiative.id', '=', $id)
            ->groupBy('legislative_initiative.id')
            ->orderBy('legislative_initiative.created_at', 'desc');

        $data = $q->get()->map(function ($row) {
            if(!empty($row->comments)){
                $row->comments = json_decode($row->comments, true);
            } else{
                $row->comments = [];
            }
            if(!empty($row->institutions)){
                $row->institutions = json_decode($row->institutions, true);
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

    public function comments(Request $request, $id = 0)
    {
        $data = DB::select('
            select
                li.id,
                lic.created_at::date as date,
                (case when u.id is not null then u.first_name || \' \' || u.last_name else \'\' end) as author_name,
                lic.description as text
                from legislative_initiative_comments lic
                left join users u on u.id = lic.user_id
                left join legislative_initiative li on li.id = lic.legislative_initiative_id
                where true
                    '.(!$this->authanticated ? 'and lic.deleted_at is null and li.deleted_at is null ' : '').'
                    and lic.legislative_initiative_id = '.$id.'
        ');

        return $this->output($data);
    }

}

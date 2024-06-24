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

class LibraryController extends ApiController
{
    public function list(Request $request){
        $from = $to = null;
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

        $q = DB::table('publication')
            ->select([
                'publication.id',
                'publication_translations.title',
                DB::raw('to_char(publication.published_at, \'DD.MM.YYYY\') as date'),
                DB::raw('case when users.id is not null then users.first_name || \' \' || users.middle_name || \' \' || users.last_name else \'\' end as author'),
                'publication_translations.content'
            ])
            ->leftJoin('users', 'users.id' , '=', 'publication.users_id')
            ->leftJoin('publication_translations', function ($j){
                $j->on('publication_translations.publication_id', '=', 'publication.id')
                    ->where('publication_translations.locale', '=', $this->locale);
            })
            ->whereNull('publication.deleted_at')
            ->whereIn('publication.type', [PublicationTypesEnum::TYPE_LIBRARY, PublicationTypesEnum::TYPE_NEWS])
            ->whereNotNull('publication.published_at')
            ->where('publication.active', true)
            ->when($from, function (Builder $query) use ($from){
                $query->where('publication.published_at', '>=', $from);
            })
            ->when($to, function (Builder $query) use ($to){
                $query->where('publication.published_at', '<=', $to);
            })
            ->orderBy('publication.published_at', 'desc');

        if($this->request_limit){
            $q->limit($this->request_limit);
        }
        if($this->request_offset){
            $q->offset($this->request_offset);
        }

        $data = $q->get()->map(fn ($row) => (array)$row)->toArray();

        return $this->output($data);

    }
}

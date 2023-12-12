<?php

namespace App\Models\Consultations;

use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Enums\PublicConsultationTimelineEnum;
use App\Models\ActType;
use App\Models\Comments;
use App\Models\ConsultationLevel;
use App\Models\CustomRole;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\Poll;
use App\Models\Pris;
use App\Models\PublicConsultationContact;
use App\Models\RegulatoryAct;
use App\Models\StrategicDocuments\Institution;
use App\Models\Timeline;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use App\Models\ModelActivityExtend;

class PublicConsultation extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'description', 'short_term_reason', 'responsible_unit', 'proposal_ways', 'importer'];
    const SHORT_REASON_FIELD = 'short_term_reason';
    const MODULE_NAME = ('custom.consultations.public_consultation');
    const EMAIL_SUBJECT = ('New public consultation was published');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'public_consultation';

    //activity
    protected string $logName = "public_consultation";

    protected $fillable = ['consultation_level_id', 'act_type_id',
        'legislative_program_id', 'operational_program_id', 'open_from', 'open_to',
        'importer_institution_id', 'responsible_institution_id', 'responsible_institution_address',
        'active', 'reg_num', 'monitorstat', 'legislative_program_row_id', 'operational_program_row_id',
        'proposal_report_comment_id', 'field_of_actions_id', 'law_id', 'pris_id'
    ];

    const MIN_DURATION_DAYS = 14;
    const SHORT_DURATION_DAYS = 29;

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->translation?->title;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255']
            ],
            'description' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
            'proposal_ways' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
            'short_term_reason' => [
                'type' => 'text',
                'rules' => ['nullable', 'string']
            ],
            'responsible_unit' => [
                'type' => 'summernote',
                'rules' => ['nullable', 'string']
            ],
            'importer' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255']
            ]
        );
    }

    public function scopeActive($query){
        $query->where('public_consultation.active', 1);
    }

    public function scopeActivePublic($query){
        $query->where('public_consultation.active', 1)
            ->where('public_consultation.open_from', '<=', Carbon::now()->format('Y-m-d'));
    }

    public function scopeByUser($query){
        $user = auth()->user();
        if($user && !$user->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE])) {
            if( $user->can('manage.advisory') ) {
                return $query->where('public_consultation.importer_institution_id', '=', $user->institution_id);
            }
        }
    }

    public function scopeEnded($query){
        $query->where('public_consultation.open_to', '<', Carbon::now()->format('Y-m-d'));
    }

    protected function openFrom(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? Carbon::parse($value)->format('d.m.Y') : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }


    protected function openTo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? Carbon::parse($value)->format('d.m.Y') : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function inPeriod(): Attribute
    {
        $now = Carbon::now()->format('Y-m-d');
        return Attribute::make(
            get: fn () => ($now >= databaseDate($this->open_from) && databaseDate($this->open_to) >= $now) ? __('custom.active_f') : __('custom.inactive_f'),
        );
    }

    protected function inPeriodBoolean(): Attribute
    {
        $now = Carbon::now()->format('Y-m-d');
        return Attribute::make(
            get: fn () => $now >= databaseDate($this->open_from) && databaseDate($this->open_to) >= $now,
        );
    }

    protected function daysCnt(): Attribute
    {
        $from = Carbon::parse($this->open_from);
        $to = Carbon::parse($this->open_to);
        return Attribute::make(
            get: fn () => $from->diffInDays($to),
        );
    }

    protected function nomenclatureLevelLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->consultation_level_id ? __('custom.nomenclature_level.'.InstitutionCategoryLevelEnum::keyByValue($this->consultation_level_id)) : '---',
        );
    }

//    public function consultationLevel(): \Illuminate\Database\Eloquent\Relations\HasOne
//    {
//        return $this->hasOne(ConsultationLevel::class, 'consultation_level_id', 'id');
//    }

    public function op(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OperationalProgram::class, 'id', 'operational_program_id');
    }

    public function lp(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LegislativeProgram::class, 'id', 'legislative_program_id');
    }

    public function importerInstitution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Institution::class, 'id', 'importer_institution_id');
    }

    public function responsibleInstitution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Institution::class, 'id', 'responsible_institution_id');
    }


    public function pris(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pris::class, 'id', 'public_consultation_id');
    }

    public function decree(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Pris::class, 'id', 'pris_id');
    }

    public function actType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ActType::class, 'id', 'act_type_id');
    }

    public function contactPersons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PublicConsultationContact::class, 'public_consultation_id', 'id');
    }

    public function kd(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ConsultationDocument::class, 'public_consultation_id', 'id');
    }

    public function lpRow(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LegislativeProgramRow::class, 'id', 'legislative_program_row_id');
    }

    public function opRow(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OperationalProgramRow::class, 'id', 'operational_program_row_id');
    }

    public function polls(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Poll::class, 'public_consultation_poll', 'public_consultation_id', 'poll_id');
    }

    public function pollsInPeriod(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Poll::class, 'public_consultation_poll', 'public_consultation_id', 'poll_id')
            ->where('status', '=', 1);
            //->where('end_date', '>=', Carbon::now()->format('Y-m-d'));
    }

    public function timeline(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Timeline::class, 'public_consultation_id', 'id');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comments::class, 'object_id', 'id')
            ->where('object_code', '=', Comments::PC_OBJ_CODE)
            ->orderBy('created_at', 'desc');
    }

    public function documents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
            ->orderBy('created_at', 'desc')
            ->orderBy('locale');
    }

    public function commentsDocuments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
            ->whereIn('doc_type', [DocTypesEnum::PC_COMMENTS_CSV->value, DocTypesEnum::PC_COMMENTS_PDF->value])
            ->orderBy('created_at', 'desc')
            ->orderBy('locale');
    }

    public function commentsDocumentPdf()
    {
        return $this->hasMany(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
            ->where('doc_type', '=', DocTypesEnum::PC_COMMENTS_PDF->value)
            ->orderBy('created_at', 'desc')
            ->orderBy('locale')->first();
    }

    public function commentsDocumentCsv()
    {
        return $this->hasMany(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
            ->where('doc_type', '=', DocTypesEnum::PC_COMMENTS_CSV->value)
            ->orderBy('created_at', 'desc')
            ->orderBy('locale')->first();
    }

    public function consultations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PublicConsultation::class, 'public_consultation_connection', 'public_consultation_id', 'pc_id');
    }

    public function fieldOfAction(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(FieldOfAction::class, 'id', 'field_of_actions_id');
    }

    public function lastDocumentByLocaleAndType($docType)
    {
        return DB::table('public_consultation')
            ->select(['files.id', 'files.doc_type', DB::raw('files.description_'.app()->getLocale().' as description'), 'files.content_type', 'files.created_at', 'files.version'])
            ->join('files', function ($j) use ($docType){
                $j->on('files.id_object', '=', 'public_consultation.id')
                    ->where('files.locale','=', app()->getLocale())
                    ->where('files.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                    ->where('files.doc_type', '=', $docType);
            })
            ->where('public_consultation.id', '=', $this->id)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function lastDocumentsByLocaleAndSection($forPublic = false)
    {
        $documents = [];
        $documentTypes = $forPublic ? DocTypesEnum::docsByActTypePublic($this->act_type_id) : DocTypesEnum::docsByActType($this->act_type_id);
        foreach ($documentTypes as $docType) {
            $doc = DB::table('public_consultation')
                ->select(['files.id', 'files.doc_type', DB::raw('files.description_'.app()->getLocale().' as description'), 'files.content_type', 'files.created_at', 'files.version'])
                ->join('files', function ($j) use ($docType){
                    $j->on('files.id_object', '=', 'public_consultation.id')
                        ->where('files.locale','=', app()->getLocale())
                        ->where('files.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                        ->where('files.doc_type', '=', $docType);
                })
                ->where('public_consultation.id', '=', $this->id)
                ->orderBy('created_at', 'desc')
                ->first();
            if( $doc ) {
                $documents[] = $doc;
            }
        }

        return $documents;
    }

    public static function optionsList()
    {
        return DB::table('public_consultation')
            ->select(['public_consultation.id', DB::raw('public_consultation_translations.title as name')])
            ->join('public_consultation_translations', 'public_consultation_translations.public_consultation_id', '=', 'public_consultation.id')
            ->where('public_consultation_translations.locale', '=', app()->getLocale())
            ->whereNull('public_consultation.deleted_at')
            ->orderBy('public_consultation_translations.title', 'asc')
            ->get();
    }

    public function changedFiles(): \Illuminate\Support\Collection
    {
        return DB::table('public_consultation')
            ->select(['files.id', 'files.doc_type', DB::raw('files.description_'.app()->getLocale().' as description'), 'files.content_type', 'files.created_at', 'files.version', 'files.locale'])
            ->join('files', function ($j){
                $j->on('files.id_object', '=', 'public_consultation.id')
                    ->where('files.locale','=', app()->getLocale())
                    ->where('files.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION);
            })
            ->where('public_consultation.id', '=', $this->id)
            ->where('files.version', '<>', '1.0')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function select2AjaxOptions($filters)
    {
        $q = DB::table('public_consultation')
            ->select(['public_consultation.id', DB::raw('public_consultation.reg_num || \' / \' || public_consultation_translations.title as name')])
            ->join('public_consultation_translations', function ($j){
                $j->on('public_consultation.id', '=', 'public_consultation_translations.public_consultation_id')
                    ->where('public_consultation_translations.locale', '=', app()->getLocale());
            });

        if(isset($filters['exclude']) && (int)$filters['exclude']) {
            $q->where('public_consultation.id', '<>', (int)$filters['exclude']);
        }
        if(isset($filters['connections']) && is_array($filters['connections']) && sizeof($filters['connections'])) {
            $q->whereNotIn('public_consultation.id', $filters['connections']);
        }
        if(isset($filters['title'])) {
            $q->where('public_consultation_translations.title', 'ilike', '%'.$filters['title'].'%');
        }
        if(isset($filters['reg_num'])) {
            $q->where('public_consultation.reg_num', 'ilike', '%'.$filters['reg_num'].'%');
        }

        $q->whereNull('public_consultation.deleted_at');

        return $q->get();
    }

    public function orderTimeline()
    {
        $events = $this->timeline;
        $sortedTimeline = [];
        $now = Carbon::now()->format('Y-m-d H:i:s');

        //Начало на обществената консултация
        $startDate = Carbon::parse($this->open_from)->format('Y-m-d 00:00:00');
        $sortedTimeline['2'] = [
            'label' => __('custom.timeline.'.\App\Enums\PublicConsultationTimelineEnum::keyByValue(PublicConsultationTimelineEnum::START->value)),
            'date' => displayDate($startDate),
            'isActive' => $this->inPeriodBoolean || ($now > Carbon::parse($this->open_to)->format('Y-m-d H:i:s')),
//            'description' => '<p class="'.($this->inPeriodBoolean || ($now > Carbon::parse($this->open_to)->format('Y-m-d H:i:s')) ? 'text-muted' : '').'">'.__('custom.timeline.'.(PublicConsultationTimelineEnum::keyByValue(PublicConsultationTimelineEnum::START->value)).'.description').'</p>'
            'description' => ''
        ];

        //Приключване на консултацията
        $endDate = Carbon::parse($this->open_to)->format('Y-m-d 23:59:59');
        $sortedTimeline['4'] = [
            'label' => __('custom.timeline.'.\App\Enums\PublicConsultationTimelineEnum::keyByValue(PublicConsultationTimelineEnum::END->value)),
            'date' => displayDate($endDate),
            'isActive' => $now > Carbon::parse($this->open_to)->format('Y-m-d H:i:s'),
//            'description' => '<p class="'.($now < Carbon::parse($this->open_to)->format('Y-m-d H:i:s') ? 'text-muted' : '').'">'.__('custom.timeline.'.(PublicConsultationTimelineEnum::keyByValue(PublicConsultationTimelineEnum::END->value)).'.description').'</p>'
            'description' => ''
        ];

        //Приемане на акта от Министерския съвет
        $pris = $this->pris;
        if($pris){
            $prisEventLabel = __('custom.timeline.'.\App\Enums\PublicConsultationTimelineEnum::keyByValue(PublicConsultationTimelineEnum::ACCEPT_ACT_MC->value));
            $prisEventDescription = '<p><a class="text-primary" href="'.route('pris.view', ['category' => $pris->actType->name, 'id' => $pris->id]).'" target="_blank">'.$pris->mcDisplayName.'</a></p>';
        } else {
            $prisEventLabel = __('custom.timeline.'.\App\Enums\PublicConsultationTimelineEnum::keyByValue(PublicConsultationTimelineEnum::ACCEPT_ACT_MC->value));
            $prisEventDescription = __('custom.timeline.'.\App\Enums\PublicConsultationTimelineEnum::keyByValue(PublicConsultationTimelineEnum::ACCEPT_ACT_MC->value).'.description');
        }
        $sortedTimeline['6'] = [
            'label' => $prisEventLabel,
            'date' => $pris ? displayDate($pris->doc_date) : null,
            'isActive' => (bool)$pris,
            'description' => $prisEventDescription
        ];

        //TODO PublicConsultationTimelineEnum::PRESENTING_IN_NA->value
        foreach ([PublicConsultationTimelineEnum::INCLUDE_TO_PROGRAM->value,
                     PublicConsultationTimelineEnum::FILE_CHANGE->value,
                     PublicConsultationTimelineEnum::PUBLISH_PROPOSALS_REPORT->value
                 ] as $e){
            $found = 0;
            if($events->count()) {
                foreach ($events as $event) {
                    if($e == $event->event_id) {
                        switch ($event->event_id) {
                            case PublicConsultationTimelineEnum::INCLUDE_TO_PROGRAM->value:
                                $label = $event->object instanceof OperationalProgramRow ? __('custom.op_project_timeline_label') : __('custom.lp_project_timeline_label');
                                //always first
                                $sortedTimeline['1'] = [
                                    'label' => $label,
                                    'date' => displayDate($event->updated_at),
                                    'isActive' => true,
                                    'description' => '<p><a class="text-primary" target="_blank" href="'.route(($event->object instanceof OperationalProgramRow ? 'op.view' : 'lp.view') , ['id' => $event->object instanceof OperationalProgramRow ? $event->object->operational_program_id : $event->object->legislative_program_id ]).'">'.$event->object->value.'</a></p>'
                                ];
                                $found = 1;
                                break;
                            case PublicConsultationTimelineEnum::FILE_CHANGE->value:
                            case PublicConsultationTimelineEnum::PUBLISH_PROPOSALS_REPORT->value:
                                $index = $event->event_id == PublicConsultationTimelineEnum::FILE_CHANGE->value ? '3_'.$event->created_at : '5';
                                if($event->object->{'description_' . app()->getLocale()}) {
                                    $sortedTimeline[$index] = [
                                        'label' => __('custom.timeline.'.\App\Enums\PublicConsultationTimelineEnum::keyByValue($event->event_id)),
                                        'date' => displayDate($event->created_at),
                                        'isActive' => true,
                                        'description' => '<p><span class="d-inline-block">
                                                <button type="button" class="btn btn-sm btn-outline-secondary preview-file-modal" data-file="'.$event->object->id.'" data-url="'.route('admin.preview.file.modal', ['id' => $event->object->id]).'" title="'.__('custom.preview').'">'.fileIcon($event->object->content_type).' '.($event->object->{'description_' . app()->getLocale()}).' '.__('custom.version_short').' '.$event->object->version.'</button>
                                            </span></p>'
                                    ];
                                }
                                $found = 1;
                                break;
                        }
                    }
                }
            }

            if(!$found && $e != PublicConsultationTimelineEnum::FILE_CHANGE->value) {
                $label = __('custom.timeline.'.\App\Enums\PublicConsultationTimelineEnum::keyByValue($e));
                $description = __('custom.timeline.'.\App\Enums\PublicConsultationTimelineEnum::keyByValue($e).'.description');
                $eData = [
                    'label' => $label,
                    'isActive' => false,
                    'description' => $description
                ];
                switch ($e) {
                    case PublicConsultationTimelineEnum::INCLUDE_TO_PROGRAM->value:
                        $sortedTimeline['1'] = $eData;
                        break;
                    case PublicConsultationTimelineEnum::PUBLISH_PROPOSALS_REPORT->value:
                        $sortedTimeline['5'] = $eData;
                        break;
                }
            }
        }


        //Sort events
        $timestamps = [];
        if(sizeof($sortedTimeline)) {
            //sort timestamps for multiple events
            foreach ($sortedTimeline as $key => $event) {
                $explode = explode('_', $key);
                if(sizeof($explode) == 2){
                    $timestamps[] = $explode[1];
                }
                usort($timestamps, "compareByTimeStamp");
            }
            //replace timestamp keys with sortable keys
            foreach ($sortedTimeline as $key => $event) {
                if(sizeof($timestamps)) {
                    foreach ($timestamps as $tkey => $t){
                        if (str_contains($key, $t)) {
                            $newKey = str_replace('_'.$t, '', $key);
                            $sortedTimeline[$newKey.'.'.$tkey] = $sortedTimeline[$key];
                            unset($sortedTimeline[$key]);
                        }
                    }
                }
            }

            array_multisort(array_keys($sortedTimeline), SORT_NATURAL, $sortedTimeline);
        }
        return $sortedTimeline;
    }

    public function connectedConsultationByProgram()
    {
        return DB::table('public_consultation')
            ->select([
                'public_consultation.id',
                'public_consultation_translations.title',
                'public_consultation.open_from',
                'public_consultation.open_to'
            ])
            ->join('public_consultation_translations', 'public_consultation_translations.public_consultation_id', '=', 'public_consultation.id')
            ->where('public_consultation_translations.locale', '=', app()->getLocale())
            ->where('public_consultation.id', '<>', $this->id)
            ->where(function ($q){
                $q->where(function ($q){
                    $q->whereNotNull('public_consultation.operational_program_row_id')
                        ->where('public_consultation.operational_program_row_id', '=', $this->operational_program_row_id);
                })->orWhere(function ($q){
                    $q->whereNotNull('public_consultation.legislative_program_row_id')
                        ->where('public_consultation.legislative_program_row_id', '=', $this->legislative_program_row_id);
                });
            })
            ->orderBy('public_consultation_translations.title', 'asc')
            ->get();
    }
}

<?php

namespace App\Models\Consultations;

use App\Enums\DocTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Models\ActType;
use App\Models\ConsultationLevel;
use App\Models\File;
use App\Models\Poll;
use App\Models\PublicConsultationContact;
use App\Models\RegulatoryAct;
use App\Models\StrategicDocuments\Institution;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ModelActivityExtend;

class PublicConsultation extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'description', 'short_term_reason', 'responsible_unit', 'proposal_ways'];
    const MODULE_NAME = ('custom.consultations.public_consultation');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'public_consultation';

    //activity
    protected string $logName = "public_consultation";

    protected $fillable = ['consultation_level_id', 'act_type_id',
        'legislative_program_id', 'operational_program_id', 'open_from', 'open_to', 'regulatory_act_id',
        'pris_act_id', 'importer_institution_id', 'responsible_institution_id', 'responsible_institution_address',
        'act_links', 'active', 'reg_num', 'monitorstat'
    ];

    const MIN_DURATION_DAYS = 14;
    const SHORT_DURATION_DAYS = 29;

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
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
            ]
        );
    }

    public function scopeActive($query){
        $query->where('active', 1);
    }

    protected function act_links(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value)) : $value,
        );
    }

    protected function openFrom(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? Carbon::parse($value)->format('d.m.Y') : null,
            set: fn (string|null $value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }


    protected function openTo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? Carbon::parse($value)->format('d.m.Y') : null,
            set: fn (string|null $value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function inPeriod(): Attribute
    {
        $now = Carbon::now()->format('Y-m-d');
        return Attribute::make(
            get: fn () => ($now >= $this->open_from && $this->open_to >= $now) ? __('custom.active_f') : __('custom.inactive_f'),
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
        return $this->hasOne(OperationalProgram::class, 'operational_program_id', 'id');
    }

    public function lp(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LegislativeProgram::class, 'legislative_program_id', 'id');
    }

    //TODO fix me uncomment after add pris structure
//    public function regulatoryAct()
//    {
//        return $this->hasOne(RegulatoryAct::class, 'id', 'pris_act_id');
//    }

    public function importerInstitution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Institution::class, 'importer_institution_id', 'id');
    }

    public function responsibleInstitution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Institution::class, 'responsible_institution_id', 'id');
    }


    public function prisAct(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Pris::class, 'regulatory_act_id', 'id');
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

    public function polls()
    {
        return $this->belongsToMany(Poll::class, 'public_consultation_poll', 'public_consultation_id', 'poll_id');
    }

    public function documents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
            ->orderBy('created_at', 'desc')
            ->orderBy('locale');
    }

    public function lastDocumentsByLocaleAndSection()
    {
        $documents = [];
        foreach (DocTypesEnum::docsByActType($this->act_type_id) as $docType) {
            $doc = DB::table('public_consultation')
                ->select(['files.id', 'files.doc_type', 'files.description', 'files.content_type', 'files.created_at', 'files.version'])
                ->join('files', function ($j) use ($docType){
                    $j->on('files.id_object', '=', 'public_consultation.id')
                        ->where('files.locale','=', app()->getLocale())
                        ->where('files.code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                        ->where('files.doc_type', '=', $docType);
                })
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
            ->select(['public_consultation.id', 'public_consultation_translations.title'])
            ->join('public_consultation_translations', 'public_consultation_translations.public_consultation_id', '=', 'public_consultation.id')
            ->where('public_consultation_translations.locale', '=', app()->getLocale())
            ->orderBy('public_consultation_translations.title', 'asc')
            ->get();
    }
}

<?php

namespace App\Models;

use App\Models\Consultations\PublicConsultation;
use App\Models\StrategicDocuments\Institution;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Pris extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['about', 'legal_reason', 'importer'];
    const MODULE_NAME = ('custom.pris_documents');
//
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'pris';

    //activity
    protected string $logName = "pris";

    protected $fillable = ['doc_num', 'doc_date', 'legal_act_type_id', 'institution_id', 'version',
        'protocol', 'public_consultation_id', 'newspaper_number', 'active', 'published_at'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->title;
    }

    protected function docDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? Carbon::parse($value)->format('d.m.Y') : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    public function scopePublished($query){
        $query->whereNotNull('published_at');
    }

    protected function regNum(): Attribute
    {
        return Attribute::make(
            get: fn () => ('#'.$this->doc_num.'/'.Carbon::parse($this->doc_date)->format('Y')),
        );
    }

    protected function docYear(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->doc_date)->format('Y'),
        );
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'about' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
            'legal_reason' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
            'importer' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255']
            ],
        );
    }

    public function actType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LegalActType::class, 'id', 'legal_act_type_id');
    }

    public function consultation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PublicConsultation::class, 'id', 'public_consultation_id')->withTrashed();
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Institution::class, 'id', 'institution_id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'pris_tag', 'pris_id', 'tag_id');
    }

    public function changedDocs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(self::class, 'pris_change_pris', 'changed_pris_id', 'pris_id')->withPivot(['connect_type', 'old_connect_type'])->withTrashed();
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_PRIS)
            ->orderBy('created_at', 'desc')
            ->orderBy('locale');
    }


    public static function select2AjaxOptions($filters)
    {
        $q = DB::table('pris')
            ->select(['pris.id', DB::raw('legal_act_type_translations.name || \' №\' || pris.doc_num || \' от \' || DATE_PART(\'year\', doc_date) || \' г.\' as name')])
            ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
            ->join('legal_act_type_translations', function ($j){
                $j->on('legal_act_type.id', '=', 'legal_act_type_translations.legal_act_type_id')
                    ->where('legal_act_type_translations.locale', '=', app()->getLocale());
            });
            if(isset($filters['doc_num'])) {
                $q->where('pris.doc_num', 'ilike', '%'.$filters['doc_num'].'%');
            }
            if(isset($filters['year']) && !empty($filters['year'])) {
                $yearLength = strlen($filters['year']);
                $year = $filters['year'];
                if($yearLength < 4) {
                    while(strlen($year) < 4) {
                        $year .= '0';
                    }
                }
                $from = Carbon::parse('01-01-'.$year)->format('Y-m-d');

                $to = $yearLength < 4 ? Carbon::now()->format('Y-m-d') : Carbon::parse($from)->endOfYear()->format('Y-m-d');
                $q->where(function ($q) use($from, $to){
                    $q->where('doc_date', '>=', $from)->where('doc_date', '<=', $to);
                });
            }

            if(isset($filters['actType']) && (int)$filters['actType'] > 0) {
                $q->where('pris.legal_act_type_id', '=', (int)$filters['actType']);
            }

            $q->whereNull('pris.deleted_at');

            $q->orderBy('legal_act_type_translations.name', 'asc')
            ->orderBy('pris.doc_num', 'asc');

        return $q->get();
    }
}

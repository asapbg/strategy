<?php

namespace App\Models;

use App\Models\Consultations\PublicConsultation;
use App\Models\StrategicDocuments\Institution;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class Pris extends ModelActivityExtend implements TranslatableContract, Feedable
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['about', 'legal_reason', 'importer'];
    const MODULE_NAME = ('custom.pris_documents');
//
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'pris';

    //activity
    protected string $logName = "pris";

    protected $fillable = ['doc_num', 'doc_date', 'legal_act_type_id', //'institution_id',
        'version',
        'protocol', 'public_consultation_id', 'newspaper_number', 'newspaper_year', 'active', 'published_at',
        'old_connections', 'old_id', 'old_doc_num', 'old_newspaper_full', 'connection_status', 'parentdocumentid', 'state', 'xstate'];

    /**
     * @return FeedItem
     */
    public function toFeedItem(): FeedItem
    {
        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->mcDisplayName,
            'summary' => '',
            'updated' => $this->updated_at ?? $this->created_at,
            'link' => route('pris.view', ['category' => Str::slug($this->actType?->name), 'id' => $this->id]),
            'authorName' => '',
            'authorEmail' => ''
        ]);
    }

    /**
     * We use this method for rss feed
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeedItems(): \Illuminate\Database\Eloquent\Collection
    {
        return static::with(['translations', 'actType', 'actType.translations'])
            ->Published()
            ->where('active', '=', 1)
            ->orderByRaw("(case when updated_at is null then created_at else updated_at end) desc")
            ->limit(config('feed.items_per_page'), 20)
            ->get();
    }

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
        $query->whereNotNull('pris.published_at');
    }

    public function scopeDecrees($query){
        $query->where('pris.legal_act_type_id', '=', LegalActType::TYPE_DECREES);
    }

    protected function regNum(): Attribute
    {
        return Attribute::make(
            get: fn () => (__('custom.number_symbol').$this->doc_num.'/'.Carbon::parse($this->doc_date)->format('Y')),
        );
    }

    protected function oldConnectionsHtml(): Attribute
    {
        return Attribute::make(
            get: fn () => !empty($this->old_connections) ? implode('<br>', explode('|', $this->old_connections)) : '',
        );
    }

    protected function displayName(): Attribute
    {
        $actName = $this->actType ? __('custom.'.Str::slug($this->actType->name).'_slug_one') : '';
        return Attribute::make(
            get: fn () => ($actName.' '.__('custom.number_symbol').$this->doc_num.'/'.Carbon::parse($this->doc_date)->format('Y').' '.__('custom.of').' '.($this->institution ? $this->institution->name : '---')),
        );
    }

    protected function mcDisplayName(): Attribute
    {
        $actName = $this->actType ? __('custom.'.Str::slug($this->actType->name).'_slug_one') : '';
        return Attribute::make(
            get: fn () => __('custom.pris_program_title', ['actType' => $actName, 'number' => __('custom.number_symbol').$this->doc_num, 'year' => Carbon::parse($this->doc_date)->format('Y')])
        );
    }

    protected function docYear(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->doc_date)->format('Y'),
        );
    }

    protected function newspaper(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->newspaper_number ? __('custom.newspaper', ['number' => $this->newspaper_number , 'year' => $this->newspaper_year ?? '---']) : (!empty($this->old_newspaper_full) ? $this->old_newspaper_full : null)
        );
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'about' => [
                'type' => 'summernote',
                'rules' => ['required', 'string'],
                'required_all_lang' => true
            ],
            'legal_reason' => [
                'type' => 'summernote',
                'rules' => ['required', 'string'],
                'required_all_lang' => false
            ],
            'importer' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255'],
                'required_all_lang' => true
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

    public function decreesConsultation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PublicConsultation::class, 'pris_id', 'id');
    }

    /**
     * @deprecated
     * @return HasOne
     */
    public function institution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Institution::class, 'id', 'institution_id');
    }

    public function institutions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'pris_institution', 'pris_id', 'institution_id');
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

            if(isset($filters['consultationId']) && $filters['consultationId'] > 0) {
                $q->where('public_consultation_id', (int)$filters['consultationId']);
            }

            $q->whereNull('pris.deleted_at');

            $q->orderBy('legal_act_type_translations.name', 'asc')
            ->orderBy('pris.doc_num', 'asc');

        return $q->get();
    }

    /**
     * @return morphMany
     */
    public function subscriptions()
    {
        return $this->morphMany(UserSubscribe::class, 'subscribable');
    }

    /**
     * Use in public list page and subscription check
     * @param array $filter
     */
    public static function list(array $filter){
        return self::select('pris.*')
            ->Published()
            ->with(['translations', 'actType', 'actType.translations', 'institutions', 'institutions.translation'])
            ->leftJoin('pris_institution', 'pris_institution.pris_id', '=', 'pris.id')
            ->leftJoin('pris_translations', function ($j){
                $j->on('pris_translations.pris_id', '=', 'pris.id')
                    ->where('pris_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('institution', 'institution.id', '=', 'pris_institution.institution_id')
            ->leftJoin('institution_translations', function ($j){
                $j->on('institution_translations.institution_id', '=', 'institution.id')
                    ->where('institution_translations.locale', '=', app()->getLocale());
            })
            ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
            ->join('legal_act_type_translations', function ($j){
                $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
                    ->where('legal_act_type_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('pris_tag', 'pris_tag.pris_id', '=', 'pris.id')
            ->leftJoin('tag', 'pris_tag.tag_id', '=', 'tag.id')
            ->leftJoin('tag_translations', function ($j){
                $j->on('tag_translations.tag_id', '=', 'tag.id')
                    ->where('tag_translations.locale', '=', app()->getLocale());
            })
            ->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ARCHIVE)
            ->FilterBy($filter)
            ->get();
    }
}

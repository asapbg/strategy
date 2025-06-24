<?php

namespace App\Models;

use App\Models\Consultations\PublicConsultation;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use LaravelIdea\Helper\App\Models\_IH_StrategicDocument_C;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class StrategicDocument extends ModelActivityExtend implements TranslatableContract, Feedable
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const DEFAULT_IMG = 'images'.DIRECTORY_SEPARATOR.'ms-2023.jpg';
    const TRANSLATABLE_FIELDS = ['title', 'description'];
    const MODULE_NAME = ('custom.strategic_documents');
    const HOME_PAGINATE = 4;
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'strategic_document';

    //activity
    protected string $logName = "strategic_document";

    protected $fillable = ['strategic_document_level_id', 'policy_area_id', 'strategic_document_type_id',
        'strategic_act_type_id', 'strategic_act_number', 'strategic_act_link', 'accept_act_institution_type_id',
        'pris_act_id', 'document_date', 'public_consultation_id', 'active', 'link_to_monitorstat',
        'document_date_accepted', 'document_date_expiring', 'parent_document_id', 'ekatte_area_id', 'ekatte_municipality_id'];


    /**
     * @return FeedItem
     */
    public function toFeedItem(): FeedItem
    {
        $extraInfo = '';

        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->title,
            //TODO debug why description is bad for rss
//            'summary' => $extraInfo.str_replace(['&bull;'], '', $this->description),
            'summary' => '',
            'updated' => $this->updated_at,
            'created' => $this->created_at,
            'enclosure' => asset(self::DEFAULT_IMG),
            'link' => route('strategy-document.view', ['id' => $this->id]),
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
        $request = request();
        $requestFilter = $request->all();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'document_date_accepted';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        return static::with(['translations'])
            ->leftJoin('strategic_document_translations', function ($j){
                $j->on('strategic_document_translations.strategic_document_id', '=', 'strategic_document.id')
                    ->where('strategic_document_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('strategic_document_type_translations', function ($j){
                $j->on('strategic_document_type_translations.strategic_document_type_id', '=', 'strategic_document.strategic_document_type_id')
                    ->where('strategic_document_type_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'strategic_document.policy_area_id')
            ->leftJoin('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->Active()
            ->whereNull('strategic_document.parent_document_id')
            ->orderByRaw("strategic_document.created_at desc")
            ->FilterBy($requestFilter)
            ->SortedBy($sort,$sortOrd)
//            ->limit(config('feed.items_per_page'), 20)
            ->get();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('strategic_document.active', true);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInstitutionListing(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder {
        return $query->whereHas('pris', function ($query) {
            $query->whereHas('institutions', function ($query) {
                $query->where('id', auth()->user()->institution_id);
            });
        });
    }


    /**
     * Dates
     */
    protected function documentDate(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? displayDate($value) : '',
            set: fn (string|null $value) => !empty($value) ?  databaseDate($value) : null
        );
    }

    protected function documentDateAccepted(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? Carbon::parse($value)->format('d.m.Y') : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d H:i:s') : null
        );
    }

    protected function documentDateExpiring(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? Carbon::parse($value)->format('d.m.Y') : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d H:i:s') : null
        );
    }

    protected function ogDescription(): Attribute
    {
//        Област на политика: <област на политика>\nОписание: <описание, отрязано до 100 символа>
        return Attribute::make(
            get: function () {
                return trans_choice('custom.field_of_actions', 1).': '.$this->policyArea?->name.' | '.__('custom.description').': '.substr(clearAfterStripTag(strip_tags($this->description)), 0, 100);
            }
        );
    }

    /**
     * @return string
     */
    public function getDocumentDisplayNameAttribute(): string
    {
        $expiringDate = $this->document_date_expiring == null ? trans('custom.infinite') : Carbon::parse($this->document_date_expiring)->format('d-m-Y');
        return $this->title .' ';

    }

    /**
     * @return string
     */
    public function getDocumentStatusAttribute(): string
    {
        $currentDate = Carbon::now();
        $dateAccepted = Carbon::parse($this->document_date_accepted);
        $dateExpired = Carbon::parse($this->document_date_expiring);

        if ($currentDate->between($dateAccepted, $dateExpired, true)) {
            return trans('custom.strategic_document_active');
        } elseif ($currentDate->greaterThan($dateExpired)) {
            return trans('custom.strategic_document_expired');
        } else {
            return trans('custom.pending');
        }
    }

    /**
     * @return string
     */
    public function getDocumentLinkAttribute(): string
    {
        $url = url('strategy-documents', ['id' => $this->id]);

        return '<a href="' . $url . '">' . $this->title . '</a>';
    }

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->title;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'text',
                'rules' => ['required', 'string'],
                'required_all_lang' => true
            ],
            'description' => [
                'type' => 'summernote',
                'rules' => ['required', 'string'],
                'required_all_lang' => false
            ],
        );
    }

    public function files()
    {
        return $this->hasMany(StrategicDocumentFile::class, 'strategic_document_id', 'id')->orderBy('ord');
    }
    public function filesByLocale()
    {
        return $this->hasMany(StrategicDocumentFile::class, 'strategic_document_id', 'id')
            ->where('locale', '=', app()->getLocale())
            ->orderBy('ord');
    }

    public function documents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StrategicDocumentChildren::class, 'strategic_document_id', 'id')->whereNull('parent_id')->orderBy('id');
    }

    //!!! TODO remove after level and field of actions changes
//    public function documentLevel(): \Illuminate\Database\Eloquent\Relations\HasOne
//    {
//        return $this->hasOne(StrategicDocumentLevel::class, 'id', 'strategic_document_level_id');
//    }

    public function acceptActInstitution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AuthorityAcceptingStrategic::class, 'id', 'accept_act_institution_type_id');
    }

    public function documentType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicDocumentType::class, 'id', 'strategic_document_type_id');
    }

    public function strategicActType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StrategicActType::class, 'id', 'strategic_act_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function policyArea(): BelongsTo
    {
        return $this->belongsTo(FieldOfAction::class);
    }

    public function pris(): BelongsTo
    {
        return $this->belongsTo(Pris::class, 'pris_act_id');
    }


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    //!!! TODO remove after level and field of actions changes
    public function ekatteArea()
    {
        return $this->belongsTo(EkatteArea::class, 'ekatte_area_id');
    }
//!!! TODO remove after level and field of actions changes
    public function ekatteManiputlicity()
    {
        return $this->belongsTo(EkatteArea::class, 'ekatte_municipality_id');
    }

    /**
     * @return BelongsTo
     */
    public function publicConsultation(): BelongsTo
    {
        return $this->belongsTo(PublicConsultation::class, 'public_consultation_id');
    }

    /**
     * @return BelongsTo
     */
    public function parentDocument(): BelongsTo
    {
        return $this->belongsTo(StrategicDocument::class, 'parent_document_id');
    }

    public static function select2AjaxOptions($filters)
    {
        $q = DB::table('strategic_document')
            ->select(['strategic_document.id', DB::raw('strategic_document_translations.title as name')])
            ->join('strategic_document_translations', function ($j){
                $j->on('strategic_document.id', '=', 'strategic_document_translations.strategic_document_id')
                    ->where('strategic_document_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'strategic_document.policy_area_id')
            ->leftJoin('strategic_document as child', 'strategic_document.id', '=', 'strategic_document.parent_document_id');

        if(isset($filters['sd_document_id']) && $filters['sd_document_id']){
            $q->where('child.id', '=', $filters['sd_document_id']);
            if((isset($filters['field_of_action_id']) && $filters['field_of_action_id'] > 0) || (isset($filters['search']))) {
                $q->orWhere(function ($q) use($filters){
                    if(isset($filters['field_of_action_id']) && $filters['field_of_action_id'] > 0) {
                        $q->where('field_of_actions.id', '=', $filters['field_of_action_id']);
                    }
                    if(isset($filters['search'])) {
                        $q->where('strategic_document_translations.title', 'ilike', '%'.$filters['search'].'%');
                    }
                });
            }
        } else {
            if(isset($filters['field_of_action_id']) && $filters['field_of_action_id'] > 0) {
                $q->where('field_of_actions.id', '=', $filters['field_of_action_id']);
            }

            if(isset($filters['search'])) {
                $q->where('strategic_document_translations.title', 'ilike', '%'.$filters['search'].'%');
            }
        }

        $q->orderBy('strategic_document_translations.title', 'asc');
        $q->groupBy('strategic_document.id', 'strategic_document_translations.title');

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
     * @param string $sort
     * @param string $sortOrd
     * @param int $paginate
     * @return StrategicDocument[]|LengthAwarePaginator|_IH_StrategicDocument_C
     */
    public static function list(array $filter, string $sort = 'title', string $sortOrd = 'desc', int $paginate = self::PAGINATE){
        return self::select('strategic_document.*')
            ->Active()
            ->with(['translations', 'policyArea', 'policyArea.translations', 'documentType.translations'])
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'strategic_document.policy_area_id')
            ->leftJoin('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('strategic_document_translations', function ($j){
                $j->on('strategic_document_translations.strategic_document_id', '=', 'strategic_document.id')
                    ->where('strategic_document_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('strategic_document_type', 'strategic_document_type.id', '=', 'strategic_document.strategic_document_type_id')
            ->leftJoin('strategic_document_type_translations', function ($j){
                $j->on('strategic_document_type_translations.strategic_document_type_id', '=', 'strategic_document_type.id')
                    ->where('strategic_document_type_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($filter)
            ->SortedBy($sort,$sortOrd)
            //->GroupBy('strategic_document.id')
            ->paginate($paginate);
    }
}

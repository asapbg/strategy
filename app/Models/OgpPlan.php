<?php

namespace App\Models;

use App\Enums\DocTypesEnum;
use App\Enums\OgpStatusEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class OgpPlan extends ModelActivityExtend implements TranslatableContract, Feedable
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const DEFAULT_IMG = 'images'.DIRECTORY_SEPARATOR.'ms-2023.jpg';
    const MODULE_NAME = ('custom.plans');
    const TRANSLATABLE_FIELDS = ['name', 'content', 'report_title', 'report_content'];

    public $timestamps = true;

    protected $table = 'ogp_plan';

    //activity
    protected string $logName = "ogp_plan";

    protected $fillable = ['from_date', 'to_date', 'active', 'author_id',
        'ogp_status_id', 'version_after_public_consultation_pdf', 'final_version_pdf',
        'from_date_develop', 'to_date_develop', 'national_plan', 'develop_plan_id',
        'report_evaluation_published_at', 'self_evaluation_published_at'];
    protected $translatedAttributes = OgpPlan::TRANSLATABLE_FIELDS;

    public function toFeedItem(): FeedItem
    {
        $extraInfo = '';
        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->name,
            'summary' => $extraInfo.$this->content,
            'updated' => $this->updated_at,
            'created' => $this->created_at,
            'enclosure' => asset(self::DEFAULT_IMG),
            'link' => route('ogp.national_action_plans.show', ['id' => $this->id]),
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
        return static::with(['translations'])
            ->where('active', '=', 1)
            ->whereRelation('status', 'type', OgpStatusEnum::ACTIVE->value)
            ->where('national_plan', '=', 1)
            ->FilterBy($requestFilter)
            ->orderByRaw("created_at desc")
            ->limit(config('feed.items_per_page'), 20)
            ->get();
    }

    public function scopeActive($query)
    {
        return $query->where('ogp_plan.active', '=', true);
    }

    public function scopeNational($query)
    {
        return $query->where('ogp_plan.national_plan', '=', 1);
    }

    public function scopeNotNational($query)
    {
        return $query->where('ogp_plan.national_plan', '=', 0);
    }

    protected function fromDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? displayDate($value) : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function toDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? displayDate($value) : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function fromDateDevelop(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? displayDate($value) : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function toDateDevelop(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? displayDate($value) : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function reportEvaluationPublishedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? displayDate($value) : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function selfEvaluationPublishedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? displayDate($value) : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function ogDescription(): Attribute
    {
//        Отворени области: <област1> (до <срок1>), <областN> (до <срокN>)
        if($this->national_plan){
            $txt = substr(clearAfterStripTag(strip_tags($this->content)), 0, 180);
        } else{
            $txt = __('custom.dev_new_plan_title');
            if($this->areas->count()){
                $txt = __('custom.open_areas').': ';
                $first = true;
                foreach ($this->areas as $area){
                    $txt .= (!$first ? ', ' : '').$area->area->name.' ('.__('custom.to').' '.displayDate($this->to_date_develop).')';
                    $first = false;
                }
            }
        }

        return Attribute::make(
            get: function () use ($txt){
                return $txt;
            }
        );
    }

    public function status(): HasOne
    {
        return $this->hasOne(OgpStatus::class, 'id', 'ogp_status_id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function areas(): HasMany
    {
        return $this->hasMany(OgpPlanArea::class, 'ogp_plan_id', 'id')->orderBy('ord', 'asc');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(OgpPlanSchedule::class, 'ogp_plan_id', 'id');
    }

    public function developPlan(): HasOne
    {
        return $this->hasOne(OgpPlan::class, 'id', 'develop_plan_id');
    }

    public function plan(): HasOne
    {
        return $this->hasOne(OgpPlan::class, 'develop_plan_id', 'id');
    }

    public function versionAfterConsultation()
    {
        return $this->hasMany(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_OGP)
            ->where('doc_type', '=', DocTypesEnum::OGP_VERSION_AFTER_CONSULTATION->value)
            ->where('locale', '=', app()->getLocale())
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function reportEvaluation(): HasMany
    {
        return $this->hasMany(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_OGP)
            ->where('doc_type', '=', DocTypesEnum::OGP_REPORT_EVALUATION->value)
            //->where('locale', '=', app()->getLocale())
            ->orderBy('created_at', 'desc');
    }

    public function reportEvaluationByLang(): HasMany
    {
        return $this->hasMany(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_OGP)
            ->where('doc_type', '=', DocTypesEnum::OGP_REPORT_EVALUATION->value)
            ->where('locale', '=', app()->getLocale())
            ->orderBy('created_at', 'desc');
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'name' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255'],
                'required_all_lang' => true
            ],
            'content' => [
                'type' => 'summernote',
                'rules' => ['required'],
                'required_all_lang' => false
            ],
            'report_title' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:2000'],
                'required_all_lang' => true
            ],
            'report_content' => [
                'type' => 'summernote',
                'rules' => ['nullable'],
                'required_all_lang' => false
            ],
        );
    }

    public static function list($filter)
    {
        return OgpPlan::Active()
            ->National()
            ->whereRelation('status', 'type', OgpStatusEnum::ACTIVE->value)
            ->FilterBy($filter)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

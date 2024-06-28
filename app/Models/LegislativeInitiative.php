<?php

namespace App\Models;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\Consultations\OperationalProgramRow;
use App\Models\StrategicDocuments\Institution;
use App\Traits\FilterSort;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

/**
 * @property int        $id
 * @property int        $author_id
 *
 * @property Collection $votes
 *
 * @method static find(mixed $legislative_initiative_id)
 */
class LegislativeInitiative extends ModelActivityExtend implements Feedable
{

    use FilterSort;

    const PAGINATE = 20;
    const DEFAULT_IMG = 'images'.DIRECTORY_SEPARATOR.'ms-2023.jpg';
    const HOME_PAGINATE = 4;
    const MODULE_NAME = ('custom.nomenclatures.legislative_initiative');

    public $timestamps = true;

    protected $table = 'legislative_initiative';

    //activity
    protected string $logName = "legislative_initiative";

    protected $fillable = ['author_id', 'law_paragraph', 'law_text', 'description', 'motivation', 'law_id', 'cap', 'ready_to_send', 'active_support', 'send_at', 'end_support_at'];

    /**
     * @return FeedItem
     */
    public function toFeedItem(): FeedItem
    {
        $extraInfo = '';
        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->facebookTitle,
            'summary' => $extraInfo.$this->description,
            'updated' => $this->updated_at,
            'created' => $this->created_at,
            'enclosure' => asset(self::DEFAULT_IMG),
            'link' => route('legislative_initiatives.view', ['item' => $this->id]),
            'authorName' => $this->user?->fullName(),
            'authorEmail' => ''
        ]);
    }

    /**
     * We use this method for rss feed
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeedItems(): \Illuminate\Database\Eloquent\Collection
    {
        return static::orderByRaw("created_at desc")
//            ->limit(config('feed.items_per_page'), 20)
            ->get();
    }


    /**
     * Get the model name
     */
    public function getModelName()
    {
        return $this->name;
    }

    protected function facebookTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => __('custom.change_f') .' '. __('custom.in') .' '.$this->law?->name,
        );
    }

    protected function ogDescription(): Attribute
    {
        return Attribute::make(
//            По член: <член>\nПредложение: <предложение за промяна, отрязано до първите 50 символа>
            get: function () {
                return __('custom.by_article').': '.(substr(strip_tags($this->law_paragraph), 0, 50)). ' | '.__('custom.proposal').': '.(substr(strip_tags($this->description), 0, 50));
            }
        );
    }

    /**
     * Value
     */
    protected function daysLeft(): Attribute
    {
        $from = Carbon::now();
        $to = !empty($this->active_support) && Carbon::now()->format('Y-m-d H:i:s') < $this->active_support && $this->status == LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value ? Carbon::parse($this->active_support) : null;
        return Attribute::make(
            get: fn () => !empty($to) ? $from->diffInDays($to) : 0,
        );
    }

    /**
     * Value
     */
    protected function endAfterDays(): Attribute
    {
        $from = Carbon::parse($this->created_at);
        $to = !empty($this->end_support_at) ? Carbon::parse($this->end_support_at) : null;
        return Attribute::make(
            get: fn () => !empty($to) ? ($from->diffInDays($to) > 0 ? $from->diffInDays($to) : 1) : 0,
        );
    }

    public function scopeExpired($query){
        $query->where('status', '=', LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value)
            ->whereNotNull('active_support')
            ->where('active_support', '<', Carbon::now()->format('Y-m-d H:i:s'));
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'description' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
        );
    }

//    public function operationalProgram()
//    {
//        return $this->belongsTo(OperationalProgramRow::class, 'operational_program_id', 'operational_program_id');
//    }

//    public function operationalProgramTitle()
//    {
//        return $this->belongsTo(OperationalProgramRow::class, 'operational_program_id', 'operational_program_id')
//            ->where('dynamic_structures_column_id', config('lp_op_programs.op_ds_col_title_id'));
//    }

    public function votes(): HasMany
    {
        return $this->hasMany(LegislativeInitiativeVote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(LegislativeInitiativeComment::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id')->withTrashed();
    }

    public function receivers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'legislative_initiative_receiver', 'legislative_initiative_id', 'institution_id')->withPivot(['created_at']);
    }

    public function getStatus(int $value): LegislativeInitiativeStatusesEnum
    {
        return LegislativeInitiativeStatusesEnum::from($value);
    }

    public function setStatus(LegislativeInitiativeStatusesEnum $value): void
    {
        $this->attributes['status'] = $value->value;
    }


    public function userHasLike(): bool
    {
        $userId = auth()->user() ? auth()->user()->id : 0;
        $cnt = $this->likes->filter(function ($item) use ($userId){
            return $item->user_id == $userId;
        })->count();
        return (bool)$cnt;
//        if (auth()->user()) {
//            return $this->likes()->where('user_id', auth()->user()->id)->exists();
//        }
//
//        return false;
    }

    public function userHasDislike(): bool
    {
        $userId = auth()->user() ? auth()->user()->id : 0;
        $cnt = $this->dislikes->filter(function ($item) use ($userId){
            return $item->user_id == $userId;
        })->count();
        return (bool)$cnt;

//        if (auth()->user()) {
//            return $this->dislikes()->where('user_id', auth()->user()->id)->exists();
//        }
//
//        return false;
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeInitiativeVote::class)->where('is_like', true);
    }

    public function dislikes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeInitiativeVote::class)->where('is_like', false);
    }

    public function law(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Law::class, 'id', 'law_id')->withTrashed();
    }

    public function institutions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'legislative_initiative_institution', 'legislative_initiative_id', 'institution_id');
    }

    public function countSupport(): int
    {
        return ($this->likes->count() - $this->dislikes->count());
    }

    public function countLikes(): int
    {
        return $this->likes->count();
    }

    public function countDislikes(): int
    {
        return $this->dislikes->count();
    }

    /**
     * Use in subscription check
     * @param array $filter
     */
    public static function list(array $filter){
        return self::select('legislative_initiative.*')
            ->leftJoin('law', 'law.id', '=', 'legislative_initiative.law_id')
            ->leftJoin('law_translations', function ($j){
                $j->on('law_translations.law_id', '=', 'law.id')
                    ->where('law_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('law_institution', function ($query) use ($filter) {
                $query->on('law_institution.law_id', '=', 'law.id')->when(!empty($filter['institution']),function ($query) use ($filter) {
                    $query->whereIn('law_institution.institution_id',$filter['institution']);
                });
            })
            ->when(!empty($filter['law']), function ($query) use ($filter) {
                $query->whereIn('law.id', $filter['law']);
            })
            ->when(!empty($filter['keywords']), function ($query) use ($filter){
                $query->orWhere('legislative_initiative.description', 'ilike', '%' . $filter['keywords'] . '%')
                    ->orWhereHas('user', function ($query) use ($filter) {
                        $query->where('first_name', 'ilike', '%' . $filter['keywords'] . '%');
                        $query->orWhere('middle_name', 'ilike', '%' . $filter['keywords'] . '%');
                        $query->orWhere('last_name', 'ilike', '%' . $filter['keywords'] . '%');
                    });
            })
            ->groupBy('legislative_initiative.id')
            ->get();
    }
}

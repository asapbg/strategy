<?php

namespace App\Models;

use App\Enums\PollStatusEnum;
use App\Models\Consultations\PublicConsultation;
use App\Traits\FilterSort;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use function Clue\StreamFilter\fun;


class Poll extends ModelActivityExtend implements Feedable
{
    use FilterSort;

    const DEFAULT_IMG = 'images'.DIRECTORY_SEPARATOR.'ms-2023.jpg';
    const PAGINATE = 20;
    const MODULE_NAME = ('custom.polls');
    const MORE_THEN_ONE_ANSWER = true;

    public $timestamps = true;

    protected $table = 'poll';
    protected $fillable = ['name', 'user_id', 'consultation_id', 'status', 'start_date', 'end_date', 'is_once', 'only_registered'];

    //activity
    protected string $logName = "poll";

    protected $guarded = [];

    /**
     * @return FeedItem
     */
    public function toFeedItem(): FeedItem
    {
        $extraInfo = '';
        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->name,
            'summary' => $extraInfo,
            'updated' => $this->updated_at,
            'created' => $this->created_at,
            'enclosure' => asset(self::DEFAULT_IMG),
            'link' => Carbon::parse($this->end_date)->format('Y-m-d') < databaseDate(Carbon::now()) ? route('poll.statistic', ['id' => $this->id]) : route('poll.show', ['id' => $this->id]),
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
        $contentSearch = $requestFilter['content'] ?? null;
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'id';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');
        return static::select('poll.*')->Public()
            ->leftjoin('poll_question', 'poll_question.poll_id', '=', 'poll.id')
            ->leftjoin('poll_question_option', 'poll_question_option.poll_question_id', '=', 'poll_question.id')
            ->when($contentSearch, function ($query) use($contentSearch){
                return $query->where(function ($query) use ($contentSearch){
                    $query->where('poll_question.name', 'ilike', '%'.$contentSearch.'%')
                        ->orWhere('poll_question_option.name', 'ilike', '%'.$contentSearch.'%')
                        ->orWhere('poll.name', 'ilike', '%'.$contentSearch.'%');
                });
            })
            ->FilterBy($requestFilter)
            ->whereDoesntHave('consultations')
            ->groupBy('poll.id')
            ->orderByRaw("poll.created_at desc")
            ->SortedBy($sort,$sortOrd)
            ->limit(config('feed.items_per_page'), 20)
            ->get();
    }
    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public function scopeActive($query)
    {
        $query->where('status', '<>', PollStatusEnum::INACTIVE->value);
    }

    public function scopePublic($query)
    {
        $query->where('poll.start_date', '<=', databaseDate(Carbon::now()))
            ->where('poll.status', '<>', PollStatusEnum::INACTIVE->value);
    }

    public function scopeNotExpired($query)
    {
        $query->where(function ($query) {
            $query->where('end_date', '>=', databaseDate(Carbon::now()))->orWhereNull('end_date');
        });
    }

    public function scopeExpired($query)
    {
        $query->where(function ($query) {
            $query->where('end_date', '<', databaseDate(Carbon::now()));
        });
    }

    public function scopeByUserPermission($query)
    {
        $user = auth()->user();
        if( $user ) {
            //if not super admin
            if( !$user->hasRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE]) ) {
                if( $user->can(['manage.pools']) ){
                    //only polls where user is the author
                    $query->where('poll.user_id', '=', $user->id);
                } else if( $user->can(['manage.advisory']) ) {
                    //only polls where OK is from user institution
                    $query->whereHas('consultations', function ($query) use($user){
                        $query->where('public_consultation.importer_institution_id', '=', $user->institution_id);
                    });
                } else{
                    //to return empty list - just in case
                    $query->where('poll.user_id', '=', 0);
                }
            }
        }
    }

    protected function inPeriod(): Attribute
    {
        $now = databaseDate(Carbon::now()->format('Y-m-d'));
        return Attribute::make(
            get: fn ($value) => $this->status == PollStatusEnum::ACTIVE->value &&
                (
                    databaseDate($this->start_date) <= $now
                    && (databaseDate($this->end_date) >= $now || is_null($this->end_date))
                )
        );
    }

    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => displayDate($value),
            set: fn ($value) => databaseDate(Carbon::parse($value)->format('Y-m-d')),
        );
    }

    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !is_null($value) ? displayDate($value) : '',
            set: fn ($value) => !is_null($value) ? databaseDate(Carbon::parse($value)->format('Y-m-d')) : null,
        );
    }

    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PollQuestion::class, 'poll_id','id');
    }

    public function entries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AccountPoll::class, 'poll_id','id');
    }

    public function consultations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PublicConsultation::class, 'public_consultation_poll', 'poll_id', 'public_consultation_id');
    }

    public function getStats()
    {
        $statistic = [];
        $statisticDB =  DB::table('poll')
            ->select(
                DB::raw('count(distinct(user_poll.id)) as users'),
                DB::raw('poll_question_option.poll_question_id as question_id'),
                DB::raw('poll_question_option.id as option_id'),
                DB::raw('sum(CASE WHEN user_poll_option.poll_question_option_id IS NOT NULL THEN 1 ELSE 0 END) as option_cnt'))
            ->join('poll_question', 'poll_question.poll_id', '=', 'poll.id')
            ->join('poll_question_option', 'poll_question_option.poll_question_id', '=', 'poll_question.id')
            ->join('user_poll', 'user_poll.poll_id', '=', 'poll.id')
            ->leftJoin('user_poll_option', function ($j){
                $j->on('user_poll_option.user_poll_id', '=', 'user_poll.id')
                    ->on('user_poll_option.poll_question_option_id', '=', 'poll_question_option.id');
            })
            ->where('poll.id', $this->id)
            ->whereNull('poll.deleted_at')
            ->whereNull('user_poll.deleted_at')
            ->whereNull('poll_question.deleted_at')
            ->whereNull('poll_question_option.deleted_at')
            ->whereColumn('user_poll_option.user_poll_id', '=', 'user_poll.id')
            ->groupBy(['poll_question.id', 'poll_question_option.id'])
            ->get();

        if($statisticDB->count()) {
            foreach ($statisticDB as $row) {
                if(!isset($statistic[$row->question_id])) {
                    $statistic[$row->question_id] = [
                        'users' => $row->users,
                        'all_answers' => 0,
                        'options' => []
                    ];
                }
                $statistic[$row->question_id]['all_answers'] += $row->option_cnt;
                $statistic[$row->question_id]['options'][$row->option_id] = $row->option_cnt;

            }
        }
        return $statistic;
    }

    public static function optionsList()
    {
            return DB::table('poll')
            ->select(['poll.id', 'poll.name'])
            ->orderBy('poll.name', 'asc')
            ->get();
    }

    public static function list($filter)
    {
        $contentSearch = $filter['content'] ?? null;
        return self::select('poll.id')
            ->Active()
            ->Public()
            ->FilterBy($filter)
            ->leftJoin('public_consultation_poll', 'public_consultation_poll.poll_id', '=', 'poll.id')
            ->leftjoin('poll_question', 'poll_question.poll_id', '=', 'poll.id')
            ->leftjoin('poll_question_option', 'poll_question_option.poll_question_id', '=', 'poll_question.id')
            ->when($contentSearch, function ($query) use($contentSearch){
                return $query->where(function ($query) use ($contentSearch){
                    $query->where('poll_question.name', 'ilike', '%'.$contentSearch.'%')
                        ->orWhere('poll_question_option.name', 'ilike', '%'.$contentSearch.'%')
                        ->orWhere('poll.name', 'ilike', '%'.$contentSearch.'%');
                });
            })
            ->whereNotNull('poll_question.id')
            ->whereNull('public_consultation_poll.poll_id')
            ->groupBy('poll.id')
            ->get();
    }
}

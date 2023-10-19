<?php

namespace App\Models;

use App\Enums\PollStatusEnum;
use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use illuminate\Database\Eloquent\SoftDeletes;

class Poll extends ModelActivityExtend
{
    use FilterSort, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.polls');

    public $timestamps = true;

    protected $table = 'poll';

    //activity
    protected string $logName = "poll";

    protected $guarded = [];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public function scopeActive($query)
    {
        $query->where('status', '=', PollStatusEnum::ACTIVE->value);
    }

    public function scopeNotExpired($query)
    {
        $query->where(function ($query) {
            $query->where('end_date', '>', databaseDate(Carbon::now()))->orWhereNull('end_date');
        });
    }

    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => displayDate($value),
            set: fn ($value) => databaseDate($value),
        );
    }

    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !is_null($value) ? displayDate($value) : $value,
            set: fn ($value) => !is_null($value) ? databaseDate($value) : null,
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

    public static function getStats($id)
    {
        return DB::table('polls')
            ->select(
//                DB::raw('poll_questions.id as question_id'),
                DB::raw('poll_question_option.id as option_id'),
                DB::raw('sum(CASE WHEN account_poll_options.poll_question_option_id IS NOT NULL THEN 1 ELSE 0 END) as option_cnt'))
            ->join('poll_question', 'poll_question.poll_id', '=', 'polls.id')
            ->join('poll_question_option', 'poll_question_option.poll_question_id', '=', 'poll_question.id')
            ->join('account_polls', 'account_polls.poll_id', '=', 'polls.id')
            ->leftJoin('account_poll_options', 'account_poll_options.poll_question_option_id', '=', 'poll_question_option.id')
            ->where('polls.id', (int)$id)
            ->whereNull('polls.deleted_at')
            ->whereNull('account_polls.deleted_at')
            ->whereNull('poll_question.deleted_at')
            ->whereNull('poll_question_option.deleted_at')
            ->whereColumn('account_poll_options.account_poll_id', '=', 'account_polls.id')
            ->groupBy(['poll_question.id', 'poll_question_option.id'])
            ->get();
    }

    public static function optionsList()
    {
            return DB::table('poll')
            ->select(['poll.id', 'poll.name'])
            ->orderBy('poll.name', 'asc')
            ->get();
    }
}

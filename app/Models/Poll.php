<?php

namespace App\Models;

use App\Enums\PollStatusEnum;
use App\Models\Consultations\PublicConsultation;
use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class Poll extends ModelActivityExtend
{
    use FilterSort, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.polls');
    const MORE_THEN_ONE_ANSWER = true;

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

    public function scopePublic($query)
    {
        $query->where('poll.start_date', '<', databaseDate(Carbon::now()))
            ->where('poll.status', '<>', PollStatusEnum::INACTIVE->value);
    }

    public function scopeNotExpired($query)
    {
        $query->where(function ($query) {
            $query->where('end_date', '>', databaseDate(Carbon::now()))->orWhereNull('end_date');
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
        $now = databaseDate(Carbon::now());
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

    public function consultations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PublicConsultation::class, 'public_consultation_poll', 'poll_id', 'public_consultation_id');
    }

    public static function getStats($id)
    {
        return DB::table('polls')
            ->select(
//                DB::raw('poll_questions.id as question_id'),
                DB::raw('poll_question_option.id as option_id'),
                DB::raw('sum(CASE WHEN user_poll_option.poll_question_option_id IS NOT NULL THEN 1 ELSE 0 END) as option_cnt'))
            ->join('poll_question', 'poll_question.poll_id', '=', 'polls.id')
            ->join('poll_question_option', 'poll_question_option.poll_question_id', '=', 'poll_question.id')
            ->join('user_poll', 'user_poll_option.poll_id', '=', 'polls.id')
            ->leftJoin('user_poll_option', 'user_poll_option.poll_question_option_id', '=', 'poll_question_option.id')
            ->where('polls.id', (int)$id)
            ->whereNull('polls.deleted_at')
            ->whereNull('user_poll.deleted_at')
            ->whereNull('poll_question.deleted_at')
            ->whereNull('poll_question_option.deleted_at')
            ->whereColumn('user_poll_option.user_poll_id', '=', 'user_poll.id')
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

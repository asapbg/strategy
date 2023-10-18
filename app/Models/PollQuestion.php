<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class PollQuestion extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CausesActivity;

    protected string $logName = "question";
    const MODULE_NAME = ('custom.poll_questions');

    protected $table = 'poll_question';
    public $timestamps = true;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName($this->logName);
    }
    /**
     * Get the object's name
     */
    public function getModelName() {
        return $this->name;
    }

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PollQuestionOption::class, 'poll_question_id','id');
    }

    public function poll(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Poll::class, 'id','poll_id');
    }
}

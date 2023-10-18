<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class PollQuestionOption extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, CausesActivity;

    protected string $logName = "answer_option";
    const MODULE_NAME = ('custom.poll_answers');

    protected $table = 'poll_question_options';
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
}

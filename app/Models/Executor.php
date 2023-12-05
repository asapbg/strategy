<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Executor extends Model
{
    use SoftDeletes;
    use LogsActivity;

    const PAGINATE = 20;
    const MODULE_NAME = ('List of individuals and legal entities');
    const TRANSLATABLE_FIELDS = ['contractor_name'];

    /**
     * The name of the Model that will be used for activity logs
     *
     * @var string
     */
    protected string $logName = 'executors';

    /**
     * Log user activity
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName($this->logName);
    }
}

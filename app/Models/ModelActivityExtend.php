<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as ModelAlias;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ModelActivityExtend extends ModelAlias
{
    use LogsActivity;
    
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

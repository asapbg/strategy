<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as ModelAlias;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ModelActivityExtend extends ModelAlias
{
    use LogsActivity;
    use SoftDeletes;

    const PAGINATE = 20;

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

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    /**
     * Join translations table
     *
     * @param $query
     * @param $model
     * @return mixed
     */
    public function scopeJoinTranslation($query, $model)
    {
        $table = app($model)->getTable();
        $table_singular = substr($table, 0, -1);
        $table_id = $table_singular."_id";

        $join_table = (Schema::hasTable($table."_translations"))
            ? $table."_translations"
            : $table_singular."_translations";

        return $query->join($join_table, $join_table.".$table_id", "=", $table.".id");
    }
}

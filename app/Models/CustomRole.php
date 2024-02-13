<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomRole extends Role
{
    use LogsActivity;
    use SoftDeletes;

    protected string $logName = "roles";

    const MODULE_NAME = ('custom.module_roles');
    const PAGINATE = 20;
    const SUPER_USER_ROLE = 'service_user';
    const ADMIN_USER_ROLE = 'super-admin';
    const EXTERNAL_USER_ROLE = 'external-user';
    const MODERATOR_ADVISORY_BOARD = 'moderator-advisory-board';
    const MODERATOR_ADVISORY_BOARDS = 'moderator-advisory-boards';

    const MODERATOR_STRATEGIC_DOCUMENT = 'moderator-strategic';
    const MODERATOR_STRATEGIC_DOCUMENTS = 'moderator-strategics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the name of the role
     */
    public function getModelName() {
        return $this->display_name;
    }

    /**
     * Log Sector's activity
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

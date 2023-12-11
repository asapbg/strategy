<?php

namespace App\Models;

use App\Models\StrategicDocuments\Institution;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 */
class User extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    use CausesActivity;
    use Notifiable;
    use HasRoles;

    protected string $logName = "users";

    const MODULE_NAME = ('custom.module_users');

    const PAGINATE = 20;

    const EXTERNAL_USER_DEFAULT_ROLE = 'external-user';

    const USER_TYPE_EXTERNAL = 2;
    const USER_TYPE_INTERNAL = 1;

    const ROLES_WITH_INSTITUTION = [
        'moderator-advisory',
        'moderator-strategic',
//        'moderator-legal',
        'moderator-advisory-boards',
        'moderator-advisory-board',
        'moderator-partnership',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's name
     */
    public function getModelName() {
        return $this->fullName();
    }

    /**
     * Change activity log description on login
     *
     * @param Activity $activity
     */
    public function tapActivity(Activity $activity)
    {
        if (request()->path() == "admin/login") {
            $activity->description = "user_login";
        }
    }

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_BLOCKED = 3;

    /**
     * Get user statuses
     *
     * @return array
     */
    public static function getUserStatuses()
    {
        return [
            self::STATUS_ACTIVE     => __('custom.active_m'),
            self::STATUS_INACTIVE   => __('custom.inactive_m'),
            self::STATUS_BLOCKED    => __('custom.blocked')
        ];
    }

    /**
     * Get user types
     *
     * @return array
     */
    public static function getUserTypes(): array
    {
        return [
            self::USER_TYPE_INTERNAL     => __('custom.users.type.'.self::USER_TYPE_INTERNAL),
            self::USER_TYPE_EXTERNAL   => __('custom.users.type.'.self::USER_TYPE_EXTERNAL),
        ];
    }

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
     * Get the user's activities
     *
     * @return hasMany
     */
    public function activities()
    {
        return $this->hasMany(CustomActivity::class, 'causer_id', 'id');
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Institution::class, 'id', 'institution_id');
    }

    public function polls(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserPoll::class, 'user_id', 'id');
    }


    /**
     * Return the user's full name if not empty
     * else return the username
     */
    public function fullName()
    {
        if (!empty($this->first_name)) {
            return "$this->first_name $this->middle_name $this->last_name";
        }

        return $this->username;
    }

    /**
     * Make scope to return users by given role / roles
     *
     * @param $query
     * @param $roles
     */
    public function scopeHasRole($query, $roles)
    {
        if (is_array($roles)) {
            return $query->join('model_has_roles', "model_has_roles.model_id", "=", "users.id")
                ->whereIn('role_id', Role::whereIn('name', $roles)->get()->pluck('id')->toArray());
        } else {
            return $query->join('model_has_roles', "model_has_roles.model_id", "=", "users.id")
                ->where('role_id', Role::where('name', $roles)->value('id'));
        }
    }

    /**
     * @return HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(UserSubscribe::class);
    }

}

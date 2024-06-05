<?php

namespace App\Models;

use App\Models\StrategicDocuments\Institution;
use Carbon\Carbon;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    use CausesActivity;
    use Notifiable;
    use HasRoles;
    use Notifiable;
    use MustVerifyEmail;

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
//        'moderator-advisory-boards',
//        'moderator-advisory-board',
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

//    public function routeNotificationFor($driver, $notification = null)
//    {
//        if($this->user_type == self::USER_TYPE_INTERNAL){
//            return [
//                $this->email => $this->fullName()
//            ];
//        } else{
//            return [
//                $this->notification_email => $this->fullName()
//            ];
//        }
//    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification): array|string
    {
        return config('app.env') != 'production' ? config('mail.local_to_mail') : $this->email;

    }

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
    const STATUS_REG_IN_PROCESS = 4;

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

    public function advisoryBoards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdvisoryBoardModerator::class, 'user_id');
    }

    public function certificates(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(UserCertificate::class, 'user');
    }

    public function activeCertificate(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(UserCertificate::class, 'user')->where('valid_to', '>', Carbon::now());
    }

    public function commentsPc(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comments::class, 'user_id', 'id')
            ->where('object_code', '=', Comments::PC_OBJ_CODE)
            ->orderBy('created_at', 'desc');
    }

    public function legislativeInitiatives(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeInitiative::class, 'author_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    public function legislativeInitiativesComments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeInitiativeComment::class, 'user_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    public function legislativeInitiativesLike(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LegislativeInitiativeVote::class, 'user_id', 'id')
            ->where('is_like', true)
            ->orderBy('created_at', 'desc');
    }

    public function changeRequests(): HasMany
    {
        return $this->hasMany(UserChangeRequest::class)->orderBy('id', 'desc');

    }

    public function pendingChangeRequests(): HasMany
    {
        return $this->hasMany(UserChangeRequest::class, 'user_id', 'id')
            ->where('status', '=', UserChangeRequest::PENDING)
            ->orderBy('id', 'desc');
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

    public function scopeIsActive($query)
    {
        $query->where('users.activity_status', '<>', self::STATUS_INACTIVE)
            ->where('users.active', 1);
    }

    public function scopeIsInProcess($query)
    {
        $query->where('users.activity_status', self::STATUS_REG_IN_PROCESS);
    }

    public function scopeNotVerified($query, $id)
    {
        $query->where('users.activity_status', self::STATUS_REG_IN_PROCESS)
            ->whereNull('users.email_verified_at')
            ->where('users.id', '=', $id)
            ->where('users.user_type', '=', User::USER_TYPE_EXTERNAL);
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

    public function moderateAdvisoryBoards(): HasMany
    {
        return $this->hasMany(AdvisoryBoardModerator::class);
    }

    public function moderatedAdvBoardOrdered()
    {
        return \App\Models\AdvisoryBoard::whereIn('advisory_boards.id', $this->moderateAdvisoryBoards->pluck('advisory_board_id')->toArray())->ActivePublic()->orderByTranslation('name')->get();
    }

    public function getModerateFieldOfActionIds(): array
    {
        $own_advisory_board_ids = $this->moderateAdvisoryBoards->pluck('advisory_board_id');
        $policy_area_ids = AdvisoryBoard::whereIn('id', $own_advisory_board_ids)->pluck('policy_area_id');

        return empty($policy_area_ids) ? [] : $policy_area_ids->toArray();
    }

    /**
     * Get the user's subscriptions
     *
     * @return HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(UserSubscribe::class);
    }

    /**
     * Check if the current user is subscribed to the current route's model
     * for the given channel
     *
     * @param $channel
     * @return bool
     */
    public static function isSubscribed($channel)
    {
        $route_name = request()->route()->getName();

        return (
            session('subscriptions')
            && array_key_exists($route_name, session('subscriptions'))
            && session('subscriptions')[$route_name]['channel'] == $channel
            && session('subscriptions')[$route_name]['is_subscribed'] == UserSubscribe::SUBSCRIBED
        );
    }
}

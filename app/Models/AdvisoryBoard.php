<?php

namespace App\Models;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Enums\PublicationTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

/**
 * @property int                      $id
 * @property int                      $policy_area_id
 * @property int                      $authority_id
 * @property int                      $advisory_act_type_id
 * @property int                      $advisory_chairman_type_id
 * @property int                      $meetings_per_year
 * @property bool                     $active
 * @property string                   $name
 * @property bool                     $has_npo_presence
 * @property bool                     $public
 *
 * @property Collection               $members
 * @property AdvisoryBoardFunction    $advisoryFunction
 * @property Collection               $functionFiles
 * @property AdvisoryBoardSecretariat $secretariat
 * @property Collection               $meetings
 * @property Collection               $regulatoryAllFiles
 * @property Collection               $regulatoryFiles
 * @property Collection               $moderators
 * @property Collection               $meetingsAllFiles
 * @property Collection               $meetingsFiles
 * @property Collection               $npos
 *
 * @method static orderBy(string $string, string $string1)
 * @method static find(mixed $get)
 * @method moderatorListing()
 */
class AdvisoryBoard extends ModelActivityExtend implements Feedable
{

    use FilterSort, Translatable;

    const PAGINATE = 20;
    const DEFAULT_IMG = 'images'.DIRECTORY_SEPARATOR.'ms-2023.jpg';
    const MODULE_NAME = ('custom.advisory_board');
    const CHANGEABLE_FIELDS = ['policy_area_id', 'advisory_chairman_type_id', 'advisory_act_type_id', 'meetings_per_year', 'has_npo_presence', 'authority_id', 'integration_link', 'public'];
    const TRANSLATABLE_FIELDS = ['name', 'advisory_specific_name', 'advisory_act_specific_name', 'report_institution_specific_name'];
    const DEFAULT_HEADER_IMG = '/img/ms-w-2023.jpg';

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    protected $table = 'advisory_boards';

    //activity
    protected string $logName = "advisory_board";

    protected $fillable = ['policy_area_id', 'advisory_chairman_type_id', 'advisory_act_type_id', 'meetings_per_year', 'has_npo_presence', 'authority_id', 'integration_link', 'public', 'file_id'];

    /**
     * @return FeedItem
     */
    public function toFeedItem(): FeedItem
    {
        return FeedItem::create([
            'id' => $this->id,
            'title' => $this->name,
            'summary' => '',
            'updated' => $this->updated_at ?? $this->created_at,
            'link' => route('advisory-boards.view', ['item' => $this->id]),
            'authorName' => '',
            'authorEmail' => ''
        ]);
    }

    /**
     * We use this method for rss feed
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFeedItems(): \Illuminate\Database\Eloquent\Collection
    {
        return static::with(['translations'])
            ->ActivePublic()
            ->orderByRaw("(case when updated_at is null then created_at else updated_at end) desc")
            ->limit(config('feed.items_per_page'), 20)
            ->get();
    }

    public function scopeActivePublic($query){
        $query->where('advisory_boards.active', true)
            ->where('advisory_boards.public', true);
    }

    /**
     * Listing only moderator's advisory boards.
     */
    public function scopeModeratorListing(Builder $query): Builder
    {
        return $query->whereIn('id', AdvisoryBoardModerator::where('user_id', auth()->user()->id)->pluck('advisory_board_id'));
    }

    public function npos(): HasMany
    {
        return $this->hasMany(AdvisoryBoardNpo::class);
    }

    public function moderators(): HasMany
    {
        return $this->hasMany(AdvisoryBoardModerator::class)->whereHas('user');
    }

    /**
     * Check if current user is a moderator.
     *
     * @return bool
     */
    public function moderatorCanOperate(): bool
    {
        return !is_null($this->moderators->first(fn($record) => $record->user_id === auth()->user()->id && $record->advisory_board_id === $this->id));
    }

    public function customSections(): HasMany
    {
        return $this->hasMany(AdvisoryBoardCustom::class)->orderBy('order');
    }

    public function moderatorInformation(): HasOne
    {
        return $this->hasOne(AdvisoryBoardModeratorInformation::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class)->orderBy('ord', 'asc')->orderBy('id', 'asc');
    }

    public function viceChairmen(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class)
            ->where('advisory_type_id', AdvisoryTypeEnum::VICE_CHAIRMAN->value)
            ->orderBy('ord', 'asc')->orderBy('id', 'asc');
    }

    public function chairmen(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class)
            ->where('advisory_type_id', AdvisoryTypeEnum::CHAIRMAN->value)
            ->orderBy('ord', 'asc')->orderBy('id', 'asc');
    }

    public function establishment(): HasOne
    {
        return $this->hasOne(AdvisoryBoardEstablishment::class);
    }

    public function organizationRule(): HasOne
    {
        return $this->hasOne(AdvisoryBoardOrganizationRule::class);
    }

    public function advisoryFunctions(): HasMany
    {
        return $this->hasMany(AdvisoryBoardFunction::class)->orderBy('working_year', 'desc');
    }

    public function workingProgram(): HasOne
    {
        return $this->hasOne(AdvisoryBoardFunction::class)
            ->whereYear('working_year', '=', now()->year);
    }

    public function workingProgramAll(): HasOne
    {
        return $this->hasOne(AdvisoryBoardFunction::class);
    }

    protected function hasViceChairman(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->members->count() > 0) {
                    $found = $this->members->first(fn($member) => $member->advisory_chairman_type_id === AdvisoryChairmanType::VICE_CHAIRMAN);

                    return !is_null($found);
                }

                return false;
            }
        );
    }
    protected function headerImg(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->file_id > 0) {
                    return asset($this->mainImg->path);
                }
                return asset(self::DEFAULT_HEADER_IMG);
            }
        );
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMeeting::class);
    }

    public function secretariat(): HasOne
    {
        return $this->hasOne(AdvisoryBoardSecretariat::class);
    }

    public function secretaryCouncil(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class)
            ->where('advisory_type_id', AdvisoryTypeEnum::SECRETARY->value)
            ->orderBy('ord', 'asc')->orderBy('id', 'asc');
    }

    public function authority(): BelongsTo
    {
        return $this->belongsTo(AuthorityAdvisoryBoard::class)->withTrashed();
    }

    public function allMembers(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class)
            ->orderBy('ord', 'asc')->orderBy('id', 'asc');
    }

    public function advisoryActType(): BelongsTo
    {
        return $this->belongsTo(AdvisoryActType::class)->withTrashed();
    }

    public function advisoryChairmanType(): BelongsTo
    {
        return $this->belongsTo(AdvisoryChairmanType::class);
    }

    public function policyArea(): BelongsTo
    {
        return $this->belongsTo(FieldOfAction::class)->withTrashed();
    }

    /**
     * @return HasOne
     */
    public function mainImg()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    /**
     * Get the model name
     */
    public function getModelName(): string
    {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return [
            'name' => [
                'type' => 'string',
                'rules' => ['required', 'string', 'max:255'],
                'required_all_lang' => true
            ],
        ];
    }

    public static function select2AjaxOptions($filters)
    {
        $userAdvBoards = request()->user()->hasAnyRole(
            [CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_ADVISORY_BOARDS]) ?
            null
            : (request()->user()->advisoryBoards->count() ? request()->user()->advisoryBoards->pluck('advisory_board_id')->toArray() : [0]);

        $q = DB::table('advisory_boards')
            ->select(['advisory_boards.id', DB::raw('advisory_board_translations.name')])
            ->join('advisory_board_translations', function ($j){
                $j->on('advisory_boards.id', '=', 'advisory_board_translations.advisory_board_id')
                    ->where('advisory_board_translations.locale', '=', app()->getLocale());
            });

        if(isset($filters['search'])) {
            $q->where('advisory_board_translations.name', 'ilike', '%'.$filters['search'].'%');
        }

        $q->whereNull('advisory_boards.deleted_at')
            ->when($userAdvBoards, function ($q) use ($userAdvBoards){
                $q->whereIn('advisory_boards.id', $userAdvBoards);
            });

        return $q->get();
    }

    /**
     * @return morphMany
     */
    public function subscriptions()
    {
        return $this->morphMany(UserSubscribe::class, 'subscribable');
    }

    public static function list($requestFilter)
    {
        $personName = isset($requestFilter['personName']) && !empty($requestFilter['personName']) ? $requestFilter['personName'] : null;
        return self::select('advisory_boards.*')
            ->with(['policyArea', 'policyArea.translations', 'translations', 'moderators',
                'authority', 'authority.translations', 'advisoryChairmanType', 'advisoryChairmanType.translations',
                'advisoryActType', 'advisoryActType.translations'])
            ->leftJoin('advisory_board_translations', function ($j){
                $j->on('advisory_board_translations.advisory_board_id', '=', 'advisory_boards.id')
                    ->where('advisory_board_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('field_of_actions', 'field_of_actions.id', '=', 'advisory_boards.policy_area_id')
            ->leftJoin('field_of_action_translations', function ($j){
                $j->on('field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
                    ->where('field_of_action_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('authority_advisory_board', 'authority_advisory_board.id', '=', 'advisory_boards.authority_id')
            ->leftJoin('authority_advisory_board_translations', function ($j){
                $j->on('authority_advisory_board_translations.authority_advisory_board_id', '=', 'authority_advisory_board.id')
                    ->where('authority_advisory_board_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_act_type', 'advisory_act_type.id', '=', 'advisory_boards.advisory_act_type_id')
            ->leftJoin('advisory_act_type_translations', function ($j){
                $j->on('advisory_act_type_translations.advisory_act_type_id', '=', 'advisory_act_type.id')
                    ->where('advisory_act_type_translations.locale', '=', app()->getLocale());
            })
            ->leftJoin('advisory_chairman_type', 'advisory_chairman_type.id', '=', 'advisory_boards.advisory_chairman_type_id')
            ->leftJoin('advisory_chairman_type_translations', function ($j){
                $j->on('advisory_chairman_type_translations.advisory_chairman_type_id', '=', 'advisory_chairman_type.id')
                    ->where('advisory_chairman_type_translations.locale', '=', app()->getLocale());
            })->when($personName, function ($query){
                $query->join('advisory_board_members', 'advisory_board_members.advisory_board_id', '=', 'advisory_boards.id')
                    ->join('advisory_board_member_translations', function ($j){
                        $j->on('advisory_board_member_translations.advisory_board_member_id', '=', 'advisory_board_members.id')
                            ->where('advisory_board_member_translations.locale', '=', app()->getLocale());
                    })
                    ->join('advisory_board_npos', 'advisory_board_npos.advisory_board_id', '=', 'advisory_boards.id')
                    ->join('advisory_board_npo_translations', function ($j){
                        $j->on('advisory_board_npo_translations.advisory_board_npo_id', '=', 'advisory_board_npos.id')
                            ->where('advisory_board_npo_translations.locale', '=', app()->getLocale());
                    });
            })
            ->where('public', true)
            ->FilterBy($requestFilter)->get();
    }
}

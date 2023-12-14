<?php

namespace App\Models;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int                      $id
 * @property int                      $policy_area_id
 * @property int                      $authority_id
 * @property int                      $advisory_act_type_id
 * @property int                      $advisory_chairman_type_id
 * @property int                      $meetings_per_year
 * @property bool                     $active
 * @property string                   $name
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
 *
 * @method static orderBy(string $string, string $string1)
 * @method static find(mixed $get)
 * @method moderatorListing()
 */
class AdvisoryBoard extends ModelActivityExtend
{

    use FilterSort, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board');
    const TRANSLATABLE_FIELDS = ['name', 'advisory_specific_name', 'advisory_act_specific_name', 'report_institution_specific_name'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    protected $table = 'advisory_boards';

    //activity
    protected string $logName = "advisory_board";

    protected $fillable = ['policy_area_id', 'advisory_chairman_type_id', 'advisory_act_type_id', 'meetings_per_year', 'has_npo_presence', 'authority_id', 'integration_link'];

    /**
     * Listing only moderator's advisory boards.
     */
    public function scopeModeratorListing(Builder $query): Builder
    {
        return $query->whereIn('id', AdvisoryBoardModerator::where('user_id', auth()->user()->id)->pluck('advisory_board_id'));
    }

    public function moderators(): HasMany
    {
        return $this->hasMany(AdvisoryBoardModerator::class);
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
        return $this->hasMany(AdvisoryBoardMember::class);
    }

    public function viceChairmen(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class)
            ->where('advisory_type_id', AdvisoryTypeEnum::VICE_CHAIRMAN->value);
    }

    public function chairmen(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class)
            ->where('advisory_type_id', AdvisoryTypeEnum::CHAIRMAN->value);
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
        return $this->hasMany(AdvisoryBoardFunction::class);
    }

    public function workingProgram(): HasOne
    {
        return $this->hasOne(AdvisoryBoardFunction::class)
            ->whereYear('working_year', '=', now()->year);
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
        return $this->hasMany(AdvisoryBoardMember::class)->where('advisory_type_id', AdvisoryTypeEnum::SECRETARY->value);
    }

    public function authority(): BelongsTo
    {
        return $this->belongsTo(AuthorityAdvisoryBoard::class);
    }

    public function allMembers(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class);
    }

    public function advisoryActType(): BelongsTo
    {
        return $this->belongsTo(AdvisoryActType::class);
    }

    public function advisoryChairmanType(): BelongsTo
    {
        return $this->belongsTo(AdvisoryChairmanType::class);
    }

    public function policyArea(): BelongsTo
    {
        return $this->belongsTo(PolicyArea::class);
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
                'rules' => ['required'],
            ],
        ];
    }
}

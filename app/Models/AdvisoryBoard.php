<?php

namespace App\Models;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Models\StrategicDocuments\Institution;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int                   $id
 * @property string                $name
 *
 * @property Collection            $members
 * @property AdvisoryBoardFunction $advisoryFunction
 * @property Collection            $functionFiles
 * @property Collection            $secretaryCouncil
 * @property Collection            $secretariat
 * @property Collection            $meetings
 *
 * @method static orderBy(string $string, string $string1)
 * @method static find(mixed $get)
 */
class AdvisoryBoard extends Model
{

    use FilterSort, SoftDeletes, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board');
    const TRANSLATABLE_FIELDS = ['name', 'advisory_specific_name', 'advisory_act_specific_name', 'report_institution_specific_name'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    protected $table = 'advisory_boards';

    //activity
    protected string $logName = "advisory_board";

    protected $fillable = ['policy_area_id', 'advisory_chairman_type_id', 'advisory_act_type_id', 'meetings_per_year', 'has_npo_presence', 'authority_id'];

    public function members(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class)
            ->where('advisory_type_id', AdvisoryTypeEnum::MEMBER->value);
    }

    public function viceChairmen(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class)
            ->where('advisory_type_id', AdvisoryTypeEnum::CHAIRMAN->value)
            ->where('advisory_chairman_type_id', AdvisoryChairmanType::VICE_CHAIRMAN);
    }

    public function chairmen(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMember::class)
            ->where('advisory_type_id', AdvisoryTypeEnum::CHAIRMAN->value)
            ->where('advisory_chairman_type_id', AdvisoryChairmanType::HEAD_CHAIRMAN);
    }

    public function advisoryFunction(): HasOne
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
        return $this->hasMany(AdvisoryBoardSecretaryCouncil::class);
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
    public function getModelName()
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

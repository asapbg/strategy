<?php

namespace App\Models;

use App\Models\StrategicDocuments\Institution;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property string     $name
 *
 * @property Collection $members
 *
 * @method static orderBy(string $string, string $string1)
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

    protected $fillable = ['policy_area_id', 'advisory_chairman_type_id', 'advisory_act_type_id', 'meetings_per_year', 'report_institution_id'];

    public function reportInstitution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'report_institution_id');
    }

    public function members(): HasMany
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
            'advisory_specific_name' => [
                'type' => 'string',
                'rules' => ['nullable'],
            ],
            'advisory_act_specific_name' => [
                'type' => 'string',
                'rules' => ['nullable'],
            ],
            'report_institution_specific_name' => [
                'type' => 'string',
                'rules' => ['nullable'],
            ],
        ];
    }
}

<?php

namespace App\Models;

use App\Enums\LegislativeInitiativeStatusesEnum;
use App\Models\Consultations\OperationalProgramRow;
use App\Models\StrategicDocuments\Institution;
use App\Traits\FilterSort;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 *
 * @method static find(mixed $legislative_initiative_id)
 */
class LegislativeInitiative extends ModelActivityExtend
{

    use FilterSort, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.nomenclatures.legislative_initiative');

    public $timestamps = true;

    protected $table = 'legislative_initiative';

    //activity
    protected string $logName = "legislative_initiative";

    protected $fillable = ['operational_program_id', 'author_id', 'description'];

    /**
     * Get the model name
     */
    public function getModelName()
    {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'description' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
        );
    }

    public function operationalProgram()
    {
        return $this->belongsTo(OperationalProgramRow::class, 'operational_program_id', 'operational_program_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(LegislativeInitiativeVote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(LegislativeInitiativeComment::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'author_id');
    }

    public function getStatus(int $value): LegislativeInitiativeStatusesEnum
    {
        return LegislativeInitiativeStatusesEnum::from($value);
    }

    public function setStatus(LegislativeInitiativeStatusesEnum $value): void
    {
        $this->attributes['status'] = $value->value;
    }
}

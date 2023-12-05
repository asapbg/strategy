<?php

namespace App\Models;

use App\Enums\DocTypesEnum;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 *
 * @method static find(mixed $meeting_id)
 */
class AdvisoryBoardMeeting extends Model
{

    use SoftDeletes, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_meetings');
    const TRANSLATABLE_FIELDS = ['description'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    //activity
    protected string $logName = "advisory_board_meetings";

    protected $fillable = ['advisory_board_id', 'next_meeting'];

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object', $this->id)->where('code_object', File::CODE_AB_FUNCTION)->where('doc_type', DocTypesEnum::AB_MEETINGS_AND_DECISIONS);
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
            'description' => [
                'type' => 'string',
                'rules' => ['nullable'],
            ],
        ];
    }
}

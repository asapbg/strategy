<?php

namespace App\Models;

use App\Enums\DocTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $advisory_board_id
 * @property Carbon $next_meeting
 *
 * @method static find(mixed $meeting_id)
 * @method static where(string $string, int $id)
 * @method static truncate()
 */
class AdvisoryBoardMeeting extends ModelActivityExtend
{

    use SoftDeletes, Translatable, FilterSort;

    const PAGINATE = 10;
    const MODULE_NAME = ('custom.advisory_board_meetings');
    const TRANSLATABLE_FIELDS = ['description'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    //activity
    protected string $logName = "advisory_board_meetings";

    protected $fillable = ['advisory_board_id', 'next_meeting'];

    public function decisions(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMeetingDecision::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_MEETINGS_AND_DECISIONS);
    }

    public function siteFiles()
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_MEETINGS_AND_DECISIONS)
            ->where('parent_id', null)
            ->whereLocale(app()->getLocale());
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
                'type' => 'summernote',
                'rules' => ['required', 'string'],
                'required_all_lang' => true
            ],
        ];
    }
}

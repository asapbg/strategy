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
    const CHANGEABLE_FIELDS = ['next_meeting'];

    const FILTER_ALL = 'all';
    const FILTER_CURRENT_YEAR = 'current_year';
    const FILTER_SPECIFIC_YEAR = 'specific_year';
    const FILTER_PERIOD = 'period';

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    //activity
    protected string $logName = "advisory_board_meetings";

    protected $fillable = ['advisory_board_id', 'next_meeting'];

    public function decisions(): HasMany
    {
        return $this->hasMany(AdvisoryBoardMeetingDecision::class);
    }

    public function advBoard()
    {
        return $this->hasOne(AdvisoryBoard::class, 'id', 'advisory_board_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_MEETINGS_AND_DECISIONS)
            ->orderBy('created_at', 'desc');
    }

    public function siteFiles()
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_MEETINGS_AND_DECISIONS)
            ->where('parent_id', null)
            ->whereLocale(app()->getLocale())
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the model name
     */
    public function getModelName()
    {
        return $this->advBoard->name;
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

    public static function getCriterias(): array
    {
        return [
            ['value' => self::FILTER_ALL,           'name' => __('custom.all')],
            ['value' => self::FILTER_CURRENT_YEAR,  'name' => __('custom.current_year')],
            ['value' => self::FILTER_SPECIFIC_YEAR, 'name' => __('custom.specific_year')],
            ['value' => self::FILTER_PERIOD,        'name' => __('custom.period')],
        ];
    }

    /**
     * Generate an array of years from the earliest `created_at` year in a table to the current year.
     *
     * @return array An array of years.
     */
    public static function getYearsRange(): array
    {
        // Get the earliest year from the `created_at` column
        $earliest_year = self::selectRaw('EXTRACT(YEAR FROM MIN(next_meeting)) as earliest_year')
            ->value('earliest_year');

        // Use the current year if no records are found
        $earliest_year = $earliest_year ?? Carbon::now()->year;

        // Generate the range of years
        $range = range($earliest_year, Carbon::now()->year);

        $formatted_range = [];

        // Format
        foreach ($range as $index => $year) {
            $formatted_range[] = [
                'value' => $year,
                'name' => $year,
            ];
        }

        return $formatted_range;
    }
}

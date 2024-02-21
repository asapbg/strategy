<?php

namespace App\Models;

use App\Enums\DocTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int        $id
 * @property int        $advisory_board_id
 * @property Collection $allFiles
 * @property Collection $files
 * @property Carbon     $working_year
 *
 * @method static where(string $string, mixed $advisory_board_id)
 * @method static orderBy(string $string, string $string1)
 * @method static create(array $array)
 * @method static find(mixed $function_id)
 */
class AdvisoryBoardFunction extends ModelActivityExtend
{

    use FilterSort, Translatable;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.advisory_board_functions');
    const TRANSLATABLE_FIELDS = ['description'];

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;
    public $timestamps = true;

    //activity
    protected string $logName = "advisory_board_functions";

    protected $fillable = ['advisory_board_id', 'working_year', 'ord'];

    public function advisoryBoard(): BelongsTo
    {
        return $this->belongsTo(AdvisoryBoard::class);
    }

    public function allFiles(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->withTrashed()
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_FUNCTION);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_FUNCTION);
    }

    public function siteFiles(): HasMany
    {
        return $this->hasMany(File::class, 'id_object')
            ->where('code_object', File::CODE_AB)
            ->where('doc_type', DocTypesEnum::AB_FUNCTION)
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

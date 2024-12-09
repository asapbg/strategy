<?php

namespace App\Models\AdvisoryBoard;

use App\Models\ModelActivityExtend;
use App\Models\StrategicDocuments\Institution;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

/**
 * @method static create(array $array)
 * @method static paginate(int $PAGINATION)
 * @method static orderBy(string $string)
 * @method static advisoryBoard() - Scope to get all advisory board categories
 */
class AdvisoryBoardNomenclatureFieldOfAction extends ModelActivityExtend implements TranslatableContract
{

    use FilterSort, Translatable;

    const TRANSLATABLE_FIELDS = ['name'];

    const MODULE_NAME = ('custom.tags');

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    protected $fillable = ['icon_class'];

    const CATEGORY_NATIONAL = 1;
    const CATEGORY_AREA = 2;
    const CATEGORY_MUNICIPAL = 3;


    //activity
    protected string $logName = "advisory_board_nomenclature_field_of_actions";

    protected function name(): Attribute
    {
        $name = 'name_' . app()->getLocale();

        return Attribute::make(
            get: fn() => $this->$name
        );
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'name' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255']
            ],
        );
    }

    public function institution()
    {
        return $this->belongsToMany(Institution::class, 'institution_field_of_action', 'field_of_action_id', 'institution_id');
    }

    public static function optionsList($active = false): \Illuminate\Support\Collection
    {
        $q = DB::table((new self())->getTable())
            ->select(['advisory_board_nomenclature_field_of_actions.id', 'advisory_board_nomenclature_field_of_action_translations.name'])
            ->join((new AdvisoryBoardNomenclatureFieldOfActionTranslation())->getTable(), 'advisory_board_nomenclature_field_of_action_translations.advisory_board_nomenclature_field_of_action_id', '=', 'advisory_board_nomenclature_field_of_actions.id')
            ->where('advisory_board_nomenclature_field_of_action_translations.locale', '=', app()->getLocale());

        if ($active) {
            $q->where('advisory_board_nomenclature_field_of_actions.active', '=', 1)
                ->whereNull('advisory_board_nomenclature_field_of_actions.deleted_at');
        }

        return $q->orderBy('advisory_board_nomenclature_field_of_action_translations.name')->get();
    }
}

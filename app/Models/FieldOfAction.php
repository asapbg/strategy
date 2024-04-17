<?php

namespace App\Models;

use App\Enums\InstitutionCategoryLevelEnum;
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
class FieldOfAction extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const TRANSLATABLE_FIELDS = ['name'];

    const MODULE_NAME = ('custom.tags');

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    protected $table = 'field_of_actions';
    protected $fillable = ['icon_class'];

    const CATEGORY_NATIONAL = 1;
    const CATEGORY_AREA = 2;
    const CATEGORY_MUNICIPAL = 3;


    //activity
    protected string $logName = "field_of_actions";

    protected function name(): Attribute
    {
        $name = 'name_' . app()->getLocale();

        return Attribute::make(
            get: fn() => $this->$name
        );
    }

    /**
     * Used to get only advisory boards categories.
     *
     * @param $query
     *
     * @return void
     */
    public function scopeAdvisoryBoard($query): void
    {
        $query->where('parentid', 1);
    }

    public function scopeActive($query){
        $query->where('field_of_actions.active', 1);
    }

    public function scopeCentral($query){
        $query->where('field_of_actions.parentid', InstitutionCategoryLevelEnum::fieldOfActionCategory(InstitutionCategoryLevelEnum::CENTRAL->value));
    }

    public function scopeArea($query){
        $query->where('field_of_actions.parentid', InstitutionCategoryLevelEnum::fieldOfActionCategory(InstitutionCategoryLevelEnum::AREA->value));
    }

    public function scopeMunicipal($query){
        $query->where('field_of_actions.parentid', InstitutionCategoryLevelEnum::fieldOfActionCategory(InstitutionCategoryLevelEnum::MUNICIPAL->value));
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

    public static function optionsList($active = false, $parent = 0)
    {
        $q = DB::table('field_of_actions')
            ->select(['field_of_actions.id', 'field_of_action_translations.name', 'field_of_actions.parentid'])
            ->join('field_of_action_translations', 'field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
            ->where('field_of_action_translations.locale', '=', app()->getLocale());

        if($parent){
            $q->where('field_of_actions.parentid', '=', $parent);
        }

        if($active) {
            $q->where('field_of_actions.active', '=', 1)
                ->whereNull('field_of_actions.deleted_at');
        }

        return $q->orderBy('field_of_action_translations.name', 'asc')
            ->get();
    }

}

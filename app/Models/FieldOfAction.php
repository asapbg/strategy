<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

/**
 * @method static create(array $array)
 * @method static paginate(int $PAGINATION)
 * @method static orderBy(string $string)
 */
class FieldOfAction extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const TRANSLATABLE_FIELDS = ['name'];

    const MODULE_NAME = ('custom.tags');

    const PAGINATION = 20;

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    protected $table = 'field_of_actions';
    protected $fillable = ['icon_class'];

    //activity
    protected string $logName = "field_of_actions";

    protected function name(): Attribute
    {
        $name = 'name_' . app()->getLocale();

        return Attribute::make(
            get: fn() => $this->$name
        );
    }

    public function scopeActive($query){
        $query->where('field_of_actions.active', 1);
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

    public static function optionsList($active = false)
    {
        $q = DB::table('field_of_actions')
            ->select(['field_of_actions.id', 'field_of_action_translations.name'])
            ->join('field_of_action_translations', 'field_of_action_translations.field_of_action_id', '=', 'field_of_actions.id')
            ->where('field_of_action_translations.locale', '=', app()->getLocale());

        if($active) {
            $q->where('active', '=', 1);
        }

        return $q->orderBy('field_of_action_translations.name', 'asc')
            ->get();
    }

}

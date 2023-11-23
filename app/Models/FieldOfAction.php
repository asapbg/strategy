<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method static paginate(int $PAGINATION)
 * @method static orderBy(string $string)
 */
class FieldOfAction extends Model implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const TRANSLATABLE_FIELDS = ['name'];

    const MODULE_NAME = ('custom.tags');

    const PAGINATION = 20;

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    protected $table = 'field_of_actions';

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
}

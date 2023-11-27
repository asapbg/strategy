<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Tag extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['label'];
    const MODULE_NAME = ('custom.tags');
//
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'tag';

    //activity
    protected string $logName = "tag";

    protected $fillable = [];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->label;
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->label
        );
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'label' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255']
            ]
        );
    }

    public static function optionsList()
    {
        return DB::table('tag')
            ->select(['tag.id', 'tag_translations.label'])
            ->join('tag_translations', 'tag_translations.tag_id', '=', 'tag.id')
            ->where('tag_translations.locale', '=', app()->getLocale())
            ->orderBy('tag_translations.label', 'asc')
            ->get();
    }
}

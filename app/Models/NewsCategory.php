<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class NewsCategory extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = 'custom.nomenclatures.news_category';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'news_category';

    //activity
    protected string $logName = "news_category";

    protected $fillable = [];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
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

    public static function optionsList()
    {
        return DB::table('news_category')
            ->select(['news_category.id', 'news_category_translations.name'])
            ->join('news_category_translations', 'news_category_translations.news_category_id', '=', 'news_category.id')
            ->where('news_category_translations.locale', '=', app()->getLocale())
            ->orderBy('news_category_translations.name', 'asc')
            ->get();
    }
}

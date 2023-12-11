<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class PublicationCategory extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name'];
    const MODULE_NAME = ('custom.nomenclatures.publication_category');
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'publication_category';

    //activity
    protected string $logName = "publication_category";

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

    public static function optionsList($onlyActive = false): \Illuminate\Support\Collection
    {
        $list = DB::table('publication_category')
            ->select(['publication_category.id', 'publication_category_translations.name'])
            ->join('publication_category_translations', 'publication_category_translations.publication_category_id', '=', 'publication_category.id')
            ->where('publication_category_translations.locale', '=', app()->getLocale())
            ->orderBy('publication_category_translations.name', 'asc');
        if($onlyActive) {
            $list->where('publication_category.active', '=', 1)
                ->whereNull('publication_category.deleted_at');
        }
        return $list->get();
    }
}

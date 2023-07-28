<?php

namespace App\Models;

use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'content'];
    const MODULE_NAME = 'custom.pages';
    const TYPE_STATIC_CONTENT = 1;
    const TYPE_STATIC_PAGE = 2;
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'page';

    //activity
    protected string $logName = "page";

    protected $fillable = ['type', 'highlighted', 'active'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->title;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'textarea',
                'rules' => ['required', 'string']
            ],
            'content' => [
                'type' => 'ckeditor',
                'rules' => ['required', 'string']
            ],
        );
    }

    public static function optionsList()
    {
        return DB::table('page')
            ->select(['page.id', 'page_translations.name'])
            ->join('page_translations', 'page_translations.page_id', '=', 'page.id')
            ->where('page_translations.locale', '=', app()->getLocale())
            ->orderBy('page_translations.name', 'asc')
            ->get();
    }
}

<?php

namespace App\Models;

use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publication extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'content'];
    const MODULE_NAME = 'custom.publications';
    const TYPE_PUBLICATION = 1;
    const TYPE_OGP_NEWS = 2;
    const TYPE_NEWS = 3;
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'publication';

    //activity
    protected string $logName = "publication";

    protected $fillable = ['type', 'publication_category_id', 'event_date', 'highlighted', 'active'];

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

    public function publicationCategory()
    {
        return $this->hasOne(PublicationCategory::class, 'id', 'publication_category_id');
    }

    public static function optionsList()
    {
        return DB::table('publication')
            ->select(['publication.id', 'publication_translations.name'])
            ->join('publication_translations', 'publication_translations.publication_id', '=', 'publication.id')
            ->where('publication_translations.locale', '=', app()->getLocale())
            ->orderBy('publication_translations.name', 'asc')
            ->get();
    }
}

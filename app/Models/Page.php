<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class Page  extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name', 'short_content', 'content', 'meta_keyword', 'meta_title', 'meta_description'];
    const MODULE_NAME = 'custom.page';

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'page';
    //activity
    protected string $logName = "page";

    protected $fillable = ['active', 'in_footer', 'slug', 'order_idx', 'system_name'];

    public function scopeIsActive($query)
    {
        $query->where('page.active', 1);
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class, 'id_object', 'id')->where('code_object', '=', File::CODE_OBJ_PAGE);
    }

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
            'short_content' => [
                'type' => 'textarea',
                'rules' => ['nullable', 'string', 'max:500']
            ],
            'content' => [
                'type' => 'summernote',
                'rules' => ['required', 'string']
            ],
            'meta_title' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255']
            ],
            'meta_keyword' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255']
            ],
            'meta_description' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255']
            ]
        );
    }

    public static function optionsList()
    {
        return DB::table('page')
            ->select(['page.id', 'page_translations.name'])
            ->join('page_translations', 'page_translations.page_id', '=', 'page.id')
            ->where('page.active', '=', 1)
            ->whereNull('page.deleted_at')
            ->where('page_translations.locale', '=', app()->getLocale())
            ->orderBy('page_translations.name', 'asc')
            ->get();
    }
}

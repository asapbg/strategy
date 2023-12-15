<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Publication extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title', 'short_content', 'content', 'meta_keyword', 'meta_title', 'meta_description'];
    const MODULE_NAME = ('custom.publications');
//
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'publication';

    //activity
    protected string $logName = "publication";

    protected $fillable = ['slug', 'type', 'publication_category_id', 'file_id', 'published_at', 'active'];

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
                'type' => 'text',
                'rules' => ['required', 'string', 'max:2000']
            ],
            'short_content' => [
                'type' => 'textarea',
                'rules' => ['required', 'string']
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
            ],
            'file' => [
                'type' => 'text',
                'rules' => ['nullable', 'string', 'max:255']
            ]
        );
    }

    /**
     * @return HasOne
     */
    public function category()
    {
        return $this->hasOne(PublicationCategory::class, 'id', 'publication_category_id');
    }

    /**
     * @return HasOne
     */
    public function mainImg()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'id_object', 'id')->where('code_object', '=', File::CODE_OBJ_PUBLICATION);
    }
}

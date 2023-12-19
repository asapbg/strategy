<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpArea extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.area');
    const TRANSLATABLE_FIELDS = ['name'];

    public $timestamps = true;

    protected $table = 'ogp_area';

    //activity
    protected string $logName = "ogp_area";

    protected $fillable = ['active', 'author_id'];
    protected $translatedAttributes = OgpArea::TRANSLATABLE_FIELDS;

    public function scopeActive($query)
    {
        return $query->where('active', '=', true);
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

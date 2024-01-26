<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PublicationCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'publication_category_id', 'name'];

    /**
     * Content
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value
        );
    }
}

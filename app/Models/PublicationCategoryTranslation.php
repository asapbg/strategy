<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'publication_category_id', 'name'];
}

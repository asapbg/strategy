<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'link_category_id', 'name'];
}

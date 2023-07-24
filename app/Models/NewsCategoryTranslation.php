<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'news_category_id', 'name'];
}

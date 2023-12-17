<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OgpAreaTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'ogp_area_translations';

    protected $fillable = ['locale', 'ogp_area_id', 'name'];
}

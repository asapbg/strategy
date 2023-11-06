<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PrisTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'pris_id', 'title'];
}

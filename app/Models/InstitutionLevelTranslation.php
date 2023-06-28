<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionLevelTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'institution_level_id', 'name'];
}

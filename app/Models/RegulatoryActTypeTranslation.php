<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegulatoryActTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'regulatory_act_type_id', 'name'];
}

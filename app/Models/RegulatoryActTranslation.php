<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegulatoryActTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'regulatory_act_id', 'name', 'institution'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalActTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'legal_act_type_id', 'name'];
}

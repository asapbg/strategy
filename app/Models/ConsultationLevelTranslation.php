<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationLevelTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'consultation_level_id', 'name'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'consultation_type_id', 'name'];
}

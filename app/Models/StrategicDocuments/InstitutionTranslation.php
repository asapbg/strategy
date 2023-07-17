<?php

namespace App\Models\StrategicDocuments;

use Illuminate\Database\Eloquent\Model;

class InstitutionTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'institution_id', 'name'];
}

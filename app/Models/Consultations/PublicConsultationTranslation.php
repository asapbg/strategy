<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActTypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'locale',
        'public_consultation_id',
        'title',
        'description',
        'shortTermReason',
        'responsibleUnit',
        'responsiblePerson',
        'address',
    ];
}

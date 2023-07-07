<?php

namespace App\Models\Consultations;

use Illuminate\Database\Eloquent\Model;

class PublicConsultationTranslation extends Model
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

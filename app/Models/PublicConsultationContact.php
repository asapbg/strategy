<?php

namespace App\Models;

use App\Models\Consultations\PublicConsultation;

class PublicConsultationContact extends ModelActivityExtend
{

    protected $table ='public_consultation_contact';
    protected $guarded = [];

    const MODULE_NAME = ('custom.consultations.public_consultation.contacts');
    protected string $logName = "public_consultation_contact";

    public function getModelName() {
        return $this->name;
    }

    public function publicConsultation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PublicConsultation::class, 'id', 'public_consultation_id');
    }
}

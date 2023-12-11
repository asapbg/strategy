<?php

namespace App\Models\Consultations;

use App\Models\ConsultationDocumentRow;
use App\Models\PublicConsultationUnit;
use App\Traits\FilterSort;
use App\Models\ModelActivityExtend;

class ConsultationDocument extends ModelActivityExtend
{
    use FilterSort;

    const MODULE_NAME = ('custom.consultations.public_consultation');

    public $timestamps = true;

    protected $table = 'consultation_document';

    //activity
    protected string $logName = "consultation_document";

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public function records(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ConsultationDocumentRow::class, 'consultation_document_id', 'id');
    }




}

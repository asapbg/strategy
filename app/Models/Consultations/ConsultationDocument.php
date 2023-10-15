<?php

namespace App\Models\Consultations;

use App\Models\ConsultationDocumentRow;
use App\Models\ConsultationLevel;
use App\Models\PublicConsultationContact;
use App\Models\PublicConsultationUnit;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\DB;
use illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ModelActivityExtend;

class ConsultationDocument extends ModelActivityExtend
{
    use FilterSort, SoftDeletes;

    const MODULE_NAME = 'custom.consultations.public_consultation';

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

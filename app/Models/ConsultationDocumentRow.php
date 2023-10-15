<?php

namespace App\Models;

use App\Models\Consultations\ConsultationDocument;
use App\Models\Consultations\OperationalProgram;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultationDocumentRow extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'consultation_document_row';
    protected $guarded = [];

    public function details(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DynamicStructureColumn::class, 'id', 'dynamic_structures_column_id');
    }
    public function parent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ConsultationDocument::class, 'consultation_document_id', 'id');
    }
}

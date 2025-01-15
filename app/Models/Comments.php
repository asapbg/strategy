<?php

namespace App\Models;

use App\Models\Consultations\PublicConsultation;
use App\Traits\FilterSort;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comments extends Model
{
    use FilterSort, SoftDeletes;
    const PAGINATE = 20;
    public $timestamps = true;
    protected $table = 'comments';
    protected $guarded = [];

    const PC_OBJ_CODE = 1;
    const PC_OBJ_CODE_MESSAGE = 2;

    public function author(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id','id')->withTrashed();
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => !empty($value) ? html_entity_decode($value) : $value,
            set: fn (string|null $value) => !empty($value) ?  htmlentities(stripHtmlTags($value)) : $value,
        );
    }

    public function commented(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PublicConsultation::class, 'id', 'object_id');
    }
}

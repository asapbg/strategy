<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'consultation_category_id', 'name'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LawTranslation extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    protected $fillable = ['locale', 'law_id', 'name'];
}

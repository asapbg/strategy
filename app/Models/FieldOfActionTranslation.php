<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldOfActionTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'field_of_action_id', 'name'];
}

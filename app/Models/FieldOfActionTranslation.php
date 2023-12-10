<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $locale
 * @property int    $field_of_action_id
 * @property string $name
 */
class FieldOfActionTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['locale', 'field_of_action_id', 'name'];
}

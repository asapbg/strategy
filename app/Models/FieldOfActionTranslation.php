<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property string $locale
 * @property int    $field_of_action_id
 * @property string $name
 * @property Carbon $deleted_at
 */
class FieldOfActionTranslation extends ModelActivityExtend
{

    public $timestamps = false;

    protected $fillable = ['locale', 'field_of_action_id', 'name'];

    protected string $logName = "field_of_action_translations";
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * @property string $locale
 * @property int    $field_of_action_id
 * @property string $name
 * @property Carbon $deleted_at
 */
class FieldOfActionTranslation extends ModelTranslatableActivityExtend
{

    const MODULE_NAME = ('custom.field_of_actions');
    public $timestamps = false;

    protected $fillable = ['locale', 'field_of_action_id', 'name'];

    protected string $logName = "field_of_action_translations";
}

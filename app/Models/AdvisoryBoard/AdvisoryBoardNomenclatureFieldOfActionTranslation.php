<?php

namespace App\Models\AdvisoryBoard;

use App\Models\ModelTranslatableActivityExtend;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $locale
 * @property int    $advisory_board_nomenclature_field_of_action_id
 * @property string $name
 * @property Carbon $deleted_at
 */
class AdvisoryBoardNomenclatureFieldOfActionTranslation extends ModelTranslatableActivityExtend
{

    use SoftDeletes;

    const MODULE_NAME = ('custom.advisory_board_nomenclature_field_of_actions');
    public $timestamps = false;

    protected $fillable = ['locale', 'advisory_board_nomenclature_field_of_action_id', 'name'];

    protected string $logName = "advisory_board_nomenclature_field_of_action_translations";
}

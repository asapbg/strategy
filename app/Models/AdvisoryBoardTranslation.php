<?php

namespace App\Models;

/**
 * @property int    $advisory_board_id
 * @property string $locale
 * @property string $name
 */
class AdvisoryBoardTranslation extends ModelActivityExtend
{

    public $timestamps = false;

    protected $fillable = [];

    protected string $logName = "advisory_board_translation";
}

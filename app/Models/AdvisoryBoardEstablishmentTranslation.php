<?php

namespace App\Models;

/**
 * @property string $locale
 * @property int    advisory_board_establishment_id
 * @property string $description
 */
class AdvisoryBoardEstablishmentTranslation extends ModelTranslatableActivityExtend
{

    public $timestamps = false;

    protected string $logName = "advisory_board_establishment_translations";
}

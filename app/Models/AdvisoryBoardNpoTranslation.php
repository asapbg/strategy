<?php

namespace App\Models;


/**
 * @property string $locale
 * @property int    $advisory_board_npo_id
 * @property string $name
 */
class AdvisoryBoardNpoTranslation extends ModelTranslatableActivityExtend
{
    const MODULE_NAME = ('custom.adv_board_npo_translations');
    public $timestamps = false;
}

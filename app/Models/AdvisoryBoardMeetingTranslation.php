<?php

namespace App\Models;

/**
 * @property string $locale
 * @property int    $advisory_board_meeting_id
 * @property string $description
 */
class AdvisoryBoardMeetingTranslation extends ModelTranslatableActivityExtend
{

    const MODULE_NAME = ('custom.adv_board_meetings_translations');
    public $timestamps = false;

    protected string $logName = "advisory_board_meeting_translations";
}

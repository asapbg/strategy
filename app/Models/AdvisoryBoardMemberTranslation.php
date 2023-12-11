<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $locale
 * @property int    $advisory_board_member_id
 * @property string $member_name
 * @property string $member_job
 */
class AdvisoryBoardMemberTranslation extends Model
{

    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $locale
 * @property int    $advisory_board_custom_id
 * @property string $title
 * @property string $body
 */
class AdvisoryBoardCustomTranslation extends Model
{

    public $timestamps = false;
}

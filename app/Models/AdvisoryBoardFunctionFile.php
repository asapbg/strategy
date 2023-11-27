<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $advisory_board_id
 * @property int $file_id
 */
class AdvisoryBoardFunctionFile extends Model
{

    use SoftDeletes;

    protected $fillable = ['file_name', 'file_description'];
}

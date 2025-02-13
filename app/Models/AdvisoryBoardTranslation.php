<?php

namespace App\Models;

/**
 * @property int    $advisory_board_id
 * @property string $locale
 * @property string $name
 */
class AdvisoryBoardTranslation extends ModelTranslatableActivityExtend
{

    const MODULE_NAME = ('custom.adv_board_translations');
    public $timestamps = false;

    protected $fillable = [];

    protected string $logName = "advisory_board_translation";

    public function getModelName()
    {
        return $this->name;
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AdvisoryBoard::class, 'id', 'advisory_board_id');
    }
}

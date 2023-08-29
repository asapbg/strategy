<?php

namespace App\Models;

use App\Models\ModelActivityExtend;
use App\Traits\FilterSort;
use Illuminate\Support\Facades\DB;

class Setting extends ModelActivityExtend
{
    use FilterSort;

    const PAGINATE = 20;
    const MODULE_NAME = 'custom.settings';

    public $timestamps = true;

    //activity
    protected string $logName = "setting";

    protected $fillable = ['key', 'value', 'section'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->key;
    }
}

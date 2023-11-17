<?php

namespace App\Models;

use App\Traits\FilterSort;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportFile extends ModelActivityExtend
{
    use FilterSort, SoftDeletes;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['title'];
    const MODULE_NAME = ('custom.files');

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'report_files';
}

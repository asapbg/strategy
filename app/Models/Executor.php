<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Executor extends ModelActivityExtend implements TranslatableContract
{
    use Translatable;

    const MODULE_NAME = ('custom.executors');
    const TRANSLATABLE_FIELDS = [
        'contractor_name',
        'executor_name',
        'contract_subject',
        'services_description',
        'hyperlink'
    ];

    /**
     * @var array|string[]
     */
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    /**
     * @var string[]
     */
    protected $fillable = ['eik', 'contract_date', 'price'];

    /**
     * The name of the Model that will be used for activity logs
     *
     * @var string
     */
    protected string $logName = 'executors';
}

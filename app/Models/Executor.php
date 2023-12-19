<?php

namespace App\Models;

use App\Models\StrategicDocuments\Institution;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class Executor extends ModelActivityExtend implements TranslatableContract
{
    use Translatable;

    const MODULE_NAME = ('custom.executors');
    const TRANSLATABLE_FIELDS = [
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
    protected $fillable = ['eik', 'contract_date', 'price', 'institution_id'];

    /**
     * The name of the Model that will be used for activity logs
     *
     * @var string
     */
    protected string $logName = 'executors';

    /**
     * @return belongsTo
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}

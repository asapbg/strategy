<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpPlanArrangementAction extends ModelActivityExtend implements TranslatableContract
{
    use SoftDeletes, Translatable;

    const TRANSLATABLE_FIELDS = ['name'];

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.ogp_plan_arrangement_action');

    public $timestamps = true;

    protected $table = 'ogp_plan_arrangement_action';

    //activity
    protected string $logName = "ogp_plan_arrangement_action";

    protected $fillable = ['from_date', 'to_date', ];
    protected $translatedAttributes = self::TRANSLATABLE_FIELDS;

    protected function fromDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? displayDate($value) : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function toDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? displayDate($value) : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    public function arrangement(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OgpPlanArrangement::class, 'id', 'ogp_plan_arrangement_id');
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'name' => [
                'type' => 'text',
                'rules' => ['required', 'max:2000'],
                'required_all_lang' => true
            ],
        );
    }
}

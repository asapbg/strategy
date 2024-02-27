<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpPlanArrangement extends ModelActivityExtend implements TranslatableContract
{
    use SoftDeletes, Translatable;

    const TRANSLATABLE_FIELDS = ['content', 'npo_partner', 'responsible_administration'];

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.ogp_plans_arrangement');

    public $timestamps = true;

    protected $table = 'ogp_plan_arrangement';

    //activity
    protected string $logName = "ogp_plan_arrangement";

    protected $fillable = ['ogp_plan_area_id', 'from_date', 'to_date', ];
    protected $translatedAttributes = OgpPlanArrangement::TRANSLATABLE_FIELDS;

    protected function fromDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? Carbon::parse($value)->format('d.m.Y') : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function toDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? Carbon::parse($value)->format('d.m.Y') : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'content' => [
                'type' => 'summernote',
                'rules' => ['required']
            ],
            'from_date' => [
                'type' => 'date',
                'rules' => []
            ],
            'to_date' => [
                'type' => 'date',
                'rules' => []
            ],
            'npo_partner' => [
                'type' => 'date',
                'rules' => ['max:255']
            ],
            'responsible_administration' => [
                'type' => 'text',
                'rules' => ['max:255']
            ],
        );
    }
}

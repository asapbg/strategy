<?php

namespace App\Models;

use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class OgpPlanSchedule extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable;

    const PAGINATE = 20;
    const TRANSLATABLE_FIELDS = ['name', 'description'];
    const MODULE_NAME = ('custom.ogp_plan_schedule');

    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;

    protected $table = 'ogp_plan_schedule';

    //activity
    protected string $logName = "ogp_plan_schedule";

    protected $fillable = ['ogp_plan_id', 'start_date', 'end_date'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->name;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'name' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:2000'],
                'required_all_lang' => true
            ],
            'description' => [
                'type' => 'summernote',
                'rules' => ['nullable', 'string'],
                'required_all_lang' => false
            ]
        );
    }

    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ? displayDate($value) : '',
            set: fn (string|null $value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null
        );
    }

    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: fn (string|null $value) => $value ? displayDate($value) : '',
            set: fn (string|null $value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null
        );
    }

    public function plan(): HasOne
    {
        return $this->hasOne(OgpPlan::class, 'id', 'ogp_plan_id');
    }
}

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

    const TRANSLATABLE_FIELDS = ['name', 'content', 'npo_partner', 'responsible_administration', 'problem',
        'solving_problem', 'values_initiative', 'extra_info', 'interested_org', 'contact_names', 'contact_positions'
        , 'contact_phone_email', 'evaluation', 'evaluation_status'];

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

    public function actions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OgpPlanArrangementAction::class, 'ogp_plan_arrangement_id');
    }

    public function ogpPlanArea(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OgpPlanArea::class, 'id', 'ogp_plan_area_id');
    }


    public static function translationFieldsProperties(): array
    {
        return array(
            'name' => [
                'type' => 'text',
                'rules' => ['required', 'max:2000'],
                'required_all_lang' => true
            ],
            'content' => [
                'type' => 'summernote',
                'rules' => ['required'],
                'required_all_lang' => false
            ],
            'npo_partner' => [
                'type' => 'summernote',
                'rules' => ['nullable'],
                'required_all_lang' => false
            ],
            'responsible_administration' => [
                'type' => 'summernote',
                'rules' => ['nullable'],
                'required_all_lang' => false
            ],
            'problem' => [
                'type' => 'summernote',
                'rules' => ['nullable'],
                'required_all_lang' => false
            ],
            'solving_problem' => [
                'type' => 'summernote',
                'rules' => ['nullable'],
                'required_all_lang' => false
            ],
            'values_initiative' => [
                'type' => 'summernote',
                'rules' => ['nullable'],
                'required_all_lang' => false
            ],
            'extra_info' => [
                'type' => 'summernote',
                'rules' => ['nullable'],
                'required_all_lang' => false
            ],
            'interested_org' => [
                'type' => 'summernote',
                'rules' => ['nullable'],
                'required_all_lang' => false
            ],
            'contact_names' => [
                'type' => 'summernote',
                'rules' => ['nullable'],
                'required_all_lang' => false
            ],
//            'contact_positions' => [
//                'type' => 'summernote',
//                'rules' => ['nullable'],
//                'required_all_lang' => false
//            ],
//            'contact_phone_email' => [
//                'type' => 'summernote',
//                'rules' => ['nullable'],
//                'required_all_lang' => false
//            ],
            'evaluation' => [
                'type' => 'summernote',
                'rules' => ['required'],
                'required_all_lang' => false
            ],
            'evaluation_status' => [
                'type' => 'text',
                'rules' => ['nullable'],
                'required_all_lang' => false
            ],
        );
    }
}

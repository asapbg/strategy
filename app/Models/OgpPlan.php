<?php

namespace App\Models;

use App\Enums\DocTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OgpPlan extends ModelActivityExtend implements TranslatableContract
{
    use FilterSort, Translatable, SoftDeletes;

    const PAGINATE = 20;
    const MODULE_NAME = ('custom.plans');
    const TRANSLATABLE_FIELDS = ['name', 'content'];

    public $timestamps = true;

    protected $table = 'ogp_plan';

    //activity
    protected string $logName = "ogp_plan";

    protected $fillable = ['from_date', 'to_date', 'active', 'author_id',
        'ogp_status_id', 'version_after_public_consultation_pdf', 'final_version_pdf',
        'from_date_develop', 'to_date_develop', 'national_plan'];
    protected $translatedAttributes = OgpPlan::TRANSLATABLE_FIELDS;

    public function scopeActive($query)
    {
        return $query->where('ogp_plan.active', '=', true);
    }

    public function scopeNational($query)
    {
        return $query->where('ogp_plan.national_plan', '=', 1);
    }

    public function scopeNotNational($query)
    {
        return $query->where('ogp_plan.national_plan', '=', 0);
    }

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

    protected function fromDateDevelop(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? displayDate($value) : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    protected function toDateDevelop(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !empty($value) ? displayDate($value) : null,
            set: fn ($value) => !empty($value) ?  Carbon::parse($value)->format('Y-m-d') : null
        );
    }

    public function status(): HasOne
    {
        return $this->hasOne(OgpStatus::class, 'id', 'ogp_status_id');
    }

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function areas(): HasMany
    {
        return $this->hasMany(OgpPlanArea::class, 'ogp_plan_id', 'id');
    }

    public function versionAfterConsultation()
    {
        return $this->hasMany(File::class, 'id_object', 'id')
            ->where('code_object', '=', File::CODE_OBJ_OGP)
            ->where('doc_type', '=', DocTypesEnum::OGP_VERSION_AFTER_CONSULTATION->value)
            ->where('locale', '=', app()->getLocale())
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'name' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255'],
                'required_all_lang' => true
            ],
            'content' => [
                'type' => 'summernote',
                'rules' => ['required'],
                'required_all_lang' => false
            ],
        );
    }
}

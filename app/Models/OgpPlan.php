<?php

namespace App\Models;

use App\Enums\DocTypesEnum;
use App\Traits\FilterSort;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
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

    protected $fillable = ['from_date', 'to_date', 'active', 'author_id', 'ogp_status_id'];
    protected $translatedAttributes = OgpPlan::TRANSLATABLE_FIELDS;

    public function scopeActive($query)
    {
        return $query->where('ogp_plan.active', '=', true);
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
                'rules' => ['required', 'string', 'max:255']
            ],
            'content' => [
                'type' => 'summernote',
                'rules' => ['required']
            ],
        );
    }
}

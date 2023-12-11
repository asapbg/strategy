<?php

namespace App\Models;

use App\Models\StrategicDocuments\Institution;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class InstitutionLink extends ModelActivityExtend implements TranslatableContract
{
    use Translatable;

    const TRANSLATABLE_FIELDS = ['title'];
    const MODULE_NAME = 'custom.institution_links';
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    public $timestamps = true;
    protected $table = 'institution_link';

    //activity
    protected string $logName = "institution_link";

    protected $fillable = ['institution_id', 'link'];

    /**
     * Get the model name
     */
    public function getModelName() {
        return $this->title;
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Institution::class, 'id', 'institution_id');
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'title' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:500']
            ]
        );
    }
}

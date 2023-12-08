<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;

class DynamicStructureColumn extends ModelActivityExtend
{
    use Translatable;
    public $timestamps = true;
    protected $fillable = ['type', 'ord', 'dynamic_structure_id', 'dynamic_structure_groups_id'];

    const TRANSLATABLE_FIELDS = ['label'];
    const MODULE_NAME = ('custom.dynamic_structures.columns');
//
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    protected $table = 'dynamic_structure_column';

    //activity
    protected string $logName = "dynamic_structure_columns";

    /**
     * Get model name
     * @return mixed
     */
    public function getModelName(): mixed
    {
        return $this->label;
    }

    public static function translationFieldsProperties(): array
    {
        return array(
            'label' => [
                'type' => 'text',
                'rules' => ['required', 'string', 'max:255']
            ],
        );
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DynamicStructureGroup::class, 'id', 'dynamic_structure_groups_id');
    }
}

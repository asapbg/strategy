<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;

class DynamicStructureGroup extends ModelActivityExtend
{
    use Translatable;
    public $timestamps = true;
    protected $fillable = ['ord', 'dynamic_structure_id'];

    const TRANSLATABLE_FIELDS = ['label'];
    const MODULE_NAME = ('dynamic_structures.groups');
//
    public array $translatedAttributes = self::TRANSLATABLE_FIELDS;

    protected $table = 'dynamic_structure_group';

    //activity
    protected string $logName = "dynamic_structure_groups";

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
}

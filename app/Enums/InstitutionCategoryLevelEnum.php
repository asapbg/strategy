<?php

namespace App\Enums;

use App\Models\ActType;
use App\Models\FieldOfAction;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum InstitutionCategoryLevelEnum: int
{
    use Names, Options, Values;

    //Public consultation
    case CENTRAL = 1; //Централно
    case CENTRAL_OTHER = 2; //Ценрално друго
    case AREA = 3; //Областно
    case MUNICIPAL = 4; //Общинско ниво


    // Return enum name by value
    public static function keyByValue($searchVal): string
    {
        $keyName = '';
        foreach (self::options() as $key => $val) {
            if( $val == $searchVal) {
                $keyName = $key;
            }
        }
        return $keyName;
    }

    public static function keyToLabel(): array
    {
        return [
            self::CENTRAL->value => __('custom.nomenclature_level.'.self::CENTRAL->name),
            self::CENTRAL_OTHER->value => __('custom.nomenclature_level.'.self::CENTRAL_OTHER->name),
            self::AREA->value => __('custom.nomenclature_level.'.self::AREA->name),
            self::MUNICIPAL->value => __('custom.nomenclature_level.'.self::MUNICIPAL->name)
        ];
    }

    public static function fieldOfActionCategory($value): int
    {
        $categories = [
            self::CENTRAL->value => FieldOfAction::CATEGORY_NATIONAL,
            self::CENTRAL_OTHER->value => FieldOfAction::CATEGORY_NATIONAL,
            self::AREA->value => FieldOfAction::CATEGORY_AREA,
            self::MUNICIPAL->value => FieldOfAction::CATEGORY_MUNICIPAL
        ];
        return $categories[$value] ?? -1;
    }

    public static function convertFoaLevel($value): int
    {
        $categories = [
            FieldOfAction::CATEGORY_NATIONAL => self::CENTRAL->value,
            FieldOfAction::CATEGORY_AREA => self::AREA->value,
            FieldOfAction::CATEGORY_MUNICIPAL => self::MUNICIPAL->value
        ];
        return $categories[$value] ?? -1;
    }
}

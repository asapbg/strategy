<?php

namespace App\Enums;

use App\Models\ActType;
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
            self::CENTRAL->value => 1,
            self::CENTRAL_OTHER->value => 1,
            self::AREA->value => 2,
            self::MUNICIPAL->value => 3
        ];
        return $categories[$value] ?? -1;
    }
}

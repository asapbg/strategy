<?php

namespace App\Enums;

use App\Models\DynamicStructure;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum DynamicStructureTypesEnum: int
{
    use Names, Options, Values;

    case LEGISLATIVE_PROGRAM = 1; //Законодателна програма
    case OPERATIONAL_PROGRAM = 2; //Оперативна програма
    case CONSULT_DOCUMENTS = 3; //Консултационни документи

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

    public static function hasGroupField($searchVal): string
    {
        if( in_array($searchVal, [self::CONSULT_DOCUMENTS->value]) ) {
            return true;
        }
        return false;
    }

}

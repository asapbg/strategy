<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum PrisDocChangeTypeEnum: int
{
    use Names, Options, Values;

    case CHANGE = 1; //изменя
    case COMPLEMENTS = 2; //допълва
    case CANCEL = 3; //отменя

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

}

<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum DocTypesEnum: int
{
    use Names, Options, Values;

    case PC_IMPACT_EVALUATION = 1; //Оценка на въздействие
    case PC_IMPACT_EVALUATION_OPINION = 2; //Становище на администарцията на Министерски съвет по тази оценка

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

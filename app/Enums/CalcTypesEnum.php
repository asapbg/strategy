<?php

namespace App\Enums;

use App\Models\ActType;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum CalcTypesEnum: string
{
    use Names, Options, Values;

    //Public consultation
    case STANDARD_COST = 'standard'; //Модел на стандартните разходи
    case COSTS_AND_BENEFITS = 'cost_and_benefits'; //Анализ на разходите и на ползите
    case COST_EFFECTIVENESS = 'cost_effectiveness'; //Анализ на ефективността на разходите
    case MULTICRITERIA = 'multicriteria'; //Мултикритериен анализ

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

    public static function btnClass($value): string
    {
        $arr = [
            self::STANDARD_COST->value => 'navy-marine-bgr',
            self::COSTS_AND_BENEFITS->value => 'gr-color-bgr',
            self::COST_EFFECTIVENESS->value => 'light-blue-bgr',
            self::MULTICRITERIA->value => 'dark-blue-bgr',
        ];
        return $arr[$value];
    }

}

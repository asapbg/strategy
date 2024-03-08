<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum OldNationalPlanEnum: int
{
    use Names, Options, Values;

    case FIRST = 1;
    case SECOND = 2;

    case THIRD = 3;

    public static function nameByValue($searchVal): string
    {
        $keyName = '';
        foreach (self::options() as $key => $val) {
            if( $val == $searchVal) {
                $keyName = $key;
            }
        }
        return __('custom.old_pan_names.'.$keyName);
    }


    public static function fromDateByValue($searchVal): string
    {
        $dates = [
            self::FIRST->value => '01.01.2013',
            self::SECOND->value => '01.01.2014',
            self::THIRD->value => '01.07.2016',
        ];
        return $dates[$searchVal];
    }

    public static function toDateByValue($searchVal): string
    {
        $dates = [
            self::FIRST->value => '31.12.2013',
            self::SECOND->value => '31.12.2015',
            self::THIRD->value => '30.06.2018',
        ];
        return $dates[$searchVal];
    }
}

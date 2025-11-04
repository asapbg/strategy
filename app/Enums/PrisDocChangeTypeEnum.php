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

    case AMENDMENT = 4; //поправка
    case SEE_IN = 5; //виж

    case OTHER = 6; //ДРУГО

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

    public static function toStatus($val) {
        $statuses = [
            self::CHANGE->value => PrisConnectionStatusEnum::CHANGED->value,
            self::COMPLEMENTS->value => PrisConnectionStatusEnum::SUPPLEMENTED->value,
            self::CANCEL->value => PrisConnectionStatusEnum::CANCELED->value,
            self::AMENDMENT->value => PrisConnectionStatusEnum::AMENDMENT->value,
            self::SEE_IN->value => PrisConnectionStatusEnum::SEE->value,
            self::OTHER->value => PrisConnectionStatusEnum::OTHER->value
        ];
        return $statuses[$val];
    }

}

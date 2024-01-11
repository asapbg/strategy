<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum AdvisoryTypeEnum: int
{

    use Names, Options, Values;

    case CHAIRMAN = 1; // Председател
    case MEMBER = 2; // Член
    case VICE_CHAIRMAN = 3; // Заместник-председател
    case SECRETARY = 4; // Секретар

    public static function nameByValue($value){
        $names = [
            self::CHAIRMAN->value => strtolower(self::CHAIRMAN->name),
            self::MEMBER->value => strtolower(self::MEMBER->name),
            self::VICE_CHAIRMAN->value => strtolower(self::VICE_CHAIRMAN->name),
            self::SECRETARY->value => strtolower(self::SECRETARY->name),
        ];
        return $names[$value];
    }
}

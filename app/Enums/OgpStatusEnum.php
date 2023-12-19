<?php

namespace App\Enums;

use App\Models\ActType;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum OgpStatusEnum: int
{
    use Names, Options, Values;

    case DRAFT = 1; //чернова
    case IN_DEVELOPMENT = 2; //В разработка
    case FINAL = 3; //финализирай план
    case ACTIVE = 4; //Действащ

    public static function fromName(string $name){

        return constant("self::$name");
    }
}

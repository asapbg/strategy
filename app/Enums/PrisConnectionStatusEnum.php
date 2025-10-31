<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum PrisConnectionStatusEnum: int
{
    use Names, Options, Values;

    case CHANGED = 1; //изменен
    case SUPPLEMENTED = 2; //допълнен
    case CANCELED = 3; //отменен
    case AMENDMENT = 4; //поправка
    case SEE = 5; //виж
    case CONFIDENTIAL = 6; //поверителен
}

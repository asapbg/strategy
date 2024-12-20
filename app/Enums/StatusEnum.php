<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum StatusEnum: int
{
    use Names, Options, Values;

    case ACTIVE = 1; // Активен
    case INACTIVE = 0; // Неактивен
    case ALL = -1; // Всички
}

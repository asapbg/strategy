<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum LegislativeInitiativeStatusesEnum: int
{

    use Names, Options, Values;

    case STATUS_ACTIVE = 1; // Отворена
    case STATUS_SEND = 2; // Изпратена до администрация
    case STATUS_CLOSED = 3; // Затворена
}

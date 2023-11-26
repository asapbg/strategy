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
}

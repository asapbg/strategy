<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum OgpAreaArrangementFieldEnum: int
{
    use Names, Options, Values;

    case CONTEXT = 1; // Контекст
    case MAIN_GOAL = 2; // Основна цел:
    case AMBITION = 3; // Амбиция
    case RELEVANCE_ALIGNMENT_POU_GRAND_CHALLENGES = 4; // Релевантност и съответствие с големите предизвикателства на ПОУ
    case RESPONSIBLE_INSTITUTION = 5; // Отговорна институция
    case OTHER_INVOLVED_PUBLIC_INSTITUTIONS = 6; // Други ангажирани публични институции
    case EXPECTED_IMPACT = 7; // Очаквано въздействие
    case DEADLINE = 8; // Срок на изпълнение

    public static function fromName(string $name){

        return constant("self::$name");
    }
}

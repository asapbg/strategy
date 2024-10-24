<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum PollStatusEnum: int
{
    use Names, Options, Values;

    case INACTIVE = 0; //неактивна
    case ACTIVE = 1; //активна
    case EXPIRED = 2; //неактивна

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

    public static function statusOptions(string $anyText = '---')
    {
        return [
            ['value' => 0, 'name' => $anyText],
            ['value' => self::ACTIVE->value, 'name' => __('custom.active')],
            ['value' => self::EXPIRED->value, 'name' => __('custom.closed_f')],
        ];
    }

}

<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum PublicationTypesEnum: int
{
    use Names, Options, Values;

    case TYPE_LIBRARY = 1;
    case TYPE_NEWS = 3;
    case TYPE_OGP_NEWS = 2;

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

    /**
     * Get publication type name's list
     *
     * @return array
     */
    public static function getTypeName()
    {
        return [
            self::TYPE_LIBRARY->value       => 'custom.public_sections.types.TYPE_LIBRARY',
            self::TYPE_NEWS->value          => 'custom.public_sections.types.TYPE_NEWS',
            self::TYPE_OGP_NEWS->value      => 'custom.public_sections.types.TYPE_OGP_NEWS'
        ];
    }

}

<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum PageModulesEnum: int
{
    use Names, Options, Values;

    case MODULE_IMPACT_ASSESSMENT = 1;
    case MODULE_OGP = 2;

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
    public static function getModuleName(): array
    {
        return [
            self::MODULE_IMPACT_ASSESSMENT->value           => 'custom.page.module.MODULE_IMPACT_ASSESSMENT',
            self::MODULE_OGP->value           => 'custom.page.module.MODULE_IMPACT_ASSESSMENT',
        ];
    }

}

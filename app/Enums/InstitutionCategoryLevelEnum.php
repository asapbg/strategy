<?php

namespace App\Enums;

use App\Models\ActType;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum InstitutionCategoryLevelEnum: int
{
    use Names, Options, Values;

    //Public consultation
    case CENTRAL = 1; //Централно
    case CENTRAL_OTHER = 2; //Ценрално друго
    case AREA = 3; //Областно
    case MUNICIPAL = 4; //Общинско ниво


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
}

<?php

namespace App\Enums;

use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum PublicConsultationTimelineEnum: int
{
    use Names, Options, Values;

    case INCLUDE_TO_PROGRAM = 1; //	Включване на проекта на акт в законодателната или оперативната програма на Министерския съвет
    case START = 2; //	Начало на обществената консултация
    case FILE_CHANGE = 3; //	Промяна на файл (само на файл) от обществената консултация – възможно е да има няколко такива събития.
    case END = 4; //	Приключване на консултацията
    case PUBLISH_PROPOSALS_REPORT = 5; //	Публикуване на справка за получените предложения или на съобщение за неполучени предложения
    case ACCEPT_ACT_MC = 6; //	Приемане на акта от Министерския съвет
    case PRESENTING_IN_NA = 7; //	Представяне на законопроекта в страницата на Народното събрание

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

    public static function ordered()
    {
        return [
            self::INCLUDE_TO_PROGRAM->value,
            self::START->value,
            self::FILE_CHANGE->value,
            self::END->value,
            self::PUBLISH_PROPOSALS_REPORT->value,
            self::ACCEPT_ACT_MC->value,
            self::PRESENTING_IN_NA->value,
        ];
    }

    public static function noTimelineRecord()
    {
        return [
            self::START->value,
            self::END->value,
            self::ACCEPT_ACT_MC->value,
            self::PRESENTING_IN_NA->value,
        ];
    }

}

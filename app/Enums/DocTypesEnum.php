<?php

namespace App\Enums;

use App\Models\ActType;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum DocTypesEnum: int
{
    use Names, Options, Values;

    //Public consultation
    case PC_IMPACT_EVALUATION = 1; //Оценка на въздействие
    case PC_IMPACT_EVALUATION_OPINION = 2; //Становище на администарцията на Министерски съвет по тази оценка
    case PC_REPORT = 3; //Доклад
    case PC_DRAFT_ACT = 4; //Проект на акт
    case PC_MOTIVES = 5; //Мотиви
    case PC_CONSOLIDATED_ACT_VERSION = 6; //консолидирана версия на акта
    case PC_OTHER_DOCUMENTS = 7; //Други документи
    case PC_COMMENTS_REPORT = 8; //Справка за получените предложения/съобщение за неполучени предложения (публикува се по късно, както е обяснено по-долу) - след приключване на консултацията

    case PC_COMMENTS_CSV = 9; //Списък с коментари
    case PC_COMMENTS_PDF = 10; //Списък с коментари
    case PC_KD_PDF = 11; //Консултационен документ

    // Advisory board - Консултативен съвет
    case AB_FUNCTION = 12; // Файл към функции на консултативен съвет
    case AB_SECRETARIAT = 13; // Файл към секретариат на консултативен съвет

    case AB_REGULATORY_FRAMEWORK = 14; // Файл към нормативна рамка на консултативен съвет

    case AB_MEETINGS_AND_DECISIONS = 15; // Файл към заседания и решения на консултативен съвет

    case AB_CUSTOM_SECTION = 16; // Файл към ръчно създадена секция от модератор към консултативен съвет

    //TODO ask Izi about next two documents. Are they same as 1 and 2 ???
    //case PC_preliminary_IMPACT_EVALUATION = 6; //Предварителна оценка на въздействието
    //case PC_MC_IMPACT_EVALUATION_OPINION = 6; //Становище на администрацията на Министерския съвет

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
     * @param $actType
     * @param $section {base|kd|report}
     * @return array
     */
    public static function docByActTypeInSections($actType, $section = 'base')
    {
        switch ($actType)
        {
            case ActType::ACT_LAW:
            case ActType::ACT_COUNCIL_OF_MINISTERS:
                $docs = [
                    'base' => [
                        self::PC_DRAFT_ACT->value,
                        self::PC_REPORT->value,
                        self::PC_MOTIVES->value,
                        self::PC_OTHER_DOCUMENTS->value,
                        self::PC_COMMENTS_REPORT->value,
                        self::PC_IMPACT_EVALUATION->value,
                        self::PC_IMPACT_EVALUATION_OPINION->value,
                        self::PC_CONSOLIDATED_ACT_VERSION->value,
                    ],
                    'kd' => [
                        self::PC_KD_PDF->value,
                    ],
                    'report' => [
                        //self::PC_IMPACT_EVALUATION->value,
                        //self::PC_IMPACT_EVALUATION_OPINION->value,
                    ],
                ];
                break;
            case ActType::ACT_MINISTER:
            case ActType::ACT_OTHER_CENTRAL_AUTHORITY:
            case ActType::ACT_REGIONAL_GOVERNOR:
            case ActType::ACT_MUNICIPAL:
            case ActType::ACT_MUNICIPAL_MAYOR:
            $docs = [
                'base' => [
                    self::PC_DRAFT_ACT->value,
                    self::PC_MOTIVES->value,
                    self::PC_OTHER_DOCUMENTS->value,
                    self::PC_COMMENTS_REPORT->value,
                    self::PC_CONSOLIDATED_ACT_VERSION->value,
                ],
                'kd' => [
                    self::PC_KD_PDF->value,
                ],
                'report' => [],
            ];
                break;
            default:
                $docs = [
                    'base' => [
                        self::PC_DRAFT_ACT->value,
                        self::PC_OTHER_DOCUMENTS->value,
                        self::PC_COMMENTS_REPORT->value,
                    ],
                    'kd' => [],
                    'report' => [],
                ];
        }

        return $docs[$section];
    }

    public static function docsByActType($actType)
    {
        switch ($actType)
        {
            case ActType::ACT_LAW:
            case ActType::ACT_COUNCIL_OF_MINISTERS:
                $docs = [
                    self::PC_REPORT->value,
                    self::PC_DRAFT_ACT->value,
                    self::PC_MOTIVES->value,
                    self::PC_IMPACT_EVALUATION->value,
                    self::PC_IMPACT_EVALUATION_OPINION->value,
                    self::PC_CONSOLIDATED_ACT_VERSION->value,
                    self::PC_OTHER_DOCUMENTS->value,
                    self::PC_COMMENTS_REPORT->value,
                ];
                break;
            case ActType::ACT_MINISTER:
            case ActType::ACT_OTHER_CENTRAL_AUTHORITY:
            case ActType::ACT_REGIONAL_GOVERNOR:
            case ActType::ACT_MUNICIPAL:
            case ActType::ACT_MUNICIPAL_MAYOR:
                $docs = [
                    self::PC_DRAFT_ACT->value,
                    self::PC_MOTIVES->value,
                    self::PC_CONSOLIDATED_ACT_VERSION->value,
                    self::PC_OTHER_DOCUMENTS->value,
                    self::PC_COMMENTS_REPORT->value,
                ];
                break;
            default:
                $docs = [
                    self::PC_DRAFT_ACT->value,
                    self::PC_OTHER_DOCUMENTS->value,
                    self::PC_COMMENTS_REPORT->value,
                ];
        }

        return $docs;
    }

    public static function docsByActTypePublic($actType)
    {
        switch ($actType)
        {
            case ActType::ACT_LAW:
            case ActType::ACT_COUNCIL_OF_MINISTERS:
                $docs = [
                    self::PC_REPORT->value,
                    self::PC_DRAFT_ACT->value,
                    self::PC_MOTIVES->value,
                    self::PC_IMPACT_EVALUATION->value,
                    self::PC_IMPACT_EVALUATION_OPINION->value,
                    self::PC_CONSOLIDATED_ACT_VERSION->value,
                    self::PC_OTHER_DOCUMENTS->value,
                    self::PC_COMMENTS_REPORT->value,
                    self::PC_KD_PDF->value,
                ];
                break;
            case ActType::ACT_MINISTER:
            case ActType::ACT_OTHER_CENTRAL_AUTHORITY:
            case ActType::ACT_REGIONAL_GOVERNOR:
            case ActType::ACT_MUNICIPAL:
            case ActType::ACT_MUNICIPAL_MAYOR:
                $docs = [
                    self::PC_DRAFT_ACT->value,
                    self::PC_MOTIVES->value,
                    self::PC_CONSOLIDATED_ACT_VERSION->value,
                    self::PC_OTHER_DOCUMENTS->value,
                    self::PC_COMMENTS_REPORT->value,
                    self::PC_KD_PDF->value,
                ];
                break;
            default:
                $docs = [
                    self::PC_DRAFT_ACT->value,
                    self::PC_OTHER_DOCUMENTS->value,
                    self::PC_COMMENTS_REPORT->value,
                ];
        }

        return $docs;
    }

    public static function validationRules($value, $locale)
    {
        switch ($value)
        {
            case self::PC_CONSOLIDATED_ACT_VERSION->value:
                $rules = [
                    'bg' => ['nullable', 'mimes:doc,docx,pdf', 'max:'.config('filesystems.max_upload_file_size')],
                    'en' => ['nullable', 'mimes:doc,docx,pdf', 'max:'.config('filesystems.max_upload_file_size')],
                    ];
                break;
            case self::PC_COMMENTS_REPORT->value:
                $rules = [
                    'bg' => ['required', 'mimes:pdf', 'max:'.config('filesystems.max_upload_file_size')],
                    'en' => ['nullable', 'mimes:pdf', 'max:'.config('filesystems.max_upload_file_size')],
                ];
                break;
            default:
                $rules = [
                    'bg' => ['nullable', 'mimes:doc,docx,pdf', 'max:'.config('filesystems.max_upload_file_size')],
                    'en' => ['nullable', 'mimes:doc,docx,pdf', 'max:'.config('filesystems.max_upload_file_size')],
                ];
        }

        return $rules[$locale];
    }

}

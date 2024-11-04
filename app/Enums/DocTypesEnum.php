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
    case PC_IMPACT_EVALUATION_ATT = 100; //Оценка на въздействие приложение
    case PC_IMPACT_EVALUATION_OPINION = 2; //Становище на администарцията на Министерски съвет по тази оценка
    case PC_IMPACT_EVALUATION_OPINION_ATT = 200; //Становище на администарцията на Министерски съвет по тази оценка  (приложение)
    case PC_REPORT = 3; //Доклад
    case PC_REPORT_ATT = 300; //Доклад приложение
    case PC_DRAFT_ACT = 4; //Проект на акт
    case PC_DRAFT_ACT_ATT = 400; //Проект на акт (приложение)
    case PC_MOTIVES = 5; //Мотиви
    case PC_MOTIVES_ATT = 500; //Мотиви  приложение
    case PC_CONSOLIDATED_ACT_VERSION = 6; //консолидирана версия на акта
    case PC_CONSOLIDATED_ACT_VERSION_ATT = 600; //консолидирана версия на акта приложение
    case PC_OTHER_DOCUMENTS = 7; //Други документи
    case PC_OTHER_DOCUMENTS_ATT = 700; //Други документи приложение
    case PC_COMMENTS_REPORT = 8; //Справка за получените предложения/съобщение за неполучени предложения (публикува се по късно, както е обяснено по-долу) - след приключване на консултацията
    case PC_COMMENTS_REPORT_ATT = 800; //Справка за получените предложения/съобщение за неполучени предложения (публикува се по късно, както е обяснено по-долу) - след приключване на консултацията приложение

    case PC_COMMENTS_CSV = 9; //Списък с коментари
    case PC_COMMENTS_PDF = 10; //Списък с коментари

    case PC_POLLS_PDF = 21; //Списък с Анкети
    case PC_KD_PDF = 11; //Консултационен документ
    case PC_KD_PDF_ATT = 1100; //Консултационен документ приложение

    // Advisory board - Консултативен съвет
    case AB_FUNCTION = 12; // Файл към функции на консултативен съвет
    case AB_SECRETARIAT = 13; // Файл към секретариат на консултативен съвет

    case AB_ORGANIZATION_RULES = 14; // Файл към нормативна рамка правилник на вътрешния ред на консултативен съвет

    case AB_ESTABLISHMENT_RULES = 18; // Файл към нормативна рамка акт на създаване на консултативен съвет

    case AB_MEETINGS_AND_DECISIONS = 15; // Файл към заседания и решения на консултативен съвет

    case AB_CUSTOM_SECTION = 16; // Файл към ръчно създадена секция от модератор към консултативен съвет

    case AB_MODERATOR = 17; // Файл към Информация за модератора „Консултативен съвет“
    case OGP_VERSION_AFTER_CONSULTATION = 19; // Файл към OGP
    case OGP_REPORT_EVALUATION = 20; // Доклади за независима оценка

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

    public static function pcDocTypes()
    {
        return [
            self::PC_DRAFT_ACT->value,
            self::PC_REPORT->value,
            self::PC_MOTIVES->value,
            self::PC_OTHER_DOCUMENTS->value,
            self::PC_IMPACT_EVALUATION->value,
            self::PC_IMPACT_EVALUATION_OPINION->value,
            self::PC_CONSOLIDATED_ACT_VERSION->value,
            self::PC_COMMENTS_REPORT->value,
            self::PC_KD_PDF->value
        ];
    }

    public static function pcDocAttTypes()
    {
        return [
            self::PC_DRAFT_ACT_ATT->value,
            self::PC_REPORT_ATT->value,
            self::PC_MOTIVES_ATT->value,
            self::PC_OTHER_DOCUMENTS_ATT->value,
            self::PC_IMPACT_EVALUATION_ATT->value,
            self::PC_IMPACT_EVALUATION_OPINION_ATT->value,
            self::PC_CONSOLIDATED_ACT_VERSION_ATT->value,
            self::PC_COMMENTS_REPORT_ATT->value,
            self::PC_KD_PDF_ATT->value
        ];
    }

    public static function pcMissingDocTypesSelect()
    {
        return array(
            ['value' => '', 'name' => ''],
            ['value' => self::PC_DRAFT_ACT->value, 'name' =>  __('custom.public_consultation.doc_type.'.self::PC_DRAFT_ACT->value)],
            ['value' => self::PC_REPORT->value.'_'.self::PC_MOTIVES->value, 'name' =>  __('custom.public_consultation.doc_type.'.self::PC_REPORT->value).'/'.__('custom.public_consultation.doc_type.'.self::PC_MOTIVES->value)],
            ['value' => self::PC_IMPACT_EVALUATION->value, 'name' =>  __('custom.public_consultation.doc_type.'.self::PC_IMPACT_EVALUATION->value)],
            ['value' => self::PC_IMPACT_EVALUATION_OPINION->value, 'name' =>  __('custom.public_consultation.doc_type.'.self::PC_IMPACT_EVALUATION_OPINION->value)]
        );
    }

    public static function pcRequiredDocTypesByActType($actType)
    {
        if(is_null($actType)){
            return [];
        }
        switch ($actType)
        {
            case ActType::ACT_LAW:
            case ActType::ACT_COUNCIL_OF_MINISTERS:
                $docs = [
                    self::PC_DRAFT_ACT->value,
                    self::PC_REPORT->value,
                    self::PC_MOTIVES->value,
                    self::PC_IMPACT_EVALUATION->value,
                    self::PC_IMPACT_EVALUATION_OPINION->value
                ];
                break;
            case ActType::ACT_MINISTER:
            case ActType::ACT_OTHER_CENTRAL_AUTHORITY:
            case ActType::ACT_REGIONAL_GOVERNOR:
            case ActType::ACT_MUNICIPAL:
            case ActType::ACT_MUNICIPAL_MAYOR:
                $docs = [
                    self::PC_DRAFT_ACT->value,
                    self::PC_MOTIVES->value
                ];
                break;
            default:
                $docs = [
                    self::PC_DRAFT_ACT->value
                ];
        }

        return $docs;
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
                        //self::PC_COMMENTS_REPORT->value,
                        self::PC_IMPACT_EVALUATION->value,
                        self::PC_IMPACT_EVALUATION_OPINION->value,
                        self::PC_CONSOLIDATED_ACT_VERSION->value,
                    ],
                    'kd' => [
                        self::PC_KD_PDF->value,
                    ],
                    'report' => [
                        self::PC_COMMENTS_REPORT->value
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
                    //self::PC_COMMENTS_REPORT->value,
                    self::PC_CONSOLIDATED_ACT_VERSION->value,
                ],
                'kd' => [
                    self::PC_KD_PDF->value,
                ],
                'report' => [
                    self::PC_COMMENTS_REPORT->value
                ],
            ];
                break;
            default:
                $docs = [
                    'base' => [
                        self::PC_DRAFT_ACT->value,
                        self::PC_OTHER_DOCUMENTS->value,
                        //self::PC_COMMENTS_REPORT->value,
                    ],
                    'kd' => [],
                    'report' => [
                        self::PC_COMMENTS_REPORT->value
                    ],
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

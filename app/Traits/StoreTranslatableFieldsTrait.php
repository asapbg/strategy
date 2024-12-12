<?php

namespace App\Traits;

trait StoreTranslatableFieldsTrait
{

    /**
     * @param $fields    //example model::TRANSLATABLE_FIELDS;
     * @param $item      //model;
     * @param $validated //request validated
     */
    public function storeTranslateOrNew($fields, $item, $validated, $setDefaultIfEmpty = false)
    {
        $defaultLang = config('app.default_lang');

        foreach (config('available_languages') as $locale) {
            $mainLang = $locale['code'] == $defaultLang;

            foreach ($fields as $field) {
                $fieldName = $field . "_" . $locale['code'];
                $fieldNameDefault = $field . "_" . $defaultLang;

                if (array_key_exists($fieldName, $validated)) {
                    $item->translateOrNew($locale['code'])->{$field} = $validated[$fieldName];
                } else if (!$mainLang && array_key_exists($fieldNameDefault, $validated)) {
                    if ($setDefaultIfEmpty) {
                        //by default set default language translation
                        $item->translateOrNew($locale['code'])->{$field} = $validated[$fieldNameDefault];
                    } else {
                        //do not set default language translation
                        $item->translateOrNew($locale['code'])->{$field} = '';
                    }
                }
            }
        }

        $item->save();

        return $item;
    }
}

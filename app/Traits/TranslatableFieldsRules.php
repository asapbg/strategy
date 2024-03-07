<?php

namespace App\Traits;

trait TranslatableFieldsRules
{

    /**
     * @param array $rules
     * @param array $translatableProperties
     * @return array
     */
    public function getRules(array $rules, array $translatableProperties): array
    {
        $defaultLang = config('app.default_lang');
        foreach (config('available_languages') as $lang) {
            foreach ($translatableProperties as $field => $properties) {
                $fieldName = $field . '_' . $lang['code'];
                $mainLang = $lang['code'] == $defaultLang;
                $fieldRules = $properties['rules'];
                if(isset($properties['required_all_lang']) && !$properties['required_all_lang'] && !$mainLang) {
                    if (($key = array_search('required', $fieldRules)) !== false) {
                        if(empty(request()->input($fieldName))){
                            foreach ($fieldRules as $i => $r){
                                if($r == 'required'){
                                    $fieldRules[$i] = 'nullable';
                                }
                            }
                        }
                    }
                }

                if(sizeof($fieldRules)) {
                    $rules[$fieldName] = $fieldRules;
                }
            }
        }
        return $rules;
    }
}

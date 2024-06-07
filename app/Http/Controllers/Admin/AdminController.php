<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Pris;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    use ValidatesRequests;

    /**
     * Store same values for all available languages.
     *
     * @param $fields
     * @param $item
     * @param $validated
     *
     * @return void
     */
    protected function storeWithoutTranslate($fields, $item, $validated)
    {
        foreach (config('available_languages') as $locale) {
            foreach ($fields as $field) {
                if (array_key_exists($field, $validated)) {
                    $item->translateOrNew($locale['code'])->{$field} = $validated[$field];
                }
            }
        }

        $item->save();
    }

    /**
     * @param $fields  //example model::TRANSLATABLE_FIELDS;
     * @param $item   //model;
     * @param $validated //request validated
     */
    public function storeTranslateOrNew($fields, $item, $validated, $setDefaultIfEmpty = false)
    {
        $defaultLang = config('app.default_lang');
        foreach (config('available_languages') as $locale) {
            $mainLang = $locale['code'] == $defaultLang;
            foreach ($fields as $field) {
                $fieldName = $field."_".$locale['code'];
                $fieldNameDefault = $field."_".$defaultLang;
//                dd($fields, $field, $fieldName, $validated);
                if(array_key_exists($fieldName, $validated)) {
                    $item->translateOrNew($locale['code'])->{$field} = $validated[$fieldName];
                } else if(!$mainLang && array_key_exists($fieldNameDefault, $validated)){
                    if($setDefaultIfEmpty) {
                        //by default set default language translation
                        $item->translateOrNew($locale['code'])->{$field} = $validated[$fieldNameDefault];
                    } else{
                        //do not set default language translation
                        $item->translateOrNew($locale['code'])->{$field} = '';
                    }
                }
            }
        }

        $item->save();
    }

    public function translateChanges($fields, $item, $validated)
    {
        $changes = array();
        foreach (config('available_languages') as $locale) {
            foreach ($fields as $field) {
                $fieldName = $field."_".$locale['code'];
                if((is_null($item) || !$item->id)
                    || (!isset($validated[$fieldName]) && ($item->id && !empty($item->translate($locale['code'])->{$field})) )
                    || ($item->id && isset($validated[$fieldName]) && $item->translate($locale['code'])->{$field} != $validated[$fieldName])){
                    if(!isset($changes[$locale['code']])){
                        $changes[$locale['code']] = array();
                    }
                    $changes[$locale['code']][$field] = ['old' => $item->id ? $item->translate($locale['code'])->{$field} : '', 'new' => $validated[$fieldName]];
                }
            }
        }

        return $changes;
    }

    public function mainChanges($fields, $item, $validated)
    {
        $changes = array();
        foreach ($fields as $field) {
            if((!isset($validated[$field]) && ($item->id && !empty($item->{$field})) )
                || ($item->id && isset($validated[$field]) && $item->{$field} != $validated[$field])
                || !$item->id){
                $changes[$field] = ['old' => $item->id ? $item->{$field} : '', 'new' => isset($validated[$field]) ? $validated[$field] : ''];
            }
        }
        return $changes;
    }

    //TODO Why we use only translation for current locale???
    /**
     * @param $fields  //example $item->getFillable();
     * @param $item   //model;
     * @param $validated //request validated
     */
    protected function storeTranslateOrNewCurrent($fields, $item, $validated)
    {
        $locale = app()->getLocale();
        foreach ($fields as $field) {
            $fieldName = $field .'_'. $locale;
            if(array_key_exists($fieldName, $validated)) {
                $item->translateOrNew($locale)->{$field} = $validated[$fieldName];
            }
        }

        $item->save();
    }

    /**
     * Retiurn only fillable fields from validated request data
     * @param $validated
     * @param $item
     * @return mixed
     */
    public function getFillableValidated($validated, $item)
    {
        $modelFillable = $item->getFillable();
        $validatedFillable = $validated;
        foreach ($validatedFillable as $field => $value) {
            if( !in_array($field, $modelFillable) ) {
                unset($validatedFillable[$field]);
            }
        }
        return $validatedFillable;
    }

    protected function uploadFileTwoLanguages(Request $request, $prisId = 0)
    {
        $validator = Validator::make($request->all(), [
            'file_bg' => ['required', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_PRIS)],
            'file_en' => ['nullable', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', File::ALLOWED_FILE_PRIS)],
        ]);

        $item = Pris::find((int)$prisId);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }

    }

    /**
     * Upload file in public disk
     *
     * @param             $item
     * @param             $file
     * @param int         $codeObject
     * @param int         $docType
     * @param string      $description
     * @param string      $locale
     * @param string|null $customName
     * @param string|null $resolutionCouncilMinisters // № Постановление на министерски съвет
     * @param string|null $stateNewspaper             // № Държавен вестник
     * @param string|null $effectiveAt                // В сила от (дата)
     *
     * @return File
     */
    protected function uploadFile(
        $item,
        $file,
        int $codeObject,
        $docType = 0,
        $description = '',
        $locale = 'bg',
        string $customName = null,
        string $resolutionCouncilMinisters = null,
        string $stateNewspaper = null,
        string $effectiveAt = null
    )
    {
        $path = match ($codeObject) {
            File::CODE_OBJ_LEGISLATIVE_PROGRAM,
            File::CODE_OBJ_OPERATIONAL_PROGRAM => File::PUBLIC_CONSULTATIONS_UPLOAD_DIR . $item->id . DIRECTORY_SEPARATOR,
            File::CODE_OBJ_PUBLICATION => File::PUBLICATION_UPLOAD_DIR . DIRECTORY_SEPARATOR,
            File::CODE_AB => File::ADVISORY_BOARD_FUNCTION_UPLOAD_DIR . DIRECTORY_SEPARATOR,
            default => '',
        };
        $fileNameToStore = str_replace('.', '', microtime(true)) . '.' . $file->getClientOriginalExtension();
        $file->storeAs($path, $fileNameToStore, 'public_uploads');
        $file = new File([
            'id_object' => $item->id ?? $item,
            'code_object' => $codeObject,
            'doc_type' => $docType,
            'filename' => $fileNameToStore,
            'content_type' => $file->getClientMimeType(),
            'path' => DIRECTORY_SEPARATOR . $path . $fileNameToStore,
            'sys_user' => auth()->user()->id,
            'description_' . $locale => !empty($description) ? $description : null,
            'locale' => $locale,
            'custom_name' => $customName,
            'resolution_council_ministers' => $resolutionCouncilMinisters,
            'state_newspaper' => $stateNewspaper,
            'effective_at' => $effectiveAt,
        ]);
        $file->save();
        return $file;
    }

    /**
     * Get available languages
     *
     * @return array
     */
    public static function getLanguages()
    {
        return config('available_languages');
    }
}

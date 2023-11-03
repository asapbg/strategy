<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AdminController extends Controller
{
    use ValidatesRequests;
    /**
     * @param $fields  //example $item->getFillable();
     * @param $item   //model;
     * @param $validated //request validated
     */
    protected function storeTranslateOrNew($fields, $item, $validated)
    {
        foreach (config('available_languages') as $locale) {
            foreach ($fields as $field) {
                $fieldName = $field."_".$locale['code'];
//                dd($fields, $field, $fieldName, $validated);
                if(array_key_exists($fieldName, $validated)) {
                    $item->translateOrNew($locale['code'])->{$field} = $validated[$fieldName];
                }
            }
        }

        $item->save();
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
    protected function getFillableValidated($validated, $item)
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

    /**
     * Upload file in public disk
     * @param $item
     * @param $file
     * @param int $codeObject
     * @param $docType
     * @return File
     */
    protected function uploadFile($item, $file, int $codeObject, $docType = 0)
    {
        $path = match ($codeObject) {
            File::CODE_OBJ_LEGISLATIVE_PROGRAM,
            File::CODE_OBJ_OPERATIONAL_PROGRAM => File::PUBLIC_CONSULTATIONS_UPLOAD_DIR . DIRECTORY_SEPARATOR . $item->id . DIRECTORY_SEPARATOR,
            File::CODE_OBJ_PUBLICATION => File::PUBLICATION_UPLOAD_DIR . DIRECTORY_SEPARATOR,
            default => '',
        };
        $fileNameToStore = round(microtime(true)).'.'.$file->getClientOriginalExtension();
        $file->storeAs($path, $fileNameToStore, 'public_uploads');
        $file = new File([
            'id_object' => $item->id,
            'code_object' => $codeObject,
            'doc_type' => $docType,
            'filename' => $fileNameToStore,
            'content_type' => $file->getClientMimeType(),
            'path' => 'files/'.$path.$fileNameToStore,
            'sys_user' => auth()->user()->id,
        ]);
        $file->save();
        return $file;
    }

}

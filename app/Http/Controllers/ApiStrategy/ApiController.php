<?php

namespace App\Http\Controllers\ApiStrategy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    protected string $api_version;
    protected array $request_inputs;
    protected int $request_limit;
    protected int $request_offset;
    protected string $locale;
    protected string $output_format;
    protected $authanticated;

    const ALLOWED_OUTPUT_FORMAT = ['json', 'xml'];
    const ALLOWED_LOCALE = ['bg', 'en'];
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->api_version = $request->headers->get('version') ?? 'v1';
        $this->request_inputs = $request->input();
        $this->request_limit = isset($this->request_inputs['limit']) ? (int)$this->request_inputs['limit'] : 0;
        $this->request_offset = isset($this->request_inputs['offset']) ? (int)$this->request_inputs['offset'] : 0;
        $this->locale = isset($this->request_inputs['locale']) && in_array($this->request_inputs['locale'], self::ALLOWED_LOCALE)? $this->request_inputs['locale'] : 'bg';
        $this->output_format = isset($this->request_inputs['format']) && in_array($this->request_inputs['format'], self::ALLOWED_OUTPUT_FORMAT)? (int)$this->request_inputs['format'] : 'json';
        $this->authanticated = auth('api')->user();
    }

    public function output($data)
    {
        if($this->output_format == 'xml'){
            //
        } else{
            return response()->json($data, Response::HTTP_OK, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function returnError($code, $msg = '')
    {
        if($this->output_format == 'xml'){
            //
        } else{
            return response()->json(['error' => $msg], $code, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function returnErrors($code, $errors = [])
    {
        if($this->output_format == 'xml'){
            //
        } else{
            return response()->json(['error' => $errors], $code, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function checkDate($str, $format = 'Y-m-d')
    {
        switch ($format){
            case 'Y-m-d':
                $patern = "/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/";
                break;
            case 'Y-m':
                $patern = "/^[0-9]{4}-[0-1][0-9]$/";
                break;
        }

        if (preg_match($patern,$str)) {
            return true;
        } else {
            return false;
        }
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
}

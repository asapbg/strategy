<?php

namespace App\Http\Controllers\ApiStrategy;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController
{
    protected string $api_version;
    protected array $request_inputs;
    protected int $request_limit;
    protected int $request_offset;
    protected string $locale;
    protected string $output_format;

    const ALLOWED_OUTPUT_FORMAT = ['json', 'xml'];
    const ALLOWED_LOCALE = ['bg', 'en'];
    public function __construct(Request $request)
    {
        $this->api_version = $request->headers->get('version') ?? 'v1';
        $this->request_inputs = $request->input();
        $this->request_limit = isset($this->request_inputs['limit']) ? (int)$this->request_inputs['limit'] : 0;
        $this->request_offset = isset($this->request_inputs['offset']) ? (int)$this->request_inputs['offset'] : 0;
        $this->locale = isset($this->request_inputs['locale']) && in_array($this->request_inputs['locale'], self::ALLOWED_LOCALE)? $this->request_inputs['locale'] : 'bg';
        $this->output_format = isset($this->request_inputs['format']) && in_array($this->request_inputs['format'], self::ALLOWED_OUTPUT_FORMAT)? (int)$this->request_inputs['format'] : 'json';
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
}

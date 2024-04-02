<?php

namespace App\Library;

use Carbon\Carbon;
use CkBinData;
use CkCrypt2;
use CkGlobal;
use CkPrivateKey;
use CkRsa;
use CkStringBuilder;
use CkXml;
use Illuminate\Support\Facades\Log;

class Facebook
{
    /**
     * @var string
     * values: {LOW; SUBSTANTIAL; HIGH}
     */
    private string $levelOfAssurance = 'LOW';

    /** @var string $endpoint */
    private string $endpoint;
    private string $pageId;

    public function __construct()
    {
        $this->endpoint = 'https://graph.facebook.com/v19.0';
        $this->pageId = '????';
    }


    public function postOnPage(array $data): array
    {
        foreach (['message', 'link', 'published', 'scheduled_publish_time'] as $k){
            if (!isset($data[$k]) || empty($data[$k])) {
                return array('error' => 1, 'message' => 'Missing parameter: '.$k);
            }
        }
        $result = $this->curlRequest($this->pageId.'/feed', 'post', $data, ["Content-Type: application/json"]);

        if (!isset($result['error']) && (!isset($result[0]) || !isset($result[0]['uniqueIdentificationNumber']))) {
            Log::error('Facebook post on page error. Response: ' . json_encode($result));
            $result['error'] = 1;
            $result['message'] = __('messages.system_error');
        }

        return $result;
    }

    function curlRequest($url, $method = 'post', $requestData = [] , $headers = [])
    {
        $curlHeaders = [];
        if(sizeof($headers)) {
            foreach ($headers as $h) {
                $curlHeaders[] = $h;
            }
        }

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->endpoint.'/'.$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => $curlHeaders,
            CURLOPT_VERBOSE => '1'
        ));

        switch ($method)
        {
            case 'post':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
                break;
            default:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }

        $response = curl_exec($ch);

        $err = curl_error($ch);
        $err = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
dd($response,$err, $err);
        if( $err ) {
            $result = array('error' => 1, 'message' => $err);
        } else {
            $responseArray = json_decode($response, true);
            if( is_null($responseArray) ) {
                $result = array('error' => 1, 'message' => 'Invalid response json format');
            } elseif( isset($responseArray['errors']) ) {
                $result = array('error' => 1, 'message' => is_array($responseArray['errors']) ? implode(';', $responseArray['errors']) : $responseArray['errors']);
            } else {
                $result = $responseArray;
            }
        }

        if( isset($result['error']) ) {
            Log::error('['.date('Y-m-d H:i:s').'] Facebook integration error '.PHP_EOL.'Error: '.$result['message'].PHP_EOL.'Request data: '.json_encode($requestData, JSON_UNESCAPED_UNICODE).PHP_EOL.'Response: '.$response);
        }
        return $result;
    }

}

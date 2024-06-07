<?php

namespace App\Library;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class Facebook
{

    /** @var string $endpoint */
    private string $endpoint;
    private string $pageId;
    private string $appId;
    private string $appSecret;

    private $apiVersion;
    private $userToken;
    private $userTokenLongLived;
//    private $pageToken;
    private $pageTokenLongLived;

    public function __construct()
    {
        $this->apiVersion = 'v19.0';
        $this->endpoint = 'https://graph.facebook.com';
        $this->initTokens();
    }

    public function initTokens()
    {
        $settings = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
        ->pluck('value', 'name')
        ->toArray();

        $this->appId = (int)$settings['app_id'] ?? null;
        $this->appSecret = $settings['app_secret'] ?? null;
        $this->pageId = (int)$settings['page_id'] ?? null;
        $this->userToken = $settings['user_token'] ?? null;
        $this->userTokenLongLived = $settings['user_token_long'] ?? null;
//        $this->pageToken = '';
        $this->pageTokenLongLived = $settings['page_access_token_long'] ?? null;
    }

    public function getUserLongLiveToken(): array
    {
        if(empty($this->userToken)){
            return array('error' => 1, 'message' => 'Липсва Потребител (Token)');
        }
        if(empty($this->appId)){
            return array('error' => 1, 'message' => 'Липсва Клиент (App ID)');
        }
        if(empty($this->appSecret)){
            return array('error' => 1, 'message' => 'Липсва Клиент (App Secret)');
        }

        $url = 'oauth/access_token?grant_type=fb_exchange_token&client_id='.$this->appId.'&client_secret='.$this->appSecret.'&fb_exchange_token='.$this->userToken;
        $result = $this->curlRequest($url, 'get', [], ["Content-Type: application/json"]);
        if (isset($result['error'])) {
            $result['message'] = isset($result['response']) && isset($result['response']['message']) ? $result['response']['message'] : 'Unknown error';
        } else{
            if(isset($result['response'])){
                $result = array(
                    'access_token' => $result['response']['access_token'] ?? ''
                );
            } else{
                return [
                    'error' => 1,
                    'message' => 'Facebook get User Long Live Token error: Unknown error'
                ];
            }
        }
        return $result;
    }

    public function getPageToken(): array
    {
        if(empty($this->userToken)){
            return array('error' => 1, 'message' => 'Липсва Клиент (App Secret)');
        }
        if(empty($this->pageId)){
            return array('error' => 1, 'message' => 'Липсва Страница (ID)');
        }

        $url = $this->pageId.'/?fields=name,access_token&access_token='.$this->userToken;
        $result = $this->curlRequest($url, 'get', [], ["Content-Type: application/json"]);

        if (isset($result['error'])) {
            $result['message'] = isset($result['response']) && isset($result['response']['message']) ? $result['response']['message'] : 'Unknown error';
        } else{
            if(isset($result['response'])){
                $result = array(
                    'access_token' => $result['response']['access_token'] ?? ''
                );
            } else{
                return [
                    'error' => 1,
                    'message' => 'Facebook get Page Token error: Unknown error'
                ];
            }
        }
        return $result;
    }

    public function getPageLongLiveToken(): array
    {
        if(empty($this->userTokenLongLived)){
            return array('error' => 1, 'message' => 'Липсва Потребител (Long Live Token)');
        }
        if(empty($this->pageId)){
            return array('error' => 1, 'message' => 'Липсва Страница (ID)');
        }

        $url = $this->pageId.'/?fields=name,access_token&access_token='.$this->userTokenLongLived;
        $result = $this->curlRequest($url, 'get', [], ["Content-Type: application/json"]);
        if (isset($result['error'])) {
            $result['message'] = isset($result['response']) && isset($result['response']['message']) ? $result['response']['message'] : 'Unknown error';
        } else{
            if(isset($result['response'])){
                $result = array(
                    'access_token' => $result['response']['access_token'] ?? ''
                );
            } else{
                return [
                    'error' => 1,
                    'message' => 'Facebook get Page Long Live Token error: Unknown error'
                ];
            }
        }
        return $result;
    }

    public function postOnPage(array $data): array
    {
        foreach (['message', 'link', 'published'] as $k){
            if (!isset($data[$k]) || empty($data[$k])) {
                return array('error' => 1, 'message' => 'Missing parameter: '.$k);
            }
        }

        $result = $this->curlRequest($this->pageId.'/feed?access_token='.$this->pageTokenLongLived, 'post', $data, ["Content-Type: application/json"]);

        if (isset($result['error'])) {
            $result['message'] = isset($result['response']) && isset($result['response']['message']) ? $result['response']['message'] : 'Unknown error';
        } else{
            if(isset($result['response'])){
                $result = array(
                    'id' => $result['response']['id'] ?? ''
                );
            } else{
                return [
                    'error' => 1,
                    'message' => 'Facebook Post on page error: Unknown error'
                ];
            }
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
            CURLOPT_URL => $this->endpoint.'/'.$this->apiVersion.'/'.$url,
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
        $errInfo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
//dd($response, $errInfo);
        $responseArray = json_decode($response, true);
        if( !empty($err) && $err != 200 ) {
            $result = array('error' => 1, 'response' => $responseArray);
        } else {
            if( is_null($responseArray) ) {
                $result = array('error' => 1, 'response' => ['message' => 'Invalid response json format']);
            } elseif( isset($responseArray['error']) ) {
                $result = array('error' => 1, 'response' => $responseArray);
            } else {
                $result = array('response' => $responseArray);
            }
        }

        if( isset($result['error']) ) {
            Log::error('['.date('Y-m-d H:i:s').'] Facebook integration error '.PHP_EOL.'Error: '.PHP_EOL.'Request Url: '.$this->endpoint.'/'.$this->apiVersion.'/'.$url.PHP_EOL.'Request data: '.json_encode($requestData, JSON_UNESCAPED_UNICODE).PHP_EOL.'Response: '.$response);
        }

        return $result;
    }

}

<?php

namespace App\Api\Ssev;

class EDeliveryService
{
    private $scope;
    private $endpoint;
    private $token;

    function __construct($token, $scope) {
        $this->scope = $scope;
        $this->endpoint = config('e_delivery.endpoint');
        $this->token = $token;
    }

    public function getProfileData($data)
    {
        $headers = [
            "Content-Type: application/json",
        ];
        $url = $this->endpoint.':5051/'.$this->scope.'/api/profiles/search?identifier='.$data['identity'].'&templateId=1&targetGroupId='.$data['groupId'];
        return self::curlRequest($url, [], 'GET', $headers);
    }

    public function sendMessage($data)
    {
        $headers = [
            "Content-Type: application/json",
        ];
        $url = $this->endpoint . ':5051/' . $this->scope . '/api/messages';
        return self::curlRequest($url, json_encode($data), 'POST', $headers);
    }

    /**
     * @param $data - {id (in application), name (file name), base64_encode(binary_content), content_type}
     * @param false $tmp {Temp - File will be uploaded and deleted if not used, Storage - File will be uploaded permanently}
     * @return array|bool|string
     */
    public function uploadFile($data, $tmp = false)
    {
        $eol = "\r\n";
        $postData = '--abcde12345' . $eol .
            'Content-Disposition: form-data; name="doc"; filename="' . $data['name'] . '"' . $eol .
            'Content-Type: ' . ($data['content_type'] ?? 'text/unknown') . $eol . $eol .
            base64_decode($data['binary_content']) . $eol .
            '--abcde12345--';

        $type = $tmp ? 'Temp' : 'Storage';
        $headers = [
            "Content-Type: multipart/form-data; boundary=abcde12345",
        ];
        $url = $this->endpoint . ':5051/' . $this->scope . '/upload/blobs?type=' . $type;
        return self::curlRequest($url, $postData, 'POST', $headers);
    }

    private function curlRequest($url, $requestData, $method = 'GET', $headers = [])
    {
        $curlHeaders = [
            "Accept: application/json",
            "Authorization: Bearer ".$this->token['bearer'],
            "Dp-Miscinfo: ".$this->token['miscinfo']
        ];
        if(sizeof($headers)) {
            foreach ($headers as $h) {
                $curlHeaders[] = $h;
            }
        }

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $curlHeaders,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            //certificate
            CURLOPT_SSLCERTTYPE => 'p12',
            CURLOPT_SSLCERT => config('e_delivery.client_cert'),
            CURLOPT_SSLKEYPASSWD => config('e_delivery.client_cert_key'),
        ));

        switch (strtolower($method))
        {
            case 'post':
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
                break;
            default:
        }

        $response = curl_exec($ch);
        $err = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if( $err || (int)$code != 200 ) {
            return array(
                'error' => 1,
                'message' => 'code: '.$code.' error:'.$err.' response: '.$response
            );
        }

        return $response;

    }
}

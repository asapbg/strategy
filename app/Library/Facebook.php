<?php

namespace App\Library;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Models\Consultations\PublicConsultation;
use App\Models\LegislativeInitiative;
use App\Models\OgpPlan;
use App\Models\Setting;
use App\Models\StrategicDocument;
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
        $this->apiVersion = 'v22.0';
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

    public function getUserLongLivedToken(): array
    {
        if (empty($this->userToken)) {
            return array('error' => 1, 'message' => 'Липсва Потребител (Token)');
        }
        if (empty($this->appId)) {
            return array('error' => 1, 'message' => 'Липсва Клиент (App ID)');
        }
        if (empty($this->appSecret)) {
            return array('error' => 1, 'message' => 'Липсва Клиент (App Secret)');
        }

        $url = "oauth/access_token?grant_type=fb_exchange_token&client_id=$this->appId&client_secret=$this->appSecret&fb_exchange_token=$this->userToken";
        $result = $this->curlRequest($url, 'get', [], ["Content-Type: application/json"]);
        if (isset($result['error'])) {
            $result['message'] = isset($result['response']) && isset($result['response']['message']) ? $result['response']['message'] : 'Unknown error';
        } else {
            if (isset($result['response'])) {
                $result = array(
                    'access_token' => $result['response']['access_token'] ?? ''
                );
            } else {
                return [
                    'error' => 1,
                    'message' => 'Facebook get User Long-Lived Token error: Unknown error'
                ];
            }
        }
        return $result;
    }

    public function getPageToken(): array
    {
        if (empty($this->userToken)) {
            return array('error' => 1, 'message' => 'Липсва Клиент (App Secret)');
        }
        if (empty($this->pageId)) {
            return array('error' => 1, 'message' => 'Липсва Страница (ID)');
        }

        $url = $this->pageId . '/?fields=name,access_token&access_token=' . $this->userToken;
        $result = $this->curlRequest($url, 'get', [], ["Content-Type: application/json"]);

        if (isset($result['error'])) {
            $result['message'] = isset($result['response']) && isset($result['response']['message']) ? $result['response']['message'] : 'Unknown error';
        } else {
            if (isset($result['response'])) {
                $result = array(
                    'access_token' => $result['response']['access_token'] ?? ''
                );
            } else {
                return [
                    'error' => 1,
                    'message' => 'Facebook get Page Token error: Unknown error'
                ];
            }
        }
        return $result;
    }

    public function getPageLongLivedToken(): array
    {
        if (empty($this->userTokenLongLived)) {
            return array('error' => 1, 'message' => 'Липсва Потребител (Long-Lived Token)');
        }
        if (empty($this->pageId)) {
            return array('error' => 1, 'message' => 'Липсва Страница (ID)');
        }

        $url = $this->pageId . '/?fields=name,access_token&access_token=' . $this->userTokenLongLived;
        //dd($url);
        $result = $this->curlRequest($url, 'get', [], ["Content-Type: application/json"]);
        if (isset($result['error'])) {
            $result['message'] = isset($result['response']) && isset($result['response']['message']) ? $result['response']['message'] : 'Unknown error';
        } else {
            if (isset($result['response'])) {
                $result = array(
                    'access_token' => $result['response']['access_token'] ?? ''
                );
            } else {
                return [
                    'error' => 1,
                    'message' => 'Facebook get Page Long-Lived Token error: Unknown error'
                ];
            }
        }
        return $result;
    }

    public function getMediaId()
    {
        $image_url = 'https://scontent.fsof10-1.fna.fbcdn.net/v/t39.30808-6/472558592_122096182424725207_7205765901782650598_n.png?_nc_cat=104&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=n7sM56bq-vMQ7kNvgHXkJ99&_nc_oc=Adg8ORGxcTPql4vKROdBpw2LI053IuCI5CZcLosNlBB-UQd2D1k5TajCkJm1txlzPlQ&_nc_zt=23&_nc_ht=scontent.fsof10-1.fna&_nc_gid=Aarvk9ii23NCQvvCbCSBOfv&oh=00_AYCHjqMQNLiy7aqmIDbW7qFMK-mctkUQ0CaFhduheux0vg&oe=67B3A12B';
        $result = $this->curlRequest($this->pageId . '/photos?access_token=' . $this->pageTokenLongLived, 'post', [
            'url' => $image_url,
            'published' => false
        ]);
        //$result = $this->curlRequest($this->pageId . '/photos?type=profile&access_token=' . $this->pageTokenLongLived, 'get');
        //dd($result);
        if (isset($result['response']['id'])) {
            return $result['response']['id'];
        }

        return null;
    }

    public function postOnPage(array $data): array
    {
        foreach (['message', 'link', 'published'] as $k) {
            if (!isset($data[$k]) || empty($data[$k])) {
                return array('error' => 1, 'message' => 'Missing parameter: ' . $k);
            }
        }
        $media_id = $this->getMediaId();
        //$media_id = 122096182418725207;
        if ($media_id) {
            $data['attached_media'] = [json_encode(['media_fbid' => $media_id])];
        }

        $result = $this->curlRequest($this->pageId . '/feed?access_token=' . $this->pageTokenLongLived, 'post', $data, ["Content-Type: application/json"]);

        if (isset($result['error'])) {
            $result['message'] = isset($result['response']) && isset($result['response']['message']) ? $result['response']['message'] : 'Unknown error';
        } else {
            if (isset($result['response'])) {
                $result = array(
                    'id' => $result['response']['id'] ?? ''
                );
            } else {
                return [
                    'error' => 1,
                    'message' => 'Facebook Post on page error: Unknown error'
                ];
            }
        }
        return $result;
    }

    function curlRequest($url, $method = 'post', $requestData = [], $headers = [])
    {
        $curlHeaders = [];
        if (sizeof($headers)) {
            foreach ($headers as $h) {
                $curlHeaders[] = $h;
            }
        }
        if (!strstr($url, 'oauth')) {
            //dd($this->endpoint . '/' . $this->apiVersion . '/' . $url);
        }
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->endpoint . '/' . $this->apiVersion . '/' . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => $curlHeaders,
            CURLOPT_VERBOSE => '1'
        ));

        switch ($method) {
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
        if (!empty($err) && $err != 200) {
            $result = array('error' => 1, 'response' => $responseArray);
        } else {
            if (is_null($responseArray)) {
                $result = array('error' => 1, 'response' => ['message' => 'Invalid response json format']);
            } elseif (isset($responseArray['error'])) {
                $result = array('error' => 1, 'response' => $responseArray);
            } else {
                $result = array('response' => $responseArray);
            }
        }

        if (isset($result['error'])) {
            Log::error('[' . date('Y-m-d H:i:s') . '] Facebook integration error ' . PHP_EOL . 'Error: ' . PHP_EOL . 'Request Url: ' . $this->endpoint . '/' . $this->apiVersion . '/' . $url . PHP_EOL . 'Request data: ' . json_encode($requestData, JSON_UNESCAPED_UNICODE) . PHP_EOL . 'Response: ' . $response);
        }

        return $result;
    }

    public function postToFacebook($entity)
    {
        if ($entity instanceof LegislativeInitiative) {
            $message = "На Портала за обществени консултации е направено предложение за промяна на {$entity->law?->name} и ако събере подкрепа от $entity->cap регистрирани потребители, ще бъде изпратена автоматично на компетентната институция. Срокът за коментари и подкрепа е: " . displayDate($entity->active_support) . ". Вижте повече на линка.";
            $link = route('legislative_initiatives.view', $entity->id);
        }

        if ($entity instanceof PublicConsultation) {
            $open_to = displayDate($entity->open_to);
            $message = "На Портала за обществени консултации е публикувана нова консултация: $entity->title. Срокът за коментари е: $open_to. Вижте повече тук";
            $link = route('public_consultation.view', $entity->id);
        }

        if ($entity instanceof AdvisoryBoard) {
            $chairmen = [];
            if ($entity->chairmen->count()) {
                foreach ($entity->chairmen as $c) {
                    if (!empty($c->member_name)) {
                        $chairmen[] = $c->member_name;
                    }
                }
            }
            $chairmen_text = "";
            if (count($chairmen)) {
                $chairmen_text = count($chairmen) == 1 ? ", с председател: " : ", с председатели: ";
                $chairmen_text .= implode(', ', $chairmen);
            }
            $message = "Създаден е нов консултативен съвет: $entity->name $chairmen_text. Можете да следите дейността на съвета на Портала за обществени консултации тук.";
            $link = route('advisory-boards.view', $entity->id);
        }

        if ($entity instanceof AdvisoryBoardMeeting) {
            $advBoard = $entity->advBoard;
            $date = displayDate($entity->next_meeting);
            $message = "Предстоящо заседание на $advBoard->name на $date. За повече информация тук.";
            $link = route('advisory-boards.view', $entity->id);
        }

        if ($entity instanceof StrategicDocument) {
            $message = "На Портала за обществени консултации е публикуван нов стратегически документ: $entity->title. Запознайте се с документа тук.";
            $link = route('strategy-document.view', $entity->id);
        }

        if ($entity instanceof OgpPlan) {
            $message = "На Портала за обществени консултации беше финализиран национален план: $entity->name. Вижте повече на линка.";
            $link = route('ogp.national_action_plans.show', $entity->id);
        }

        $this->postOnPage(['message' => $message, 'link' => $link, 'published' => true]);
    }

}

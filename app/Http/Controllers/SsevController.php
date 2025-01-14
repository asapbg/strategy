<?php

namespace App\Http\Controllers;

use App\Api\Ssev\EDeliveryAuth;
use App\Api\Ssev\EDeliveryService;
use Illuminate\Support\Facades\Log;

class SsevController extends Controller
{
    public static function getSsevProfile($user, $identityNumber = ''): int
    {
        $eDeliveryConfig = config('e_delivery');
        if (!$user->ssev_profile_id) {
            $eDeliveryAuth = new EDeliveryAuth('/ed2*');
            $token = $eDeliveryAuth->getToken();

            if (is_null($token)) {
                return 0;
            }

            $eDeliveryService = new EDeliveryService($token, 'ed2');

            $identity = [];
            if (!empty($user->eik)) {
                $identity[] = [
                    'groupId' => $eDeliveryConfig['group_ids']['company'],
                    'identity' => !empty($identityNumber) ? $identityNumber : $user->eik,
                ];
            } else if (!empty($user->identity)) {
                $identity[] = [
                    'groupId' => $eDeliveryConfig['group_ids']['person'],
                    'identity' => !empty($identityNumber) ? $identityNumber : $user->identity,
                ];
            }

            if (!sizeof($identity)) {
                $user->ssev_profile_id = null;
                $user->save();
                return 0;
            }

            $recipientProfile = 0;
            foreach ($identity as $identityType) {
                if ($recipientProfile) {
                    continue;
                }
                $profileResponse = $eDeliveryService->getProfileData([
                    'groupId' => $identityType['groupId'],
                    'identity' => $identityType['identity'],
                ]);

                if (is_array($profileResponse) && isset($profileResponse['error'])) {
                    Log::error('Get SSEV (eDelivery) profile info request error: ' . $profileResponse['message']);
                    continue;
                }
                $profile = json_decode($profileResponse, true);
                if (!$profile || !isset($profile['profileId'])) {
                    Log::error('Get SSEV (eDelivery) profile info response error: ' . $profileResponse);
                    continue;
                }
                $recipientProfile = $profile['profileId'];
            }

            if ($recipientProfile) {
                $user->ssev_profile_id = $recipientProfile;
                $user->save();
            } else {
                $user->ssev_profile_id = null;
                $user->save();
            }
        }

        return (int)$user->ssev_profile_id;
    }

    public static function getInstitutionSsevProfileId($institution): int
    {
        $eDeliveryConfig = config('e_delivery');
        if (!$institution->ssev_profile_id) {
            $eDeliveryAuth = new EDeliveryAuth('/ed2*');
            $token = $eDeliveryAuth->getToken();

            if (is_null($token)) {
                return 0;
            }

            $eDeliveryService = new EDeliveryService($token, 'ed2');

            $identity = [];
            if (!empty($institution->eik)) {
                $identity[] = [
                    'groupId' => $institution->eik == '175370880' ? $eDeliveryConfig['group_ids']['company'] : $eDeliveryConfig['group_ids']['egov'],
                    'identity' => $institution->eik,
                ];
            }

            if (config('app.env') != 'production') {
                $identity[0]['identity'] = '0006950250001';
            }

            if (!sizeof($identity)) {
                $institution->ssev_profile_id = null;
                $institution->save();
                return 0;
            }

            $recipientProfile = 0;
            foreach ($identity as $identityType) {
                if ($recipientProfile) {
                    continue;
                }
                $profileResponse = $eDeliveryService->getProfileData([
                    'groupId' => $identityType['groupId'],
                    'identity' => $identityType['identity'],
                ]);

                if (is_array($profileResponse) && isset($profileResponse['error'])) {
                    Log::error('Get SSEV (eDelivery) profile info request error: ' . $profileResponse['message']);
                    continue;
                }
                $profile = json_decode($profileResponse, true);
                if (!$profile || !isset($profile['profileId'])) {
                    Log::error('Get SSEV (eDelivery) profile info response error: ' . $profileResponse);
                    continue;
                }
                $recipientProfile = $profile['profileId'];
            }

            if ($recipientProfile) {
                $institution->ssev_profile_id = $recipientProfile;
                $institution->save();
            } else {
                $institution->ssev_profile_id = null;
                $institution->save();
            }
        }

        return (int)$institution->ssev_profile_id;
    }
}

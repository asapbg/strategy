<?php

namespace App\Console\Commands;

use App\Models\EkatteArea;
use App\Models\EkatteMunicipality;
use App\Models\EkatteSettlement;
use App\Models\InstitutionLevel;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncIisda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:iisda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Institutions';

    /**
     * Execute the console command.
     *
     * @return int
     */

    private array $typesToSync = []; //'AdmStructure' //Use to filter which institution to sync
    private array $typesWithAddress = ['AdmStructure']; //Use to filter which institution address we need to get
    private int $getAddressAtOnes = 10;

    public function handle()
    {
        Log::info("Cron run sync:iisda.");

        $localSubjects = $toInsert = $idArrayToDeactivate = [];
        //Local institutions
        $dbInstitution = Institution::select('institution.id'
                , 'institution.institution_level_id'
                , 'institution.batch_id'
                , 'institution.nomer_register'
                ,'institution.eik'
                , 'institution.active'
                , 'institution_translations.name'
                , 'institution_translations.address'
                , DB::raw('coalesce(institution_level.system_name, \'\') as section')
                , 'institution.email'
                , 'institution.phone'
                , 'institution.fax'
                , 'institution.region'
                , 'institution.municipality'
                , 'institution.town'
                , 'institution.zip_code'
                , 'institution.type'
            )
            ->leftJoin('institution_level', 'institution_level.id', '=', 'institution.institution_level_id')
            ->leftJoin('institution_translations', function ($join){
                $join->on('institution_translations.institution_id', '=', 'institution.id')
                    ->where('institution_translations.locale', '=', 'bg');
            })->when(sizeof($this->typesToSync), function ($query) {
                return $query->where('institution.type', '=', $this->typesToSync);
            })
            ->where('institution.adm_register', '=', 1)
            ->get();

        if( $dbInstitution->count() ) {
            foreach ($dbInstitution as $row) {
                $localSubjects[$row->nomer_register] = $row;
            }
        }
        //Local sections
        $localSections = InstitutionLevel::get()->pluck('id', 'system_name')->toArray();

        //Get list and base info
        $dataSoap= $this->dataSoapSearchBatchesIdentificationInfo();
        $responseArray = $this->getSoap($dataSoap);
        if( $responseArray ) {
            if( isset($responseArray['error']) && $responseArray['error'] ) {
                return Command::FAILURE;
            }
            //sHeader //sBody->SearchBatchesIdentificationInfoResponse->SearchBatchesIdentificationInfoResult->BatchIdentificationInfoType
            if(isset($responseArray['sBody']) && isset($responseArray['sBody']['SearchBatchesIdentificationInfoResponse'])
                && isset($responseArray['sBody']['SearchBatchesIdentificationInfoResponse']['SearchBatchesIdentificationInfoResult'])
                && isset($responseArray['sBody']['SearchBatchesIdentificationInfoResponse']['SearchBatchesIdentificationInfoResult']['BatchIdentificationInfoType'])) {
                $items = $responseArray['sBody']['SearchBatchesIdentificationInfoResponse']['SearchBatchesIdentificationInfoResult']['BatchIdentificationInfoType'];

                if( !sizeof($items) ) {
                    return Command::FAILURE;
                }

                //Get address info
                $responseArrayAddress = $this->getAddressInfo($items);
                DB::beginTransaction();
                try {
                    $updatedCnt = 0;//count how many subject are updated
                    foreach ($items as $row) {
                        if( isset($row['@attributes']) ) {
                            $subject = $row['@attributes'];
//                            dd($subject['IdentificationNumber'], $responseArrayAddress[$subject['IdentificationNumber']]);
                            $addressInfo = $responseArrayAddress[$subject['IdentificationNumber']] ?? null;
                            //add structure if not exist
                            if(!isset($localSections[$subject['AdmStructureKind']])) {
                                $newInstLevel = InstitutionLevel::create([
                                    'system_name' => $subject['AdmStructureKind']
                                ]);
                                if( !$newInstLevel ) {
                                    Log::error('Sync Institution: Missing AdmStructureKind:'. $subject['AdmStructureKind']);
                                    continue;
                                }
                                foreach (config('available_languages') as $lang) {
                                    $newInstLevel->translateOrNew($lang['code'])->name = $subject['AdmStructureKind'];
                                }
                                $newInstLevel->save();
                                $localSections[$subject['AdmStructureKind']] = $newInstLevel->id;
                            }

                            //if exist in local db check if need update
                            //remove from local subjects array, it means that we found it in sync array.
                            // At the end we will deactivate all items not removed from local subject array
                            if( isset($localSubjects[$subject['IdentificationNumber']]) ) {
                                $updated = false;
                                $localSubject = $localSubjects[$subject['IdentificationNumber']];

                                //update subject base info if need to
                                if( (int)$localSubject->batch_id != (int)$subject['BatchID']
                                    || $localSubject->section != $subject['AdmStructureKind']
                                    || $localSubject->eik != ($subject['UIC'] ?? 'N/A')
                                    || $localSubject->type != ($subject['Type'] ?? null)
                                    || ((int)$localSubject->active != (int)($subject['Status'] == 'Active'))
                                    || ( $addressInfo && (
                                        $addressInfo['email'] != $localSubject->email
                                        || $addressInfo['phone'] != $localSubject->phone
                                        || $addressInfo['fax'] != $localSubject->fax
                                        || $addressInfo['zip_code'] != $localSubject->zip_code
                                        || (int)$addressInfo['region'] != (int)$localSubject->region
                                        || (int)$addressInfo['municipality'] != (int)$localSubject->municipality
                                        || (int)$addressInfo['town'] != (int)$localSubject->town
                                        )
                                    )
                                ) {
                                    //alert users if change adm_level or status
//                                    $newLevel = $localSections[$subject['AdmStructureKind']];
//                                    $newStatus = (int)($subject['Status'] == 'Active');
//                                    if( $localSubject->adm_level != $newLevel
//                                        || $localSubject->active != $newStatus ) {
//                                        if( env('APP_ENV') != 'production' ) {
//                                            $emailList =[env('LOCAL_TO_MAIL')];
//                                        } else {
//                                            $emailList = $localSubject->getAlertUsersEmail();
//                                        }
//                                        if( sizeof($emailList) ) {
//                                            $mailData = array(
//                                                'subject' => $localSubject
//                                            );
//                                            if( $localSubject->adm_level != $newLevel ) {
//                                                $mailData['new_level'] = $newLevel;
//                                            }
//                                            if( $localSubject->active != $newStatus ) {
//                                                $mailData['new_status'] = $newStatus;
//                                            }
//                                            Mail::to($emailList)->send(new AlertForSubjectChanges($mailData));
//                                        }
//                                    }

//                                    Log::error('Update base: '.PHP_EOL. $localSubject. PHP_EOL. json_encode($addressInfo));
                                    $localSubject->batch_id = (int)$subject['BatchID'];
                                    $localSubject->eik = $subject['UIC'] ?? 'N/A';
                                    $localSubject->type = $subject['Type'] ?? null;
                                    $localSubject->adm_level = $localSections[$subject['AdmStructureKind']];
                                    $localSubject->active = (int)($subject['Status'] == 'Active');
                                    $localSubject->email = $addressInfo ? $addressInfo['email'] : null;
                                    $localSubject->phone = $addressInfo ? $addressInfo['phone'] : null;
                                    $localSubject->fax = $addressInfo ? $addressInfo['fax'] : null;
                                    $localSubject->zip_code = $addressInfo ? $addressInfo['zip_code'] : null;
                                    $localSubject->region = $addressInfo ? $addressInfo['region'] : null;
                                    $localSubject->municipality = $addressInfo ? $addressInfo['municipality'] : null;
                                    $localSubject->town = $addressInfo ? $addressInfo['town'] : null;
                                    $localSubject->save();
                                    $updated = true;
                                }
                                //update subject translation fields if need to
                                $translationUpdate = false;
                                if( $localSubject->subject_name != $subject['Name'] ) {
//                                    Log::error('Update name: '.PHP_EOL. $localSubject->subject_name. PHP_EOL. $subject['Name']);
                                    foreach (config('available_languages') as $lang) {
                                        $localSubject->translateOrNew($lang['code'])->name = $subject['Name'];
                                    }
                                    //$localSubject->translateOrNew('bg')->name = $subject['Name'];
                                    $translationUpdate = true;
                                    $updated = true;
                                }
                                if( $addressInfo && ($localSubject->address != $addressInfo['address']) ) {
//                                    Log::error('Update address: '.PHP_EOL. $localSubject->address. PHP_EOL. $addressInfo['address']);
                                    foreach (config('available_languages') as $lang) {
                                        $localSubject->translateOrNew($lang['code'])->address = $addressInfo['address'];
                                    }
                                    $translationUpdate = true;
                                    $updated = true;
                                }
                                if( $translationUpdate ) {
                                    $localSubject->save();
                                }
                                unset($localSubjects[$subject['IdentificationNumber']]);
                                if($updated) { $updatedCnt +=1; }
                            } else {
                                $toInsert[] = array(
                                    'batch_id' => $subject['BatchID'],
                                    'eik' => $subject['UIC'] ?? 'N/A',
                                    'type' => $subject['Type'] ?? null,
                                    'nomer_register' => $subject['IdentificationNumber'],
                                    'active' => $subject['IdentificationNumber'] === 'Active',
                                    'institution_level_id' => $localSections[$subject['AdmStructureKind']] ?? 0,
                                    'name' => $subject['Name'],
                                    'adm_register' => 1,
                                    'email' => $addressInfo ? $addressInfo['email'] : null,
                                    'phone' => $addressInfo ? $addressInfo['phone'] : null,
                                    'fax' => $addressInfo ? $addressInfo['fax'] : null,
                                    'zip_code' => $addressInfo ? $addressInfo['zip_code'] : null,
                                    'address' => $addressInfo ? $addressInfo['address'] : null,
                                    'region' => $addressInfo ? $addressInfo['region'] : null,
                                    'municipality' => $addressInfo ? $addressInfo['municipality'] : null,
                                    'town' => $addressInfo ? $addressInfo['town'] : null,
                                );
                            }
                        }
                    }

                    if( sizeof($toInsert) ) {
                        foreach ($toInsert as $newRow) {
                            $address = $newRow['address'];
                            unset($newRow['address']);
                            $newSubject = new Institution($newRow);
                            $newSubject->save();
                            $newSubject->refresh();
                            foreach (config('available_languages') as $lang) {
                                $newSubject->translateOrNew($lang['code'])->name = $newRow['name'];
                                $newSubject->translateOrNew($lang['code'])->address = $address;
                            }
                            $newSubject->save();
                        }
                    }

                    //deactivate local subject because we did\'t find them in sync array
                    if( sizeof($localSubjects) ) {
                        foreach ($localSubjects as $p) {
                            $idArrayToDeactivate[] = $p->id;
                        }
                        Institution::whereIn('id', $idArrayToDeactivate)->update(['active' => 0]);

                    }

                    echo 'Inserted: '.sizeof($toInsert);
                    echo 'Deactivated: '.sizeof($idArrayToDeactivate);
                    echo 'Updated: '.$updatedCnt;
                    DB::commit();
                    return Command::SUCCESS;

                } catch (\Exception $e){
                    DB::rollBack();
                    Log::error('Sync Institution error: '.$e->getMessage());
                    return Command::FAILURE;
                }

            } else {
                Log::error('Sync Institution: Response array structure missing'. $responseArray);
                return Command::FAILURE;
            }
        } else {
            Log::error('Sync Institution: Unable to parse soap xml response');
            return Command::FAILURE;
        }

    }

    private function getAddressInfo($items): array
    {
        $response = [];
        if (sizeof($items) ) {
            $areas = EkatteArea::select('id', 'oblast')->get()->pluck('id', 'oblast')->toArray();
            $municipalities = EkatteMunicipality::select('id', 'obstina')->get()->pluck('id', 'obstina')->toArray();
            $settlements = EkatteSettlement::select('id', 'ekatte')->get()->pluck('id', 'ekatte')->toArray();

            $chunks = array_chunk($items, $this->getAddressAtOnes);
            if( sizeof($chunks) ) {
                foreach ($chunks as $chunk) {
                    $dataSoap = $this->dataSoapGetBatchDetailedInfo($chunk);
                    if(empty($dataSoap)) {continue;}

                    $responseArray = $this->getSoap($dataSoap);

                    if( isset($responseArray['error']) && $responseArray['error'] ) {
                        continue;
                    }
                    if(isset($responseArray['sBody']) && isset($responseArray['sBody']['GetBatchDetailedInfoResponse'])
                        && isset($responseArray['sBody']['GetBatchDetailedInfoResponse']['GetBatchDetailedInfoResult'])
                        && isset($responseArray['sBody']['GetBatchDetailedInfoResponse']['GetBatchDetailedInfoResult']['BatchType'])) {

                        $soapItems = $responseArray['sBody']['GetBatchDetailedInfoResponse']['GetBatchDetailedInfoResult']['BatchType'];
                        if( sizeof($soapItems) ) {
                            foreach ($soapItems as $item) {
                                if( isset($item['@attributes']) && isset($item['@attributes']['IdentificationNumber'])) {
//                                    if(!isset($item['@attributes']['IdentificationNumber'])) {
//                                        dd($item, $item['@attributes']);
//                                    }
                                    $responseKey = $item['@attributes']['IdentificationNumber'];
                                    $response[$responseKey] = [
                                        'email' => null,
                                        'phone' => null,
                                        'fax' => null,
                                        'zip_code' => null,
                                        'address' => null,
                                        'region' => null,
                                        'municipality' => null,
                                        'town' => null,
                                    ];

                                    if( isset($item['Administration']) ) {
                                        if( isset($item['Administration']['CorrespondenceData']) && isset($item['Administration']['CorrespondenceData']['@attributes']) ) {
                                            $correspondenceData = $item['Administration']['CorrespondenceData']['@attributes'];

                                            //email
                                            if( isset($correspondenceData['Email']) ) {
                                                $response[$responseKey]['email'] = $correspondenceData['Email'];
                                            }
                                            //phone code
                                            $phoneCode = isset($correspondenceData['InterSettlementCallingCode']) && !empty(trim($correspondenceData['InterSettlementCallingCode'])) ?
                                                '('.$correspondenceData['InterSettlementCallingCode'].')' : '';
                                            //fax
                                            if( isset($correspondenceData['FaxNumber']) ) {
                                                $response[$responseKey]['fax'] = $phoneCode.$correspondenceData['FaxNumber'];
                                            }
                                        }
                                        //phone
                                        if( isset($item['Administration']['CorrespondenceData']) && isset($item['Administration']['CorrespondenceData']['Phone']) ) {
                                            $phonesData = $item['Administration']['CorrespondenceData']['Phone'];
                                            foreach ($phonesData as $phone) {
                                                if( isset($phone['PhoneNumber']) ) {
                                                    $response[$responseKey]['phone'] .= ($phoneCode ?? '').$phone['PhoneNumber'].';';
                                                }
                                            }
                                        }
                                        //address
                                        if( isset($item['Administration']['Address']) && isset($item['Administration']['Address']['@attributes']) ) {
                                            $addressPart1 = $item['Administration']['Address']['@attributes'];
                                            if( isset($addressPart1['PostCode']) ) {
                                                $response[$responseKey]['zip_code'] = $addressPart1['PostCode'];
                                            }
                                            if( isset($addressPart1['AddressText']) ) {
                                                $response[$responseKey]['address'] = str_replace('"', '', $addressPart1['AddressText']);
                                            }
                                        }
                                        //ekatte
//                                        dd($item['Administration']['Address']['EkatteAddress']['@attributes']);
                                        if( isset($item['Administration']['Address']) && isset($item['Administration']['Address']['EkatteAddress'])
                                            && isset($item['Administration']['Address']['EkatteAddress']['@attributes']) ) {
                                            $addressPart2 = $item['Administration']['Address']['EkatteAddress']['@attributes'];
//                                            dd($response[$responseKey],$areas[$addressPart2['DistrictEkatteCode']], $areas[$addressPart2['DistrictEkatteCode']]);
                                            if( isset($addressPart2['DistrictEkatteCode']) ) {
                                                $response[$responseKey]['region'] = $areas[$addressPart2['DistrictEkatteCode']] ?? null;
                                            }
                                            if( isset($addressPart2['MunicipalityEkatteCode']) ) {
                                                $response[$responseKey]['municipality'] = $municipalities[$addressPart2['MunicipalityEkatteCode']] ?? null;
                                            }
                                            if( isset($addressPart2['SettlementEkatteCode']) ) {
                                                $response[$responseKey]['town'] = $settlements[$addressPart2['SettlementEkatteCode']] ?? null;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }

    private function dataSoapSearchBatchesIdentificationInfo(): string
    {
        $types = '';
        if( sizeof($this->typesToSync) ) {
            foreach ($this->typesToSync as $type) {
                $types .= '<int:batchType>'.$type.'</int:batchType>';
            }
        }
        return '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:int="http://iisda.government.bg/RAS/IntegrationServices"><soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://iisda.government.bg/RAS/IntegrationServices/IBatchInfoService/SearchBatchesIdentificationInfo</wsa:Action><wsa:To>https://iisda.government.bg/Services/RAS/RAS.Integration.Host/BatchInfoService.svc</wsa:To></soap:Header>
   <soap:Body>
      <int:SearchBatchesIdentificationInfo>
         <int:status>Active</int:status>
         '.$types.'
      </int:SearchBatchesIdentificationInfo>
   </soap:Body>
</soap:Envelope>';
    }

    private function dataSoapGetBatchDetailedInfo($items): string
    {
        $dataSoap = $itemsToSearch = '';
        foreach ($items as $row) {
            if (isset($row['@attributes']) && isset($row['@attributes']['Type'])
                && isset($row['@attributes']['IdentificationNumber']) && in_array($row['@attributes']['Type'], $this->typesWithAddress) ) {
                $itemsToSearch .= '<int:string>'. $row['@attributes']['IdentificationNumber'] .'</int:string>';
            }
        }

        if( !empty($itemsToSearch) ) {
            $dataSoap = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:int="http://iisda.government.bg/RAS/IntegrationServices"><soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://iisda.government.bg/RAS/IntegrationServices/IBatchInfoService/GetBatchDetailedInfo</wsa:Action><wsa:To>https://iisda.government.bg/Services/RAS/RAS.Integration.Host/BatchInfoService.svc</wsa:To></soap:Header>
                       <soap:Body>
                          <int:GetBatchDetailedInfo>
                             <int:batchIdentificationNumber>'. $itemsToSearch .'</int:batchIdentificationNumber>
                  </int:GetBatchDetailedInfo>
               </soap:Body>
            </soap:Envelope>';
        }

        return $dataSoap;
    }

    private function getSoap($data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://iisda.government.bg/Services/RAS/RAS.Integration.Host/BatchInfoService.svc');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/soap+xml',
            'Accept: application/soap+xml',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
            Log::error('Sync Institution error (curl):'.PHP_EOL.'Data soap: '.$data.PHP_EOL. 'Error: '.$err);
            return ['error' => 1];
        } else{
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
            $xml = simplexml_load_string($response);
            $json = json_encode($xml);

            return json_decode($json, true);
        }
    }
}

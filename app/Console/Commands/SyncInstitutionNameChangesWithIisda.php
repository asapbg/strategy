<?php

namespace App\Console\Commands;

use App\Models\InstitutionHistoryName;
use App\Models\InstitutionLevel;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncInstitutionNameChangesWithIisda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:institution-name-changes {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync changes in Institutions names through the years back to 2016';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info("Command sync:institution-name-changes run at ". date("Y-m-d H:i:s"));
        activity()->disableLogging();

        $settings = Setting::Editable()->orderBy('id')->where('section', '=', 'sync')->first();
        $settings->custom_value = null;
        $settings->value = json_encode([]);
        $settings->save();

        $date = $this->argument('date');

        $institutions = Institution::select('institution.id', 'eik', 'batch_id',
            DB::raw('(select name from institution_history_names as names where names.institution_id = institution.id order by valid_from desc limit 1) as h_name')
        )
            ->without('translations')
            ->where('eik', '<>', 'N/A')
            ->orderBy('institution.id')
            //->where('names.valid_from', '<=', '2015-05-15')
            ->whereIn('institution.id', [98, 133])
            //->whereNotIn('institution.id', [143,137])
            //->skip(113)
            //->take(100)
            ->get();
        //dd($institutions->toArray());

        if (!$date) {
            $date = '2023-05-15';
        }
        $date_from = new \DateTime($date);
        $end = new \DateTime(now());
        $interval = new \DateInterval('P1M');
        $period = new \DatePeriod($date_from, $interval, $end);

        Log::info("Start date: $date");

        $changes = [];
        foreach ($institutions as $institution) {

//            if (InstitutionHistoryName::where('institution_id', $institution->id)->exists()) {
//                continue;
//            }
            $current_name = $institution->h_name;
            $currentName = null;

            foreach ($period as $dt) {
                $dateAt = $dt->format("Y-m-d");

                //$this->info("Проверка за $dateAt на $current_name");
                $dataSoap = $this->searchBatchVersions($institution->batch_id, $dateAt);

                $responseArray = $this->getSoapResponse($dataSoap);

                if (!$responseArray) {
                    Log::error('Sync Institution: Unable to parse soap xml response');
                    return Command::FAILURE;
                }
                if (isset($responseArray['error']) && $responseArray['error']) {
                    return Command::FAILURE;
                }
                if (!isset($responseArray['sBody']['GetBatchDetailedInfoResponse']['GetBatchDetailedInfoResult']['BatchType']['@attributes'])) {
                    continue;
                }

                $institutionIisda = $responseArray['sBody']['GetBatchDetailedInfoResponse']['GetBatchDetailedInfoResult']['BatchType']['@attributes'];
                if ($current_name != $institutionIisda['Name']) {

                    $valid_from = $dt->format("Y-m-d");
                    $changes[] = "Името на $current_name е променено на {$institutionIisda['Name']} на $valid_from";
                    //dump("Запис валиден от $valid_from на $current_name != {$institutionIisda['Name']}");

                    if ($currentName) {
                        $currentName->valid_till = $valid_from;
                        $currentName->current = false;
                        $currentName->save();
                    }
                    $currentName = $institution->historyNames()->create([
                        'name' => $institutionIisda['Name'],
                        'valid_from' => $valid_from
                    ]);

                    $current_name = $institutionIisda['Name'];
                }
            }
            sleep(5);
        }

        $settings->custom_value = date("Y-m-d");
        $settings->value = json_encode($changes);
        $settings->save();

        return Command::SUCCESS;

        /**
         * This commented part was used once to get the names backwards from date 2010-04-15 back to 2001-01-01
         */
//        $dataSoap = $this->searchBatchVersions('1091', '2013-04-15');
//        $responseArray = $this->getSoapResponse($dataSoap);
//        if (isset($responseArray['sBody']['GetBatchDetailedInfoResponse']['GetBatchDetailedInfoResult']['BatchType'])) {
//            dd($responseArray['sBody']['GetBatchDetailedInfoResponse']['GetBatchDetailedInfoResult']['BatchType']['@attributes']['Name']);
//        }
//        dd('ok');

//        foreach ($institutions as $institution) {
//
//            $date_from = new \DateTime('2010-04-15');
//            $backward_months = 185;
//
//            $current_name = $institution->h_name;
//
//            for ($i = 0; $i < $backward_months; $i++) {
//                $dateAt = $date_from->format("Y-m-d");
//
//                $dataSoap = $this->searchBatchVersions($institution->batch_id, $dateAt);
//                $responseArray = $this->getSoapResponse($dataSoap);
//
//                // Subtract one month
//                $date_from->modify('-1 month');
//
//                if (!$responseArray) {
//                    Log::error('Sync Institution: Unable to parse soap xml response');
//                    return Command::FAILURE;
//                }
//                if (isset($responseArray['error']) && $responseArray['error']) {
//                    return Command::FAILURE;
//                }
//                if (!isset($responseArray['sBody']['GetBatchDetailedInfoResponse']['GetBatchDetailedInfoResult']['BatchType']['@attributes'])) {
//                    dump("No results backwards for $current_name");
//                    break;
//                }
//
//                $institutionIisda = $responseArray['sBody']['GetBatchDetailedInfoResponse']['GetBatchDetailedInfoResult']['BatchType']['@attributes'];
//                if ($current_name != $institutionIisda['Name']) {
//                    dump("Запис валиден до $dateAt на $current_name != {$institutionIisda['Name']}");
//
//                    $current_name = $institutionIisda['Name'];
//
//                    if (!isset($currentName)) {
//                        $currentName = InstitutionHistoryName::find($institution->history_id);
//                    }
//                    $currentName->valid_from = $dateAt;
//                    $currentName->save();
//
//                    $currentName = $institution->historyNames()->create([
//                        'name' => $current_name,
//                        'current' => false,
//                        'valid_from' => '2000-01-01',
//                        'valid_till' => $dateAt
//                    ]);
//
//                }
//            }
//            sleep(10);
//        }
//
//        return Command::SUCCESS;
    }

    /**
     * @param $batchUIC
     * @param $dateAt
     * @return string
     */
    private function dataSoapSearchBatchesIdentificationInfo($batchUIC = null, $dateAt = null): string
    {
        //000695388 - Министерство на транспорта и съобщенията
        if ($batchUIC) {
            $batchUIC = "<int:batchUIC>$batchUIC</int:batchUIC>";
        }
        if ($dateAt) {
            $dateAt = "<int:dateAt>$dateAt</int:dateAt>";
        }
        return '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:int="http://iisda.government.bg/RAS/IntegrationServices"><soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://iisda.government.bg/RAS/IntegrationServices/IBatchInfoService/SearchBatchesIdentificationInfo</wsa:Action><wsa:To>https://iisda.government.bg/Services/RAS/RAS.Integration.Host/BatchInfoService.svc</wsa:To></soap:Header>
   <soap:Body>
      <int:SearchBatchesIdentificationInfo>
         <int:status>Active</int:status>
         ' . $batchUIC . $dateAt. '
      </int:SearchBatchesIdentificationInfo>
   </soap:Body>
</soap:Envelope>';
    }

    /**
     * @param $batchIdentificationNumber
     * @param $dateAt
     * @param $from_date
     * @param $to_date
     * @return string
     */
    private function searchBatchVersions($batchIdentificationNumber, $dateAt = null, $from_date = null, $to_date = null): string
    {
        //0000000086 - Министерство на транспорта и съобщенията
        $batchIdentificationNumber = "<int:batchIdentificationNumber><int:string>$batchIdentificationNumber</int:string></int:batchIdentificationNumber>";
        if ($dateAt) {
            $dateAt = "<int:dateAt>$dateAt</int:dateAt>";
        }
        if ($from_date) {
            //$dateTime = date("c", strtotime("2024-01-01 00:00:00"));
            $from_date = "<int:fromDate>$from_date</int:fromDate>";
        }
        if ($to_date) {
            //$dateTime = date("c", strtotime("2024-01-01 00:00:00"));
            $to_date = "<int:toDate>$to_date</int:toDate>";
        }
        return '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:int="http://iisda.government.bg/RAS/IntegrationServices">
<soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
<wsa:Action>http://iisda.government.bg/RAS/IntegrationServices/IBatchInfoService/GetBatchDetailedInfo</wsa:Action>
<wsa:To>https://iisda.government.bg/Services/RAS/RAS.Integration.Host/BatchInfoService.svc</wsa:To>
</soap:Header>
   <soap:Body>
      <int:GetBatchDetailedInfo>
         ' . $batchIdentificationNumber . $dateAt . $from_date . $to_date . '
      </int:GetBatchDetailedInfo>
   </soap:Body>
</soap:Envelope>';
    }

    /**
     * @param $data
     * @return int[]|mixed
     */
    private function getSoapResponse($data)
    {
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
            Log::error('Sync Institution error (curl):' . PHP_EOL . 'Data soap: ' . $data . PHP_EOL . 'Error: ' . $err);
            return ['error' => 1];
        } else {
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
            //file_put_contents('iisda.xml', $response);
            $xml = simplexml_load_string($response);
            $json = json_encode($xml);

            return json_decode($json, true);
        }
    }
}

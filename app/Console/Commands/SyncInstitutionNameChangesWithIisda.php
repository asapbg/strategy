<?php

namespace App\Console\Commands;

use App\Models\InstitutionHistoryName;
use App\Models\InstitutionLevel;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncInstitutionNameChangesWithIisda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:institution-name-changes';

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
        Log::info("Cron run sync:iisda.");
        activity()->disableLogging();

        $level_ministry_id = InstitutionLevel::where('system_name', '=', 'Ministry')->first()->id;
        $institutions = Institution::select('id', 'eik')
            ->without('translations')
            ->where('institution_level_id', '<>', $level_ministry_id)
            ->where('eik', '<>', 'N/A')
            ->orderBy('id')
            ->skip(200)
            ->take(100)
            ->get();
        //dd($institutions);

        $date_from = new \DateTime('2015-05-15');
        $end = new \DateTime(date('Y-m-d'));
        $interval = \DateInterval::createFromDateString('1 month');
        $period = new \DatePeriod($date_from, $interval, $end);

        foreach ($institutions as $institution) {

            if (InstitutionHistoryName::where('institution_id', $institution->id)->exists()) {
                continue;
            }
            $current_name = "";
            $currentName = null;

            foreach ($period as $dt) {
                $dateAt = $dt->format("c");

                $dataSoap = $this->dataSoapSearchBatchesIdentificationInfo($institution->eik, $dateAt);

                $responseArray = $this->getSoapResponse($dataSoap);

                if (!$responseArray) {
                    Log::error('Sync Institution: Unable to parse soap xml response');
                    return Command::FAILURE;
                }
                if (isset($responseArray['error']) && $responseArray['error']) {
                    return Command::FAILURE;
                }
                if (!isset($responseArray['sBody']['SearchBatchesIdentificationInfoResponse']['SearchBatchesIdentificationInfoResult']['BatchIdentificationInfoType']['@attributes'])) {
                    continue;
                }

                $institutionIisda = $responseArray['sBody']['SearchBatchesIdentificationInfoResponse']['SearchBatchesIdentificationInfoResult']['BatchIdentificationInfoType']['@attributes'];
                if ($current_name != $institutionIisda['Name']) {

                    $valid_from = $dt->format("Y-m-d");
                    dump("Запис валиден от $valid_from на $current_name != {$institutionIisda['Name']}");

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
            sleep(15);
        }

        return Command::SUCCESS;
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
     * @param $from_date
     * @param $to_date
     * @return string
     */
    private function searchBatchVersions($batchIdentificationNumber, $from_date = null, $to_date = null): string
    {
        //0000000086 - Министерство на транспорта и съобщенията
        $batchIdentificationNumber = "<int:batchIdentificationNumber>$batchIdentificationNumber</int:batchIdentificationNumber>";
        if ($from_date) {
            //$dateTime = date("c", strtotime("2024-01-01 00:00:00"));
            $from_date = "<int:fromDate>$from_date</int:fromDate>";
        }
        if ($to_date) {
            //$dateTime = date("c", strtotime("2024-01-01 00:00:00"));
            $to_date = "<int:toDate>$to_date</int:toDate>";
        }
        return '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:int="http://iisda.government.bg/RAS/IntegrationServices"><soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://iisda.government.bg/RAS/IntegrationServices/IBatchInfoService/SearchBatchesIdentificationInfo</wsa:Action><wsa:To>https://iisda.government.bg/Services/RAS/RAS.Integration.Host/BatchInfoService.svc</wsa:To></soap:Header>
   <soap:Body>
      <int:SearchBatchVersions>
         ' . $batchIdentificationNumber . $from_date . $to_date . '
      </int:SearchBatchVersions>
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
            $xml = simplexml_load_string($response);
            $json = json_encode($xml);

            return json_decode($json, true);
        }
    }
}

<?php
namespace Database\Seeders;

use App\Models\StrategicDocuments\Institution;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locales = config('available_languages');
        $entries = $this->fetchEntries();

        foreach ($entries as $entry) {
            $item = new Institution();
            $item->save();
            if ($item->id) {
                foreach ($locales as $locale) {
                    $item->translateOrNew($locale['code'])->name = $entry['@attributes']['Name'];
                }
            }
            $item->save();
        }
    }

    private function fetchEntries() {
        $data = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:int="http://iisda.government.bg/RAS/IntegrationServices"><soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://iisda.government.bg/RAS/IntegrationServices/IBatchInfoService/SearchBatchesIdentificationInfo</wsa:Action><wsa:To>https://iisda.government.bg/Services/RAS/RAS.Integration.Host/BatchInfoService.svc</wsa:To></soap:Header>
        <soap:Body>
            <int:SearchBatchesIdentificationInfo>
                <int:status>Active</int:status>
            </int:SearchBatchesIdentificationInfo>
        </soap:Body>
        </soap:Envelope>';
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

        $response = curl_exec($ch);

        curl_close($ch);

        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $xml = new \SimpleXMLElement($response);
        $body = $xml->xpath('//sBody')[0];
        $array = json_decode(json_encode((array)$body), true);
        $array = $array['SearchBatchesIdentificationInfoResponse']['SearchBatchesIdentificationInfoResult']['BatchIdentificationInfoType'];

        return $array;
    }
}

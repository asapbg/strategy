<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\InstitutionLevel;
use App\Models\Pris;
use App\Models\StrategicDocuments\Institution;
use App\Models\Tag;
use App\Services\FileOcr;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class seedFixInstitutionPris extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'old:fix_pris_institution';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing or wrong institutions in PRIS';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Create default institution
        $institutionForMapping = [];
        $dInstitution = Institution::where('email', '=', 'magdalena.mitkova+egov@asap.bg')->withTrashed()->first();
        $importers = [
            ':FIL' => [
                'importer' => ':FIL',
                'institution_id' => null,
            ],
            ':RE' => [
                'importer' => ':RE',
                'institution_id' => null,
            ],
            'ДА' => [
                'importer' => 'ДА',
                'institution_id' => null,
            ],
            'ДАЕЕР' => [
                'importer' => 'ДАЕЕР',
                'institution_id' => null,
            ],
            'ДАИТС' => [
                'importer' => 'ДАИТС',
                'institution_id' => null,
            ],
            'ЕВ' => [
                'importer' => 'ЕВ',
                'institution_id' => null,
            ],
            'зам. министър-председателят' => [
                'importer' => 'зам. министър-председателят',
                'institution_id' => null,
            ],
            'зам. министър-председателят и председател на ЦКБППМН' => [
                'importer' => 'зам. министър-председателят и председател на ЦКБППМН',
                'institution_id' => null,
            ],
            'и.д. главен секретар на МС' => [
                'importer' => 'и.д. главен секретар на МС',
                'institution_id' => null,
            ],
            'М3' => [
                'importer' => 'М3',
                'institution_id' => 131,
            ],
            'МВнР' => [
                'importer' => 'МВнР',
                'institution_id' => 127,
            ],
            'МВР' => [
                'importer' => 'МВР',
                'institution_id' => 127,
            ],
            'МДА' => [
                'importer' => 'МДА',
                'institution_id' => null,
            ],
            'МДААР' => [
                'importer' => 'МДААР',
                'institution_id' => null,
            ],
            'МДПБА' => [
                'importer' => 'МДПБА',
                'institution_id' => null,
            ],
            'МЕВ' => [
                'importer' => 'МЕВ',
                'institution_id' => null,
            ],
            'МЕЕР' => [
                'importer' => 'МЕЕР',
                'institution_id' => null,
            ],
            'МЗ' => [
                'importer' => 'МЗ',
                'institution_id' => null,
            ],
            'МЗГ' => [
                'importer' => 'МЗГ',
                'institution_id' => null,
            ],
            'МЗГАР' => [
                'importer' => 'МЗГАР',
                'institution_id' => null,
            ],
            'МЗГБ' => [
                'importer' => 'МЗГБ',
                'institution_id' => null,
            ],
            'МЗГП' => [
                'importer' => 'МЗГП',
                'institution_id' => null,
            ],
            'МЗП' => [
                'importer' => 'МЗП',
                'institution_id' => null,
            ],
            'МЗХ' => [
                'importer' => 'МЗХ',
                'institution_id' => 132,
            ],
            'МИ' => [
                'importer' => 'МИ',
                'institution_id' => null,
            ],
            'МИЕ' => [
                'importer' => 'МИЕ',
                'institution_id' => null,
            ],
            'МИЕТ' => [
                'importer' => 'МИЕТ',
                'institution_id' => null,
            ],
            'министър без портфейл' => [
                'importer' => 'министър без портфейл',
                'institution_id' => null,
            ],
            'министър без портфейл (Ал. Праматарски)' => [
                'importer' => 'министър без портфейл (Ал. Праматарски)',
                'institution_id' => null,
            ],
            'министър без портфейл (Б. Димитров)' => [
                'importer' => 'министър без портфейл (Б. Димитров)',
                'institution_id' => null,
            ],
            'министър без портфейл (М. Кунева)' => [
                'importer' => 'министър без портфейл (М. Кунева)',
                'institution_id' => null,
            ],
            'министър без портфейл (Н. Моллов)' => [
                'importer' => 'министър без портфейл (Н. Моллов)',
                'institution_id' => null,
            ],
            'министър без портфейл (Ф. Хюсменова)' => [
                'importer' => 'министър без портфейл (Ф. Хюсменова)',
                'institution_id' => null,
            ],
            'министър-председателят' => [
                'importer' => 'министър-председателят',
                'institution_id' => 126,
            ],
            'МИС' => [
                'importer' => 'МИС',
                'institution_id' => null,
            ],
            'МК' => [
                'importer' => 'МК',
                'institution_id' => 135,
            ],
            'МКТ' => [
                'importer' => 'МКТ',
                'institution_id' => null,
            ],
            'ММС' => [
                'importer' => 'ММС',
                'institution_id' => 136,
            ],
            'МНО' => [
                'importer' => 'МНО',
                'institution_id' => null,
            ],
            'МО' => [
                'importer' => 'МО',
                'institution_id' => 139,
            ],
            'МОМН' => [
                'importer' => 'МОМН',
                'institution_id' => null,
            ],
            'МОН' => [
                'importer' => 'МОН',
                'institution_id' => 137,
            ],
            'МОСВ' => [
                'importer' => 'МОСВ',
                'institution_id' => 138,
            ],
            'МП' => [
                'importer' => 'МП',
                'institution_id' => 140,
            ],
            'МППЕИ' => [
                'importer' => 'МППЕИ',
                'institution_id' => null,
            ],
            'МПр' => [
                'importer' => 'МПр',
                'institution_id' => null,
            ],
            'МРРБ' => [
                'importer' => 'МРРБ',
                'institution_id' => 141,
            ],
            'МС' => [
                'importer' => 'МС',
                'institution_id' => null,
            ],
            'МТ' => [
                'importer' => 'МТ',
                'institution_id' => 144,
            ],
            'МТИТС' => [
                'importer' => 'МТИТС',
                'institution_id' => null,
            ],
            'МТС' => [
                'importer' => 'МТС',
                'institution_id' => 142,
            ],
            'МТСГ' => [
                'importer' => 'МТСГ',
                'institution_id' => null,
            ],
            'МТСП' => [
                'importer' => 'МТСП',
                'institution_id' => 143,
            ],
            'МТТ' => [
                'importer' => 'МТТ',
                'institution_id' => null,
            ],
            'МФ' => [
                'importer' => 'МФ',
                'institution_id' => 145,
            ],
            'МФВС' => [
                'importer' => 'МФВС',
                'institution_id' => null,
            ]

        ];

        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('pris')->select('select max(archimed.e_items.id) from archimed.e_items');
        //start from this id in old database
        $currentStep = 1;

        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;

            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.' to Id: '.($currentStep + $step).PHP_EOL;
                $oldDbResult = DB::connection('pris')->select('select
                                pris.id as old_id,
                                pris."xml" as to_parse_xml_details
                            FROM archimed.e_items pris
                            where true
                                -- and pris.id = 24778
                                and pris.id >= ' . $currentStep . '
                                and pris.id < ' . ($currentStep + $step) . '
                                and pris.itemtypeid <> 5017 -- skip law records
                                -- and documents.lastrevision = \'Y\' -- get final versions
                            group by pris.id
                            order by pris.id asc');


                if (sizeof($oldDbResult)) {
                    DB::beginTransaction();
                    foreach ($oldDbResult as $item) {
                        $pris = null;
                        try {
                            $importerStr = [];
                            $importerInstitutions = [];
                            $xml = simplexml_load_string($item->to_parse_xml_details);
                            $json = json_encode($xml, JSON_UNESCAPED_UNICODE);
                            $data = json_decode($json, true);

                            if(isset($data['DocumentContent']) && isset($data['DocumentContent']['Attribute']) && sizeof($data['DocumentContent']['Attribute'])) {
                                $attributes = $data['DocumentContent']['Attribute'];
                                foreach ($attributes as $att) {
                                    //importer
                                    //institution_id
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Вносител') {
                                        $val = isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value']) ? $att['Value']['Value'] : (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value']) ? $att['Value'] : null);
                                        if(!empty($val)) {
                                            $val = str_replace('"', '', $val);
                                            $explode = explode(',', $val);
                                            foreach ($explode as $e) {
                                                //mapping
                                                if(isset($importers[trim($e)])) {
                                                    $importerStr[]= $importers[trim($e)]['importer'];
                                                    if(!is_null($importers[trim($e)]['institution_id'])) {
                                                        $importerInstitutions[] = $importers[trim($e)]['institution_id'];
                                                    } else{
                                                        $institutionForMapping[trim($e)] = trim($e);
                                                    }
                                                } else{
                                                    $institutionForMapping[trim($e)] = trim($e);
                                                }
                                            }

                                        }
                                    }
                                }

                                $pris = Pris::where('old_id', '=', (int)$item->old_id)->first();
                                if($pris) {
                                    $pris->importer = sizeof($importerStr) ? implode(', ', $importerStr) : '';
                                    if(isset($importerInstitutions) && sizeof($importerInstitutions)) {
                                        $this->comment('PRIS with old id (' . $item->old_id . ') institutions are updated');
                                        $pris->institutions()->sync($importerInstitutions);
                                    } else{
                                        $this->comment('PRIS with old id (' . $item->old_id . ') No institution');
                                        $pris->institutions()->sync([$dInstitution->id]);
                                    }
                                    $pris->save();
                                } else{
                                    $this->comment('Can\'t find PRIS with old id (' . $item->old_id . ')');
                                }

                            }

                            DB::commit();
                        } catch (\Exception $e) {
                            Log::error('Error Migration Fix pris institution: ' . $e);
                            DB::rollBack();
                            dd($item ?? 'no old data', $importerInstitutions ?? 'no institutions');
                        }
                    }
                }
                $currentStep += $step;
            }
        }

        if(sizeof($institutionForMapping)) {
            $fp = fopen('institutions_for_mapping_last_pris.csv', 'w');
            foreach ($institutionForMapping as $fields) {
                fputcsv($fp, [$fields]);
            }
            fclose($fp);
        }
    }
}

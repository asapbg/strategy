<?php

namespace App\Console\Commands;

use App\Enums\PrisDocChangeTypeEnum;
use App\Models\InstitutionLevel;
use App\Models\LegalActType;
use App\Models\Pris;
use App\Models\StrategicDocuments\Institution;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixOldPrisImporters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:pris_importers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix old PRIS importers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        file_put_contents('institutions_for_mapping_last_pris.txt', '');
        $institutionForMapping = [];
        //Create default institution
        $diEmail = 'magdalena.mitkova+egov@asap.bg';
        $dInstitution = Institution::where('email', '=', $diEmail)->withTrashed()->first();
        $locales = config('available_languages');
        if(!$dInstitution) {
            $insLevel = InstitutionLevel::create([
                'system_name' => 'default'
            ]);
            if(!$insLevel) {
                $this->error('Cant create default institution');
            }
            if($insLevel) {
                foreach ($locales as $locale) {
                    $insLevel->translateOrNew($locale['code'])->name = 'Default Level';
                }
                $insLevel->save();
            }

            $dInstitution = Institution::create([
                'email' => $diEmail,
                'institution_level_id' => $insLevel->id
            ]);

            if(!$dInstitution) {
                $this->error('Cant create default institution');
            }
            foreach ($locales as $locale) {
                $dInstitution->translateOrNew($locale['code'])->name = 'Default';
            }
            $dInstitution->save();
        }
        $ourPris = Pris::whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();

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
                'institution_id' => 127,
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
        $currentStep = 0;

        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;

            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.PHP_EOL;
                $oldDbResult = DB::connection('pris')->select('select
                        pris.id as old_id,
                        pris."number" as doc_num,
                        pris.parentid as parentdocumentid,
                        pris.rootid as rootdocumentid,
                        pris.masterid,
                        pris.state,
                        pris.xstate,
                        case when pris.islatestrevision = false then 0 else 1 end as last_version,
                        pris.itemtypeid as old_doc_type_id,
                        pris."xml" as to_parse_xml_details,
                        pris.activestate as active, -- check with distinct if only 0 and 1 // check also pris.state
                        pris.datepublished as published_at,
                        pris.datecreated as created_at,
                        pris.datemodified as updated_at,
                        sum(case when att.attachid is not null then 1 else 0 end) as has_files
                    FROM archimed.e_items pris
                    left join edocs.attachments att on att.documentid = pris.id
                    where true
                        and pris.id >= ' . $currentStep . '
                        and pris.id < ' . ($currentStep + $step) . '
                        and pris.itemtypeid <> 5017 -- skip law records
                        -- and documents.lastrevision = \'Y\' -- get final versions
                    group by pris.id
                    order by pris.id asc');

                if (sizeof($oldDbResult)) {
                    foreach ($oldDbResult as $item) {
                        //Update existing
                        if(isset($ourPris) && sizeof($ourPris) && isset($ourPris[(int)$item->old_id])) {
                            $existPris = Pris::find($ourPris[(int)$item->old_id]);

                            if ($existPris) {
                                $this->comment('Start update importers for Pris with old id ' . $item->old_id);
                                DB::beginTransaction();
                                //Update importers
                                try {
                                    $importerInstitutions = [];
                                    $xml = simplexml_load_string($item->to_parse_xml_details);
                                    $json = json_encode($xml, JSON_UNESCAPED_UNICODE);
                                    $data = json_decode($json, true);
                                    if (isset($data['DocumentContent']) && isset($data['DocumentContent']['Attribute']) && sizeof($data['DocumentContent']['Attribute'])) {
                                        $attributes = $data['DocumentContent']['Attribute'];
                                        foreach ($attributes as $att) {
                                            //importer
                                            //institution_id
                                            if (isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Вносител') {
                                                $val = isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value']) ? $att['Value']['Value'] : (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value']) ? $att['Value'] : null);
                                                if ($val) {
                                                    //echo "Importer: ".$att['Value']['Value'].PHP_EOL;
                                                    $importerStr = [];
                                                    $importerInstitutions = [];
                                                    $explode = explode(',', $val);
                                                    foreach ($explode as $e) {
                                                        if (isset($importers[trim($e)])) {
                                                            if (!is_null($importers[trim($e)]['institution_id'])) {
                                                                $importerInstitutions[] = $importers[trim($e)]['institution_id'];
                                                            } else{
                                                                if(!empty($importers[trim($e)]['importer'])){
                                                                    $importerStr[] = $importers[trim($e)]['importer'];
                                                                }
                                                                if(!isset($institutionForMapping[trim($e)])) {
                                                                    //TODO for mapping
                                                                    file_put_contents('institutions_for_mapping_last_pris.txt', 'Missing ID in mapping: ' . $e . PHP_EOL, FILE_APPEND);
                                                                    $institutionForMapping[trim($e)] = trim($e);
                                                                }
                                                            }
                                                        } else{
                                                            //TODO for mapping
                                                            if(!isset($institutionForMapping[trim($e)])){
                                                                file_put_contents('institutions_for_mapping_last_pris.txt', 'Missing in mapping: '.$e.PHP_EOL, FILE_APPEND);
                                                                $institutionForMapping[trim($e)] = trim($e);
                                                            }
                                                        }
                                                    }

                                                    $existPris->old_importers = $val;
                                                    $existPris->save();
                                                    $existPris->translateOrNew('bg')->importer = sizeof($importerStr) ? implode(', ', $importerStr) : '';
                                                    $existPris->translateOrNew('en')->importer = sizeof($importerStr) ? implode(', ', $importerStr) : '';
//                                                    $existPris->importer = sizeof($importerStr) ? implode(', ', $importerStr) : '';
                                                    if (isset($importerInstitutions) && sizeof($importerInstitutions)) {
                                                        $existPris->institutions()->sync($importerInstitutions);
                                                    } else {
                                                        $existPris->institutions()->sync([$dInstitution->id]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    DB::commit();
                                } catch (\Exception $e) {
                                    Log::error('Migration update old pris importers error: ' . $e);
                                    DB::rollBack();
                                }
                            }
                        }
                    }
                }
                $currentStep += $step;
            }
        }

        return Command::SUCCESS;
    }
}

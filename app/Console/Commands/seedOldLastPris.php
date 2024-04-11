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

class seedOldLastPris extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'old:pris';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate last PRIS data to application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $institutionForMapping = [];
        $locales = config('available_languages');

        //Create default institution
        $diEmail = 'magdalena.mitkova+egov@asap.bg';
        $dInstitution = Institution::where('email', '=', $diEmail)->withTrashed()->first();
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

        $ourTags = Tag::with(['translation'])->get()->pluck('id', 'translation.label')->toArray();
        $ourPris = Pris::whereNotNull('old_id')->get()->pluck('id', 'old_id')->toArray();

        $legalTypeDocs = [
            5017 => 7, //'Заповед',
            5018 => 2, //'Решение',
            5019 => 1, //'Постановление',
            5020 => 5, //'Протокол',
            5021 => 4, //'Разпореждане',
            5022 => 6, //'Стенограма',
        ];

        //If category is 'Протокол' and doc_num is float and doc_num is < 100, then we should move document to 'Протоколни решения',
        $protocolsId = 5;
        $protocolDecisionsId = 3;

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

        $formatTimestamp = 'Y-m-d H:i:s';
        $formatDate = 'Y-m-d';
        //records per query
        $step = 50;
        //max id in old db
        $maxOldId = DB::connection('pris')->select('select max(archimed.e_items.id) from archimed.e_items');
        //start from this id in old database
//        $currentStep = DB::table('pris')->select(DB::raw('max(old_id) as max'))->first()->max + 1;
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
                        if(isset($ourPris) && sizeof($ourPris) && isset($ourPris[(int)$item->old_id])){
                            $this->comment('Pris with old id '.$item->old_id.' already exist');
                            $existPris = Pris::find($ourPris[(int)$item->old_id]);

                            if($existPris){
                                //Update version
                                if($existPris->last_version != $item->last_version){
                                    $existPris->last_version = $item->last_version;
                                    $existPris->save();
                                    DB::commit();
                                }
                            }
                            continue;
                        }

                        DB::beginTransaction();
                        try {
                            $fileForExeption = null;
                            $importerInstitutions = [];
                            $tags = [];
                            $newItemTags = [];//tags ids to connect to new item

                            $xml = simplexml_load_string($item->to_parse_xml_details);
                            $json = json_encode($xml, JSON_UNESCAPED_UNICODE);
                            $data = json_decode($json, true);

                            if(isset($data['DocumentContent']) && isset($data['DocumentContent']['Attribute']) && sizeof($data['DocumentContent']['Attribute'])) {
                                $attributes = $data['DocumentContent']['Attribute'];
                                //main record
                                $prepareNewPris = [
                                    'old_id' => $item->old_id,
                                    'doc_num' => $item->doc_num,
                                    'doc_date' => null,
                                    'old_doc_num' => $item->doc_num,
                                    'active' => $item->active,
                                    'legal_act_type_id' => $legalTypeDocs[$item->old_doc_type_id],
                                    'published_at' => Carbon::parse($item->published_at)->format($formatTimestamp),
                                    'created_at' => Carbon::parse($item->created_at)->format($formatTimestamp),
                                    'updated_at' => Carbon::parse($item->updated_at)->format($formatTimestamp),
                                    'institution_id' => null, // When old record do not have institution use our default : $dInstitution
                                    'version' => null, // TODO ??????
                                    'protocol' => null,
                                    'newspaper_number' => null,
                                    'newspaper_year' => null,
                                    'old_newspaper_full' => null,
                                    'old_connections' => null,
                                    'public_consultation_id' => null,
                                    'about' => '',
                                    'legal_reason' => '',
                                    'importer' => '',
                                    'state' => $item->state,
                                    'xstate' => $item->xstate,
                                    'last_version' => $item->last_version,
                                ];
                                //Do something
                                //1. Parse tags and insert if need to
                                foreach ($attributes as $att) {
                                    //get tags
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Термини') {
                                        if(isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value'])) {
                                            echo "Tags: ".$att['Value']['Value'].PHP_EOL;
                                            $tags = preg_split('/\r\n|\r|\n/', $att['Value']['Value']);
                                        } elseif (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value'])) {
                                            echo "Tags: ".$att['Value'].PHP_EOL;
                                            $tags = preg_split('/\r\n|\r|\n/', $att['Value']);
                                        }
                                    }
                                    //get date
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Дата') {
                                        if(isset($att['Value']) && isset($att['Value']['Date']) && !empty($att['Value']['Date'])) {
                                            $prepareNewPris['doc_date'] = Carbon::parse($att['Value']['Date'])->format($formatDate);
                                        }
                                    }
                                    //get number
                                    if(empty($prepareNewPris['doc_num']) && isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Номер') {
                                        $val = isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value']) ? $att['Value']['Value'] : (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value']) ? $att['Value'] : null);
                                        if($val) {
                                            //echo "Doc Num: ".$att['Value']['Value'].PHP_EOL;
                                            $prepareNewPris['old_doc_num'] = $val;
                                            $docNum = explode('-', $val);
                                            $prepareNewPris['doc_num'] = sizeof($docNum) == 2 ? (int)$docNum[1] : $docNum[0];
                                        }
                                    }
                                    //get protocol
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Протокол') {
                                        if(isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value'])) {
                                            $prepareNewPris['protocol'] = is_array($att['Value']['Value']) ? (!empty($att['Value']['Value']) ? implode(';', $att['Value']['Value']) : null) : $att['Value']['Value'];
                                        } elseif (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value']) && !isset($att['Value']['Value'])) {
                                            $prepareNewPris['protocol'] = is_array($att['Value']) ? (!empty($att['Value']) ? implode(';', $att['Value']) : null) : $att['Value'];
                                        }
                                    }
                                    //newspaper num
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'ДВ брой') {
                                        if(isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value'])) {
                                            $prepareNewPris['newspaper_number'] = (int)$att['Value']['Value'];
                                        } elseif (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value'])) {
                                            $prepareNewPris['newspaper_number'] = (int)$att['Value'];
                                        }
                                    }
                                    //newspaper year
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'ДВ година') {
                                        if(isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value'])) {
                                            $prepareNewPris['newspaper_year'] = (int)$att['Value']['Value'];
                                        } elseif (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value'])) {
                                            $prepareNewPris['newspaper_year'] = (int)$att['Value'];
                                        }
                                    }
                                    //newspaper full
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Обнародвано в ДВ') {
                                        if(isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value'])) {
                                            $prepareNewPris['old_newspaper_full'] = $att['Value']['Value'];
                                        } elseif (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value'])) {
                                            $prepareNewPris['old_newspaper_full'] = $att['Value'];
                                        }
                                    }
                                    //importer
                                    //institution_id
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Вносител') {
                                        $val = isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value']) ? $att['Value']['Value'] : (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value']) ? $att['Value'] : null);
                                        if($val) {
                                            //echo "Importer: ".$att['Value']['Value'].PHP_EOL;
                                            $importerStr = [];
                                            $importerInstitutions = [];

                                            $explode = explode(',', $val);
                                            foreach ($explode as $e) {
                                                //TODO for mapping
                                                $institutionForMapping[trim($e)] = trim($e);

                                                if(isset($importers[trim($e)])) {
                                                    $importerStr[]= $importers[trim($e)]['importer'];
                                                    if(!is_null($importers[trim($e)]['institution_id'])) {
                                                        $importerInstitutions[] = $importers[trim($e)]['institution_id'];
                                                    }
                                                }
                                            }
                                            $prepareNewPris['importer'] = sizeof($importerStr) ? implode(', ', $importerStr) : '';
                                        }
                                    }
                                    //get about
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Относно') {
                                        if(isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value'])) {
                                            $prepareNewPris['about'] = $att['Value']['Value'];
                                        } elseif (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value'])) {
                                            $prepareNewPris['about'] = $att['Value'];
                                        }
                                    }
                                    //get about
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Правно основание') {
                                        if(isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value'])) {
                                            $prepareNewPris['legal_reason'] = $att['Value']['Value'];
                                        } elseif (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value'])) {
                                            $prepareNewPris['legal_reason'] = $att['Value'];
                                        }
                                    }
                                    //4. Parse id doc connections and create them in pris_change_pris
                                    //get old pris change pris
                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Промени') {
                                        $val = isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value']) ? $att['Value']['Value'] : (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value']) ? $att['Value'] : null);
                                        if($val) {
                                            //echo "Changes: ".$att['Value']['Value'].PHP_EOL;
                                            $oldChanges = preg_split('/\r\n|\r|\n/', $val);
                                            $prepareNewPris['old_connections'] = sizeof($oldChanges) ? implode('; ', $oldChanges) : $oldChanges;
                                        }
                                    }

                                    if(isset($att['@attributes']) && isset($att['@attributes']['Name']) && $att['@attributes']['Name'] == 'Статус') {
                                        if(isset($att['Value']) && isset($att['Value']['Value']) && !empty($att['Value']['Value'])) {
                                            $prepareNewPris['connection_status'] = (int)$att['Value']['Value'];
                                        } elseif (isset($att['Value']) && !empty($att['Value']) && !isset($att['Value']['Value'])) {
                                            $prepareNewPris['connection_status'] = (int)$att['Value'];
                                        }
                                    }

                                }

                                //Legal type category correction if need to
                                if($prepareNewPris['legal_act_type_id'] == $protocolsId && str_contains($prepareNewPris['doc_num'], '.')) {
                                    $prepareNewPris['legal_act_type_id'] = $protocolDecisionsId;
                                }

                                //2. Create pris record and translations
                                $newItem = new Pris();
                                $newItem->fill($prepareNewPris);
                                $newItem->save();
                                if($newItem->id) {
                                    foreach ($locales as $locale) {
                                        $newItem->translateOrNew($locale['code'])->about = $prepareNewPris['about'];
                                        $newItem->translateOrNew($locale['code'])->legal_reason = $prepareNewPris['legal_reason'];
                                        $newItem->translateOrNew($locale['code'])->importer = $prepareNewPris['importer'];
                                    }
                                    $newItem->save();

                                    if(isset($importerInstitutions) && sizeof($importerInstitutions)) {
                                        $newItem->institutions()->sync($importerInstitutions);
                                    } else{
                                        $newItem->institutions()->sync([$dInstitution->id]);
                                    }
                                }

                                //3. Create connection pris - tags
                                if($newItem && sizeof($tags)) {
                                    foreach ($tags as $tag) {
                                        if(!isset($ourTags[$tag])) {
                                            //create tag
                                            $newTag = \App\Models\Tag::create();
                                            if( $newTag ) {
                                                foreach ($locales as $locale) {
                                                    $newTag->translateOrNew($locale['code'])->label = $tag;
                                                }
                                            }
                                            $newTag->save();
                                            echo "Tag with name ".$tag." created successfully".PHP_EOL;
                                            $ourTags[$tag] = $newTag->id;
                                        }
                                        $newItemTags[] = $ourTags[$tag];
                                    }
                                    if(sizeof($newItemTags)) {
                                        $newItem->tags()->sync($newItemTags);
                                        $newItem->save();
                                    }
                                }

                                //TODO //5. Create files and extract text
                                $path = File::PAGE_UPLOAD_PRIS;
                                $oldPages = DB::connection('pris')
                                    ->select('
                                    select
                                         split_part(f.bloburi, \'/\', -1) as uuid,
                                         f.filename  as filename,
                                         f.contenttype as content_type,
                                         f.datecreated as created_at,
                                         f.datemodified as updated_at,
                                         ft."text" as file_text,
                                         b."content" as file_content
                                    from edocs.attachments att
                                    join archimed.blobs f on f.id = att.blobid
                                    join archimed.blobtexts ft on ft.blobid = f.id
                                    join blobs.blobcontents b on b.id::text = split_part(f.bloburi, \'/\', -1)
                                    where true
                                        and att.documentid = '.$newItem->old_id.'
                                    order by att.documentid asc, att.pageid asc');

                                if (sizeof($oldPages)) {
                                    foreach ($oldPages as $f) {
                                        $fileForExeption = $f;
                                        $file = null;
                                        if(!empty($f->file_content)) {
//                                            $fileNameToStore = str_replace('.', '', microtime(true)).strtolower($f->doc_type);
                                            $fileNameToStore = trim($f->filename);
                                            $fullPath = $path.$fileNameToStore;
                                            Storage::disk('public_uploads')->put($fullPath, $f->file_content);
                                            $file = Storage::disk('public_uploads')->get($fullPath);
                                        }

                                        if($file) {
                                            $fileIds = [];
                                            foreach (['bg', 'en'] as $code) {
                                                //TODO catch file version
                                                //$version = File::where('locale', '=', $code)->where('id_object', '=', $newItem->id)->where('code_object', '=', File::CODE_OBJ_PRIS)->count();
                                                $version = 0;
                                                $newFile = new File([
                                                    'id_object' => $newItem->id,
                                                    'code_object' => File::CODE_OBJ_PRIS,
                                                    'filename' => $fileNameToStore,
                                                    'content_type' => Storage::disk('public_uploads')->mimeType($fullPath),
                                                    'path' => $fullPath,
                                                    'description_'.$code => $f->filename,
                                                    'sys_user' => null,
                                                    'locale' => $code,
                                                    'version' => ($version + 1).'.0',
                                                    'created_at' => Carbon::parse($f->created_at)->format($formatTimestamp),
                                                    'updated_at' => Carbon::parse($f->updated_at)->format($formatTimestamp)
                                                ]);
                                                $newFile->save();
                                                $fileIds[] = $newFile->id;
                                                $ocr = new FileOcr($newFile->refresh());
                                                $ocr->extractText();
                                            }

                                            File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
                                            File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);
                                        }
                                    }
                                }
                            }
                            $this->comment('PRIS with old id (' . $item->old_id . ') is created');
                            DB::commit();
                        } catch (\Exception $e) {
                            Log::error('Migration old pris: ' . $e);
                            DB::rollBack();
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

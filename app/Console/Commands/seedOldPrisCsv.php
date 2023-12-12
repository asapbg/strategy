<?php

namespace App\Console\Commands;

use App\Enums\PrisConnectionStatusEnum;
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

class seedOldPrisCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:pris_csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old PRIS from csv data to application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
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
        $legalTypeDocs = [
            1 => 7, //'Заповед',
            2 => 2, //'Решение',
            3 => 1, //'Постановление',
            4 => 5, //'Протокол',
            5 => 4, //'Разпореждане',
            6 => 6, //'Стенограма',
        ];

        $prisStatuses = [
            'изменен' => PrisConnectionStatusEnum::CHANGED->value,
            'допълнен' => PrisConnectionStatusEnum::SUPPLEMENTED->value,
            'отменен' => PrisConnectionStatusEnum::CANCELED->value,
            'поверителен' => PrisConnectionStatusEnum::CONFIDENRIAL
        ];

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
        $maxOldId = DB::connection('pris')->select('select max(af_documents.documentid) from af_documents');
        //start from this id in old database
        $currentStep = (int)DB::table('pris')->select(DB::raw('max(old_id) as max'))->first()->max + 1;

        $csvData = [];
        $csvFile = fopen(base_path("database/data/final-docs3-txt.csv"), "r");
        //1 documentid, 2 number_pris, 3 content, 4 act_no, 5 date, 6 title, 7 author, 8 protocol, 9 state_gazette_all, 10 state_gazette_issue, 11 state_gazette_year, 12 relationships, 13 normative_text, 14 tags, 15 status
        $firstRow = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if($firstRow) {$firstRow = false; continue;}
            if(is_array($data) && sizeof($data) == 15) {
                $csvData[(int)$data[0]] = $data;
            }

        }
        if( (int)$maxOldId[0]->max ) {
            $maxOldId = (int)$maxOldId[0]->max;

            while ($currentStep < $maxOldId) {
                echo "FromId: ".$currentStep.PHP_EOL;
                $oldDbResult = DB::connection('pris')->select('select
                                documents.documentid as old_id,
                                documents.parentdocumentid,
	                            documents.rootdocumentid,
                                case when documents.lastrevision = \'N\' then 0 else 1 end as last_vesrion,
                                documents.doctypeid as old_doc_type_id,
                                -- as version
                                -- as public_consultation_id
                                documents.content  as to_parse_xml_details, -- doc_num, about, doc_date, institution_id/importer ??, newspaper_number ??, newspaper_year ??, legal_reason ??, protocol ??, tags ??, pris_change_pris
                                contents.content as to_parse_txt_details2, -- same as to_parse_xml_details but in text format
                                case when documents.active = true then 1 else 0 end as active,
                                documents.publishdate as published_at,
                                documents.created as created_at,
                                documents.lastmodify as updated_at
                                -- file contents
                                -- att.pageid as page_ord,
                                -- att.attachment as page_content,
                                -- att.attachext as doc_type
                            from af_documents documents
                            left join af_content_fts contents on contents.documentid = documents.documentid
                            left join af_attachments att on att.documentid = documents.documentid
                            where true
                                and documents.documentid >= ' . $currentStep . '
                                and documents.documentid < ' . ($currentStep + $step) . '
                                and documents.doctypeid <> 1 -- skip law records
                                and documents.lastrevision = \'Y\' -- get final versions
                            order by documents.documentid, att.pageid, documents.lastrevision asc');

                if (sizeof($oldDbResult)) {
                    DB::beginTransaction();
                    try {
                        foreach ($oldDbResult as $item) {
                            $loopItem = $item;
                            if(!isset($csvData[$item->old_id])) {
                                $this->comment('OLD ID '.$item->old_id.' not found in csv - Skipped');
                                continue;
                            }
                            $loopCsvItem = $itemCsvData = $csvData[$item->old_id];
                            $tags = [];
                            $importerStr = [];
                            $importerInstitutions = [];
                            $newItemTags = [];//tags ids to connect to new item
                            if(!isset($legalTypeDocs[$item->old_doc_type_id])){
                                $this->comment('OLD ID '.$item->old_id.' missign legal act type (old id '.$item->old_doc_type_id.')');
                            }
                            //main record
                            $prepareNewPris = [
                                'old_id' => $item->old_id,
                                'doc_num' => null,
                                'doc_date' => null,
                                'old_doc_num' => null,
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
                            ];
                            //Do something
                            //1. Parse tags and insert if need to
                            //get tags
                            if(isset($itemCsvData[13])) {
                                $tags = explode('|', $itemCsvData[13]);
                            }
                            //get date
                            if(isset($itemCsvData[4])) {
                                $prepareNewPris['doc_date'] = Carbon::parse(trim($itemCsvData[4]))->format($formatDate);
                            }
                            //get number
                            if(isset($itemCsvData[3])) {
                                $prepareNewPris['doc_num'] = trim($itemCsvData[3]);
                            }
                            //get protocol
                            if(isset($itemCsvData[7])) {
                                $prepareNewPris['protocol'] = trim($itemCsvData[7]);
                            }
                            //newspaper num
                            if(isset($itemCsvData[9])) {
                                $prepareNewPris['newspaper_number'] = (int)$itemCsvData[9];
                            }
                            //newspaper year
                            if(isset($itemCsvData[10])) {
                                $prepareNewPris['newspaper_year'] = (int)$itemCsvData[10];
                            }
                            //newspaper full
                            if(isset($itemCsvData[8])) {
                                $prepareNewPris['old_newspaper_full'] = trim($itemCsvData[8]);
                            }
                            //importer
                            //institution_id
                            if(isset($itemCsvData[6])) {
                                $explodeInstitutions = explode(',', $itemCsvData[7]);
                                foreach ($explodeInstitutions as $e) {
                                    if(isset($importers[trim($e)])) {
                                        $importerStr[]= $importers[trim($e)]['importer'];
                                        if(!is_null($importers[trim($e)]['institution_id'])) {
                                            $importerInstitutions[] = $importers[trim($e)]['institution_id'];
                                        }
                                    }
                                }
                                $prepareNewPris['importer'] = sizeof($importerStr) ? implode(', ', $importerStr) : '';
                            }
                            //get about
                            if(isset($itemCsvData[5])) {
                                $prepareNewPris['about'] = trim($itemCsvData[5]);
                            }
                            //get legal_reason
                            if(isset($itemCsvData[12])) {
                                $prepareNewPris['legal_reason'] = trim($itemCsvData[12]);
                            }
                            //4. Parse id doc connections and create them in pris_change_pris
                            //get old pris change pris
                            if(isset($itemCsvData[11])) {
                                $prepareNewPris['old_connections'] = trim($itemCsvData[11]);
                            }

                            //get connection status
                            if(isset($itemCsvData[8])) {
                                $prepareNewPris['connection_status'] = $prisStatuses[trim($itemCsvData[8])] ?? 0;
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

                                //TODO //5. Create fiscal files and extract text
                                $path = File::PAGE_UPLOAD_PRIS;
                                $oldPages = DB::connection('pris')
                                    ->select('select
                                        -- file contents
                                         att.pageid as page_ord,
                                         att.attachment as page_content,
                                         att.attachext as doc_type
                                    from af_attachments att
                                    where true
                                        and att.documentid = '.$newItem->old_id.'
                                    order by att.documentid asc, att.pageid asc');

                                if (sizeof($oldPages)) {
                                    foreach ($oldPages as $item) {
                                        $file = null;
                                        if(!empty($item->page_content)) {
                                            $fileNameToStore = str_replace('.', '', microtime(true)).strtolower($item->doc_type);
                                            $fullPath = $path.$fileNameToStore;
                                            Storage::disk('public_uploads')->put($fullPath, $item->page_content);
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
                                                    'description_'.$code => null,
                                                    'sys_user' => null,
                                                    'locale' => $code,
                                                    'version' => ($version + 1).'.0'
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
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::error('Migration old pris: ' . $e);
                        DB::rollBack();
                        dd($prepareNewPris, $newItemTags, $loopItem, $loopCsvItem, $importerInstitutions, $dInstitution);
                    }
                }
                $currentStep += $step;
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\LegalActType;
use App\Models\Pris;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PrisSplitTrasncriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pris:split_transcriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Splits all transcriptions with protocol file in to two documents. Old stays as transcriptions and the new one is created as protocol with the same number and info, plus attached protocol file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start at: '. date('Y-m-d H:i:s'));
        //Search for transcripts category
        $dbTranscripts = Pris::where('pris.legal_act_type_id', '=', LegalActType::TYPE_TRANSCRIPTS)
            ->where('asap_last_version', 1)
            ->orderBy('id', 'desc')
            ->get();

        if(!$dbTranscripts->count()){
            return Command::SUCCESS;
        }

        foreach ($dbTranscripts as $transcript){

            //Check if there is protocol already
            $dbProtocol = Pris::where('doc_num', $transcript->doc_num)
                ->where('pris.legal_act_type_id', '=', LegalActType::TYPE_PROTOCOL)
                ->where('doc_date', '>=', Carbon::parse($transcript->doc_date)->startOfYear()->format('Y-m-d'))
                ->where('doc_date', '<=', Carbon::parse($transcript->doc_date)->endOfYear()->format('Y-m-d'))
                ->where('asap_last_version', 1)
                ->get()
                ->first();

            if($dbProtocol) {
                $this->info('Protocol exist');
                continue;
            }

            if(!$transcript->filesByLocale->count()){
                $this->info('Transcription do not have files');
                continue;
            }

            foreach ($transcript->filesByLocale as $f){
                //If there is protocol file in it
                if(str_contains($f->filename, 'ротокол') || $f->filename == $transcript->doc_num.'.doc'){
                    try {
                        //$this->info('File: '.$f->filename.' | Стенограма дата: '.$transcript->doc_date.' | Стенограма номер: '.$transcript->doc_num);
                        //Create protocol with this file and flag 'from_transcripts'
                        $newProtocol = $transcript->replicate();
                        $newProtocol->legal_act_type_id = LegalActType::TYPE_PROTOCOL;
                        $newProtocol->protocol = null;
                        $newProtocol->old_id = null;
                        $newProtocol->old_doc_num = null;
                        $newProtocol->decision_protocol = null;
                        $newProtocol->protocol_point = null;
                        $newProtocol->from_transcripts = 1;
                        $newProtocol->push();
                        //Tags
                        $tags = $transcript->tags->pluck('id')->toArray();
                        $newProtocol->tags()->sync($tags);
                        //Institutions
                        $institutions = $transcript->institutions->pluck('id')->toArray();
                        $newProtocol->institutions()->sync($institutions);

                        foreach ($transcript->translations as $translation){
                            $cloneLang = $translation->replicate();
                            $newProtocol->translations()->save($cloneLang);
                        }

                        foreach (config('available_languages') as $lang){
                            $newProtocolFile = $f->replicate(['file_text_ts_bg']);
                            $newProtocolFile->id_object = $newProtocol->id;
                            if($lang['code'] == 'en'){
                                $newProtocolFile->description_en = $f->description_bg;
                                $newProtocolFile->description_bg = null;
                                $newProtocolFile->locale = 'en';
                            }
                            $newProtocol->files()->save($newProtocolFile);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Command PrisSplitTrasncriptions error: ' . $e);
                    }
                }
            }
        }
        $this->info('End at: '. date('Y-m-d H:i:s'));
        return Command::SUCCESS;
    }
}

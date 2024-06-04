<?php

namespace App\Console\Commands;

use App\Models\LegalActType;
use App\Models\Pris;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PrisClearTranscriptionsProtocolFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pris:clear_transcriptions_protocol';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove protocol files from transcriptions.';

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
            $files = $transcript->files()->where('locale', '=', 'bg')->get();
            if(!$files->count()){
                $this->info('Transcription do not have files');
                continue;
            }

            foreach ($files as $f){
                //If there is protocol file: delete it
                if(str_contains($f->filename, 'ротокол') || $f->filename == $transcript->doc_num.'.doc'){
                    $f->delete();
                }
            }

        }
        $this->info('End at: '. date('Y-m-d H:i:s'));
        return Command::SUCCESS;
    }
}

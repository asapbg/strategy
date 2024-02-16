<?php

namespace App\Console\Commands;

use App\Models\StrategicDocumentFile;
use App\Models\StrategicDocumentFileTranslation;
use Illuminate\Console\Command;

class FixStrategicFilesTranscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:sd_files_translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing description for strategic document files name';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = StrategicDocumentFile::whereNotNull('old_file_id')
            ->where('locale', '=', 'bg')
            ->whereNull('description')
            ->get();

        if($files->count()){
            foreach ($files as $f){
                $findTranslation = StrategicDocumentFile::whereNotNull('description')
                    ->where('locale', '=', 'en')
                    ->where('old_file_id', '=', $f->old_file_id)
                    ->first();
                if($findTranslation) {
                    $f->description = $findTranslation->description;
                }
            }
            $this->comment("Records are updated");
            return Command::SUCCESS;
        } else{
            $this->comment("Didn't find any records for update");
            return Command::SUCCESS;
        }
    }
}

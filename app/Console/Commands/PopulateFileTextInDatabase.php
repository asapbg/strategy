<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Services\FileOcr;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PopulateFileTextInDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:file_file_text';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all files that has empty file_text field and populate them';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = File::select('files.*')
            ->join('pris', 'pris.id', '=',  'files.id_object')
            ->whereNull('pris.deleted_at')
            ->where('pris.active', true)
            ->where('pris.asap_last_version', true)
            ->where('files.locale', '=', 'bg')
            ->whereRaw("(file_text is null or file_text = '')")
            ->where('code_object', File::CODE_OBJ_PRIS)
            ->whereNotIn('content_type', [
                'application/vnd.ms-excel',
                'application/x-rar',
                'image/tiff',
                'image/gif',
                'image/jpeg',
                'application/zip',
                'application/x-7z-compressed',
                'application/vnd.ms-powerpoint'
            ])
            ->orderBy('files.id', 'desc')
            //->take(2)
            ->get();
        //dd($files->toArray());

        foreach ($files as $file) {
            $ocr = new FileOcr($file);
            $ocr->extractText();
            $this->info("Text updated for File ID $file->id, File path: ".Storage::disk('public_uploads')->path($file->path));
        }

        return Command::SUCCESS;
    }
}

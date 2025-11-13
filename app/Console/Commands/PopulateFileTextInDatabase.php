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
            ->whereNull('file_text')
            ->join('pris', 'pris.id', '=',  'files.id_object')
            ->where('files.locale', '=', 'bg')
            ->whereNull('pris.deleted_at')
            ->where('code_object', File::CODE_OBJ_PRIS)
            ->orderBy('files.id')
            //->take(50)
            ->get();
//        $files = DB::select("
//            select files.*
//              from files
//        inner join pris on pris.id = files.id_object
//             where file_text is null
//               and pris.deleted_at is null
//               and code_object = 5
//               and files.deleted_at is null
//          order by files.id asc
//             limit 5
//        ");
        foreach ($files as $file) {
            $ocr = new FileOcr($file);
            $ocr->extractText();
            $this->info("Text updated for File ID $file->id, File path: ".Storage::disk('public_uploads')->path($file->path));
        }

        return Command::SUCCESS;
    }
}

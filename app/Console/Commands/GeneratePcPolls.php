<?php

namespace App\Console\Commands;

use App\Enums\DocTypesEnum;
use App\Exports\CommentsExport;
use App\Models\Consultations\PublicConsultation;
use App\Models\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use function Clue\StreamFilter\fun;

class GeneratePcPolls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:pc_polls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate polls report after consultation ends';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get ended public consultation with comments and where do not have generated documents
        $items = PublicConsultation::with(['polls', 'polls.questions', 'polls.questions', 'polls.entries'])
            ->whereHas('polls', function ($q){
                $q->whereHas('entries');
            })
            ->whereDoesntHave('pollsDocuments')
            ->Ended()
            ->limit(10)
            ->get();

        //generate pdf with all comments
        if($items->count()) {
            foreach ($items as $pc) {
                $exportData = [
                    'title' => 'Анкети към обществена консултация '.$pc->title,
                    'rows' => $pc->polls
                ];

                $path = File::PUBLIC_CONSULTATIONS_POLLS_UPLOAD_DIR.$pc->id.DIRECTORY_SEPARATOR;
                $fileName = 'polls_'.Carbon::now()->format('Y_m_d_H_i_s');

                $pdf = PDF::loadView('exports.pc_all_poll', ['data' => $exportData, 'isPdf' => true]);
                Storage::disk('public_uploads')->put($path.$fileName.'.pdf', $pdf->output());
                //Attach files to public consultation
                foreach (config('available_languages') as $lang) {
                    $pdf = new File([
                        'id_object' => $pc->id,
                        'code_object' => File::CODE_OBJ_PUBLIC_CONSULTATION,
                        'doc_type' => DocTypesEnum::PC_POLLS_PDF->value,
                        'filename' => $fileName.'.pdf',
                        'content_type' => 'application/pdf',
                        'path' => $path.$fileName.'.pdf',
                        'description_'.$lang['code'] => trans('custom.public_consultation.doc_type.'.DocTypesEnum::PC_POLLS_PDF->value, [], $lang['code']),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'locale' => $lang['code'],
                        'version' => '1.0',
                    ]);
                    $pdf->save();
                }
                $this->comment('Polls for '.$pc->id.' are ready.');
            }
        } else{
            $this->comment('No ended consultations.');
        }
    }
}

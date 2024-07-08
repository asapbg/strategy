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

class GenerateComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:comments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate comments report after consultation ends';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get ended public consultation with comments and where do not have generated documents
        $items = PublicConsultation::with(['comments', 'comments.author'])
            ->whereHas('comments')
            ->whereDoesntHave('commentsDocuments')
            ->whereNull('old_id')
            ->Ended()
            ->get();
        //Foreach generate csv and pdf with all comments
        if($items->count()) {
            foreach ($items as $pc) {
                $exportData = [
                    'title' => 'Коментари към обществена консултация '.$pc->title,
                    'rows' => $pc->comments
                ];

                $path = File::PUBLIC_CONSULTATIONS_COMMENTS_UPLOAD_DIR.$pc->id.DIRECTORY_SEPARATOR;
                $fileName = 'comments_'.Carbon::now()->format('Y_m_d_H_i_s');
                //generate csv
                Excel::store(new CommentsExport($exportData), $path.$fileName.'.csv', 'public_uploads');

                //Attach files to public consultation
                foreach (config('available_languages') as $lang) {
                    $csv = new File([
                        'id_object' => $pc->id,
                        'code_object' => File::CODE_OBJ_PUBLIC_CONSULTATION,
                        'doc_type' => DocTypesEnum::PC_COMMENTS_CSV,
                        'filename' => $fileName.'.csv',
                        'content_type' => 'text/csv',
                        'path' => $path.$fileName.'.csv',
                        'description_'.$lang['code'] => trans('custom.public_consultation.doc_type.'.DocTypesEnum::PC_COMMENTS_CSV->value, [], $lang['code']),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'locale' => $lang['code'],
                        'version' => '1.0',
                    ]);
                    $csv->save();
                }

                $pdf = PDF::loadView('exports.comments', ['data' => $exportData, 'isPdf' => true]);
                Storage::disk('public_uploads')->put($path.$fileName.'.pdf', $pdf->output());

                //Attach files to public consultation
                foreach (config('available_languages') as $lang) {
                    $pdf = new File([
                        'id_object' => $pc->id,
                        'code_object' => File::CODE_OBJ_PUBLIC_CONSULTATION,
                        'doc_type' => DocTypesEnum::PC_COMMENTS_PDF,
                        'filename' => $fileName.'.pdf',
                        'content_type' => 'application/pdf',
                        'path' => $path.$fileName.'.pdf',
                        'description_'.$lang['code'] => trans('custom.public_consultation.doc_type.'.DocTypesEnum::PC_COMMENTS_PDF->value, [], $lang['code']),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'locale' => $lang['code'],
                        'version' => '1.0',
                    ]);
                    $pdf->save();
                }
                $this->comment('Comments for '.$pc->id.' are ready.');
            }
        } else{
            $this->comment('No ended consultations.');
        }
    }
}

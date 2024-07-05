<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PublicConsultationEnd extends Mailable
{
    use Queueable, SerializesModels;

    public $pc;
    public $user;

    public function __construct($pc)
    {
        $this->pc = $pc;
        $this->user = $pc->author;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = config('mail.from.address');
        $url = route('admin.consultations.public_consultations.edit', $this->pc);

        $commentsPdf = $this->pc->commentsDocumentPdf();
        $commentsCsv = $this->pc->commentsDocumentCsv();
        $pollsPdf = $this->pc->pollsDocumentPdf();
        if($commentsPdf){
            $this->attach(asset('files'.$commentsPdf->path));
        }
        if($commentsCsv){
            $this->attach(asset('files'.$commentsCsv->path));
        }
        if($pollsPdf){
            $this->attach(asset('files'.$pollsPdf->path));
        }
        return $this->from($from, config('mail.from.name'))
                    ->subject('Изтекъл срок на Обществена консултация')
                    ->markdown('emails.end_pc', ['url' => $url, 'pc' => $this->pc, 'user' => $this->user ]);
    }


}

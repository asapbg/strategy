<?php

namespace App\Services;

use App\Models\File;
use App\Models\StrategicDocumentFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;

class FileOcr
{
    private File|StrategicDocumentFile $file;
    private string $pdf_to_text_env_path;
    private string $doc_to_text_env_path;

    public function __construct(File|StrategicDocumentFile $file)
    {
        $this->file = $file;
        $this->pdf_to_text_env_path = config('file_to_text.pdf_env_path') ?? '/usr/bin/pdftotext';
        $this->doc_to_text_env_path = config('file_to_text.doc_env_path') ?? '/usr/sbin/antiword';
        $this->doc_to_docx_env_path = config('file_to_text.doc_to_docx_env_path') ?? '/usr/bin/soffice';
    }

    public function extractText(): bool
    {
        $extracted = false;
        switch ($this->file->content_type)
        {
            case 'application/pdf':
            case 'text/rtf':
                $this->pdfExtract();
                $extracted = true;
                break;
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $this->docExtract();
                $extracted = true;
                break;
        }
        return $extracted;
    }

    /**
     * @return void
     */
    private function pdfExtract()
    {
        try {
            $file_path = Storage::disk('public_uploads')->path($this->file->path);
            if (file_exists($file_path)) {
                if ($this->file->content_type == 'text/rtf') {
                    $output_dir = str_replace(DIRECTORY_SEPARATOR."{$this->file->filename}", '', $file_path);
                    $command = escapeshellarg($this->doc_to_docx_env_path).' --headless --invisible --norestore --convert-to pdf --outdir ' . escapeshellarg($output_dir) . ' ' . escapeshellarg($file_path);
                    //dd($command);
                    //shell_exec($command);
                    $res = shell_exec($command. ' 2>&1');
                    Log::error("Command for conversion $command, result: $res");
                    $delete_after_conversion = true;
                    $file_path = str_contains($file_path, 'doc')
                        ? str_replace("doc", 'pdf', $file_path)
                        : str_replace("rtf", 'pdf', $file_path);
                }
                $text = (new Pdf($this->pdf_to_text_env_path))
                    ->setPdf($file_path)
                    ->setOptions(["-enc UTF-8"])
                    ->text();
                if (empty($text)) {
                    $text = null;
                }
                $this->file->file_text = $text;
                $this->file->save();
                if (isset($delete_after_conversion) && file_exists($file_path) && str_contains($file_path, '.pdf')) {
                    //unlink($file_path);
                }
            }
        } catch (\Exception $e) {
            logError('PDF to text file: '.$this->file->path, $e->getMessage());
        }
    }

    /**
     * @return void
     */
    private function docExtract()
    {
        try {
            if (Storage::disk('public_uploads')->exists($this->file->path)) {
                $file_path = Storage::disk('public_uploads')->path($this->file->path);
                $file = escapeshellarg($file_path);
                if ($this->file->content_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                    $output_dir = str_replace(DIRECTORY_SEPARATOR . "{$this->file->filename}", '', $file);
                    $command = escapeshellarg($this->doc_to_docx_env_path) . ' --headless --invisible --norestore --convert-to doc --outdir ' . $output_dir . ' ' . $file;
                    //shell_exec($command);
                    $res = shell_exec($command. ' 2>&1');
                    Log::error("Command for conversion $command, result: $res");
                    $file = str_replace("docx", 'doc', $file);
                    $conversion_file_path = str_replace("docx", 'doc', $file_path);
                }
                $text = shell_exec($this->doc_to_text_env_path . ' -m UTF-8 -w 0 ' . $file);
                //$text = shell_exec($this->doc_to_text_env_path.' -m UTF-8 -w 0 '.$file. ' 2>&1');
                //Log::error("Result from reading text: $text");
                $clearText = html_entity_decode(trim($text));
                if (empty($clearText)) {
                    $clearText = null;
                }
                $this->file->file_text = mb_convert_encoding($clearText, mb_detect_encoding($clearText), 'UTF-8');
                $this->file->save();
                if (isset($conversion_file_path) && file_exists($conversion_file_path)) {
                    //unlink($conversion_file_path);
                }
            }
        } catch (\Exception $e) {
            logError('DOC to text file: '.$this->file->path, $e->getMessage());
        }
    }
}

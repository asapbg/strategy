<?php

namespace App\Services\StrategicDocuments;

use App\Models\File as FileModel;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
use App\Services\FileOcr;
use ArrayAccess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FileService
{
    /**
     * @param Request $request
     * @param StrategicDocument $strategicDocument
     * @return void
     * @throws \Exception
     */
    public function uploadFiles($validated, StrategicDocument $strategicDocument, ?StrategicDocumentFile $strategicDocumentFile,bool $isMain = false)
    {
        $bgFileId = null;
        foreach (['en', 'bg'] as $locale) {
            try {
                DB::beginTransaction();
                $strategicDocumentFileTypeId = Arr::get($validated, 'strategic_document_type_file_id');
                if ($strategicDocumentFileTypeId) {
                    $validated['strategic_document_type_id'] = $strategicDocumentFileTypeId;
                }
                if ($isMain) {
                    $mainFile = $strategicDocument->files->where('is_main', true)->where('locale', $locale)->first();
                    if ($mainFile) {
                        $mainFile->delete();
                    }
                }
                $file = $strategicDocumentFile ? $strategicDocumentFile->replicate() : new StrategicDocumentFile();

                if ($strategicDocumentFile) {
                    $file->strategic_document_file_id = $strategicDocumentFile->strategic_document_file_id ?? $strategicDocumentFile->id;
                }
                $fillable = $this->getFillableValidated($validated, $file);
                $file->fill($fillable);

                $uploadedFile = Arr::get($validated, 'file_strategic_documents_' . $locale);
                $enFile = $validated['file_strategic_documents_en'] ?? null;

                if ($locale === 'en') {
                    if (!$enFile) {
                        $validated['file_strategic_documents_en'] = $validated['file_strategic_documents_bg'];
                        $displayNames = [
                            'bg' => Arr::get($validated, 'display_name_bg'),
                            'en' => Arr::get($validated, 'display_name_en'),
                        ];
                        // check if to take display name in bg
                        $validated['display_name_en'] = $displayNames[$locale] ?? $displayNames['bg'];
                        //dd($validated);
                        $uploadedFile = Arr::get($validated, 'file_strategic_documents_bg');//$request->file('file_strategic_documents_bg');
                    }
                }

                $fileNameToStore = round(microtime(true)).'.'.$uploadedFile->getClientOriginalExtension();
                $uploadedFile->storeAs(StrategicDocumentFile::DIR_PATH, $fileNameToStore, 'public_uploads');
                $version = $file->version;
                $newVersion = ($version + 1);

                $file->content_type = $uploadedFile->getClientMimeType();
                $file->path = StrategicDocumentFile::DIR_PATH.$fileNameToStore;
                $file->sys_user = request()->user()->id;
                $file->filename = $fileNameToStore;
                $file->parent_id = Arr::get($validated, 'parent_id');
                $file->locale = $locale;
                $file->is_main = $isMain;
                $file->version = $newVersion.'.0';
                $file->strategic_document_id = $strategicDocument->id;
                $file->save();

                $strategicDocument->files()->save($file);
                $this->storeTranslateOrNew(StrategicDocumentFile::TRANSLATABLE_FIELDS, $file, $validated);
                $ocr = new FileOcr($file->refresh());
                $ocr->extractText();
                if ($locale === 'bg') {
                    $file->save();
                    $bgFileId = $file->id;
                } else {
                    $file->parent_lang_id = $bgFileId;
                    $file->save();
                }

                DB::commit();
            } catch (\Throwable $throwable) {
                Log::error('Upload file to strategic document ID('.$strategicDocument->id.'): '.$throwable);
                DB::rollBack();
                throw new \Exception($throwable);
            }
        }
    }

    /**
     * @param $validated
     * @param StrategicDocumentFile $strategicDocumentFile
     * @return void
     * @throws \Exception
     */
    public function updateFile($validated, StrategicDocumentFile $strategicDocumentFile): void
    {
        try {
            $strategicDocument = $strategicDocumentFile->strategicDocument;
            DB::beginTransaction();
            $newVersion = $strategicDocumentFile->replicate();
            $version = $strategicDocumentFile->version + 1;
            $uploadedFile = Arr::get($validated, 'file_strategic_documents_bg');
            $fileNameToStore = round(microtime(true)).'.'.$uploadedFile->getClientOriginalExtension();
            $uploadedFile->storeAs(StrategicDocumentFile::DIR_PATH, $fileNameToStore, 'public_uploads');
            $newVersion->content_type = $uploadedFile->getClientMimeType();
            $newVersion->path = StrategicDocumentFile::DIR_PATH.$fileNameToStore;
            $newVersion->version = $version.'.0';
            $newVersion->filename = $fileNameToStore;
            $newVersion->strategic_document_file_id = $strategicDocumentFile->strategic_document_file_id ?? $strategicDocumentFile->id;
            $strategicDocument->files()->save($newVersion);
            $ocr = new FileOcr($newVersion->refresh());
            $ocr->extractText();
            $newVersion->save();
            // check this one

            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error('Upload/Update file to strategic file ID('.$strategicDocumentFile->id.'): '.$throwable);
            throw new \Exception($throwable);
        }
    }

    protected function getFillableValidated($validated, $item)
    {
        $modelFillable = $item->getFillable();
        $validatedFillable = $validated;
        foreach ($validatedFillable as $field => $value) {
            if( !in_array($field, $modelFillable) ) {
                unset($validatedFillable[$field]);
            }
        }
        return $validatedFillable;
    }

    protected function storeTranslateOrNew($fields, $item, $validated)
    {
        foreach (config('available_languages') as $locale) {
            foreach ($fields as $field) {
                $fieldName = $field."_".$locale['code'];
//                dd($fields, $field, $fieldName, $validated);
                if(array_key_exists($fieldName, $validated)) {
                    $item->translateOrNew($locale['code'])->{$field} = $validated[$fieldName];
                }
            }
        }

        $item->save();
    }

    /**
     * @param $strategicDocumentFiles
     * @param $adminView
     * @return array
     */
    public function prepareFileData($strategicDocumentFiles, $adminView = true): array
    {
        $mainFile = $strategicDocumentFiles->where('is_main', 1)->first();
        $fileData = [];
        if ($strategicDocumentFiles->isEmpty() || !$mainFile) {
            return [];
        }

        $iconMapping = [
            //
            'application/pdf' => 'fas fa-file-pdf text-danger me-1',
            'application/msword' => 'fas fa-file-word text-info me-1',
            'application/vnd.ms-excel' => 'fas fa-file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fas fa-file-excel',
        ];
        $iconMappingFrontEnd = [
            'application/pdf' => 'fa-regular fa-file-pdf main-color me-2 fs-5',
            'application/msword' => 'fa-regular fa-file-word main-color me-2 fs-5',
            'application/vnd.ms-excel' => 'fa-regular fa-file-excel main-color me-2 fs-5',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa-regular fa-file-excel main-color me-2 fs-5',
        ];
        if ($adminView == false) {
            $iconMapping = $iconMappingFrontEnd;
        }

        $fileExtension = $mainFile->content_type;
        $iconClass = $iconMapping[$fileExtension] ?? 'fas fa-file';
        $editLink = $adminView ? "<a href='#' id='editButton_{$mainFile->id}' class='edit-button' data-file-id='{$mainFile->id}'><i class='fas fa-edit'></i></a>" : '';
        $downloadLink = $adminView ? "<a href='#' id='downloadButton_{$mainFile->id}' class='download-button'><i class='fas fa-download'></i></a>" : '';
        $deleteLink = $adminView ? "<a href='#' id='deleteButton_{$mainFile->id}' class='delete-button' data-file-id='{$mainFile->id}'><i class='fas fa-trash'></i></a>" : '';

        $rootNode = [
            'id' => $mainFile->id,
            'parent' => '#',
            'text' => $mainFile->document_display_name .
                $editLink . $downloadLink,
            'icon' => $iconClass,
        ];
        $fileData[] = $rootNode;
        $processedFileIds = [];
        foreach ($strategicDocumentFiles as $file) {
            if ($file->is_main) {
                continue;
            }

            $latestVersion = $file->latestVersion;

            if ($latestVersion && $file->id !== $latestVersion->id) {
                $currentFile = $latestVersion;
            } else {
                $currentFile = $file;
            }

            if (in_array($currentFile->strategic_document_file_id, $processedFileIds) && $currentFile->strategic_document_file_id) {
                continue;
            }

            $processedFileIds[] = $currentFile->strategic_document_file_id;
            $fileExtension = $currentFile->content_type;
            $iconClass = $iconMapping[$fileExtension] ?? 'fas fa-file';

            $fileNode = [
                'id' => $currentFile->id,
                'parent' => $currentFile->parent_id ?: $mainFile->id,
                'text' => $currentFile->version . $currentFile->document_display_name .
                    $editLink . $downloadLink . $deleteLink,
                'icon' => $iconClass,
                'ord' => $currentFile->ord,
            ];
            $fileData[] = $fileNode;
        }

        return collect($fileData)->sortBy('ord')->values()->all();
    }

    /**
     * @param StrategicDocumentFile $strategicDocumentFile
     * @return string
     */
    private function prepareValidAtText(StrategicDocumentFile $strategicDocumentFile): string
    {
        $validToTexts = [
            'en' => 'Valid to(year):',
            'bg' => 'Валиден до(година):',
        ];

        $indefiniteToTexts = [
            'en' => 'Indefinite',
            'bg' => 'Безсрочен',
        ];

        $year = Carbon::createFromDate($strategicDocumentFile->valid_at)->format('Y');

        return $strategicDocumentFile->valid_at ? Arr::get($validToTexts, app()->getLocale()) . $year ?? 'Валиден до(година):'. $year : Arr::get($indefiniteToTexts, app()->getLocale());
    }

    public function prepareMainFileFields($validated)
    {
        $fieldsToMap = [
            'display_name_main_bg' => 'display_name_bg',
            'display_name_main_en' => 'display_name_en',
            'file_strategic_documents_bg_main' => 'file_strategic_documents_bg',
            'file_strategic_documents_en_main' => 'file_strategic_documents_en',
            'valid_at_main' => 'valid_at',
            'visible_in_report_main' => 'visible_in_report',
        ];

        foreach ($fieldsToMap as $sourceField => $destinationField) {
            if (Arr::get($validated, $sourceField)) {
                $validated[$destinationField] = $validated[$sourceField];
                unset($validated[$sourceField]);
            }
        }

        return $validated;
    }
}

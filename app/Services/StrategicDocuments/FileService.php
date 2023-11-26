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
    public function uploadFiles($validated, StrategicDocument $strategicDocument, bool $isMain = false)
    {
        $bgFileId = null;
        foreach (['en', 'bg'] as $locale) {
            try {
                DB::beginTransaction();

                if ($isMain) {
                    $mainFile = $strategicDocument->files->where('is_main', true)->where('locale', $locale)->first();
                    if ($mainFile) {
                        $mainFile->delete();
                    }
                }
                $file = new StrategicDocumentFile();
                $fillable = $this->getFillableValidated($validated, $file);
                $file->fill($fillable);

                $uploadedFile = Arr::get($validated, 'file_strategic_documents_' . $locale);//$request->file('file_strategic_documents_' . $locale);
                $enFile = $validated['file_strategic_documents_en'] ?? null;

                if ($locale === 'en') {
                    if (!$enFile) {
                        $validated['file_strategic_documents_en'] = $validated['file_strategic_documents_bg'];
                        $displayNames = [
                            'bg' => Arr::get($validated, 'display_name_bg'),
                            'en' => Arr::get($validated, 'display_name_en'),
                        ];
                        $validated['display_name_en'] = $displayNames[$locale] ?? null;
                        $uploadedFile = Arr::get($validated, 'file_strategic_documents_bg');//$request->file('file_strategic_documents_bg');
                    }
                }

                $fileNameToStore = round(microtime(true)).'.'.$uploadedFile->getClientOriginalExtension();
                $uploadedFile->storeAs(StrategicDocumentFile::DIR_PATH, $fileNameToStore, 'public_uploads');

                $file->content_type = $uploadedFile->getClientMimeType();
                $file->path = StrategicDocumentFile::DIR_PATH.$fileNameToStore;
                $file->sys_user = request()->user()->id;
                $file->filename = $fileNameToStore;
                $file->parent_id = Arr::get($validated, 'parent_id');
                $file->locale = $locale;
                $file->is_main = $isMain;
                $strategicDocument->files()->save($file);

                if ($locale === 'bg') {
                    $file->save();
                    $bgFileId = $file->id;
                } else {
                    $file->parent_lang_id = $bgFileId;
                    $file->save();
                }

                $this->storeTranslateOrNew(StrategicDocumentFile::TRANSLATABLE_FIELDS, $file, $validated);
                DB::commit();
            } catch (\Throwable $throwable) {
                Log::error('Upload file to strategic document ID('.$strategicDocument->id.'): '.$throwable);
                DB::rollBack();
                throw new \Exception($throwable);
            }
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
        $validAt = $this->prepareValidAtText($mainFile);
        $editLink = $adminView ? "<a href='#' id='editButton_{$mainFile->id}' class='edit-button' data-file-id='{$mainFile->id}'><i class='fas fa-edit'></i></a>" : '';
        $downloadLink = $adminView ? "<a href='#' id='downloadButton_{$mainFile->id}' class='download-button'><i class='fas fa-download'></i></a>" : '';
        $deleteLink = $adminView ? "<a href='#' id='deleteButton_{$mainFile->id}' class='delete-button' data-file-id='{$mainFile->id}'><i class='fas fa-trash'></i></a>" : '';

        $rootNode = [
            'id' => $mainFile->id,
            'parent' => '#',
            'text' => $mainFile->display_name . ' ' . $validAt .
                //"<a href='#' id='editButton_{$mainFile->id}' class='edit-button' data-file-id='{$mainFile->id}'><i class='fas fa-edit'></i></a>" .
                //"<a href='#' id='downloadButton_{$mainFile->id}' class='download-button'><i class='fas fa-download'></i></a>",
                //"<a href='#' id='deleteButton_{$mainFile->id}' class='delete-button' data-file-id='{$mainFile->id}'><i class='fas fa-trash'></i></a>",
                $editLink . $downloadLink,
            'icon' => $iconClass,
        ];
        $fileData[] = $rootNode;

        foreach ($strategicDocumentFiles as $file) {
            if ($file->is_main) {
                continue;
            }
            $fileExtension = $file->content_type;
            $iconClass = $iconMapping[$fileExtension] ?? 'fas fa-file';
            $validAt = $this->prepareValidAtText($file);

            $fileNode = [
                'id' => $file->id,
                'parent' => $file->parent_id ?: $mainFile->id,
                'text' => $file->display_name . ' ' . $validAt .
                    $editLink . $downloadLink . $deleteLink,
                    //"<a href='#' id='editButton_{$file->id}' class='edit-button' data-file-id='{$file->id}'><i class='fas fa-edit'></i></a>" .
                    //"<a href='#' id='downloadButton_{$file->id}' class='download-button'><i class='fas fa-download'></i></a>" .
                    //"<a href='#' id='deleteButton_{$file->id}' class='delete-button' data-file-id='{$file->id}'><i class='fas fa-trash'></i></a>",
                'icon' => $iconClass,
            ];

            $fileData[] = $fileNode;
        }

        return $fileData;
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
        if (Arr::get($validated, 'display_name_main_bg')) {
            $validated['display_name_bg'] = $validated['display_name_main_bg'];
            unset($validated['display_name_main_bg']);
        }
        if (Arr::get($validated, 'display_name_main_en')) {
            $validated['display_name_en'] = $validated['display_name_main_en'];
            unset($validated['display_name_main_en']);
        }
        if (Arr::get($validated, 'file_strategic_documents_bg_main')) {
            $validated['file_strategic_documents_bg'] = $validated['file_strategic_documents_bg_main'];
            unset($validated['file_strategic_documents_bg_main']);
        }
        if (Arr::get($validated, 'file_strategic_documents_en_main')) {
            $validated['file_strategic_documents_en'] = $validated['file_strategic_documents_en_main'];
            unset($validated['file_strategic_documents_en_main']);
        }
        if (Arr::get($validated, 'valid_at_main')) {
            $validated['valid_at'] = $validated['valid_at_main'];
        }

        return $validated;
    }
}

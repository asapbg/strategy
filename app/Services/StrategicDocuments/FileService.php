<?php

namespace App\Services\StrategicDocuments;

use App\Models\File as FileModel;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentFile;
use App\Services\FileOcr;
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
    public function uploadFiles(Request $request, StrategicDocument $strategicDocument, bool $isMain = false)
    {
        $validated = $request->validated();
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

                $uploadedFile = $request->file('file_strategic_documents_' . $locale);
                $enFile = $validated['file_strategic_documents_en'] ?? null;

                if ($locale === 'en') {
                    if (!$enFile) {
                        $validated['file_strategic_documents_en'] = $validated['file_strategic_documents_bg'];
                        $displayNames = [
                            'bg' => $request->get('display_name_bg'),
                            'en' => $request->get('display_name_en'),
                        ];
                        $validated['display_name_en'] = $displayNames[$locale] ?? null;
                        $uploadedFile = $request->file('file_strategic_documents_bg');
                    }
                }

                $fileNameToStore = round(microtime(true)).'.'.$uploadedFile->getClientOriginalExtension();
                $uploadedFile->storeAs(StrategicDocumentFile::DIR_PATH, $fileNameToStore, 'public_uploads');

                $file->content_type = $uploadedFile->getClientMimeType();
                $file->path = StrategicDocumentFile::DIR_PATH.$fileNameToStore;
                $file->sys_user = $request->user()->id;
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
            'application/pdf' => 'fas fa-file-pdf text-danger me-1',
            'application/msword' => 'fas fa-file-word text-info me-1',
            'application/vnd.ms-excel' => 'fas fa-file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fas fa-file-excel',
        ];
        $fileExtension = $mainFile->content_type;
        $iconClass = $iconMapping[$fileExtension] ?? 'fas fa-file';

        $rootNode = [
            'id' => $mainFile->id,
            'parent' => '#',
            'text' => $mainFile->display_name .
                "<a href='#' id='editButton_{$mainFile->id}' class='edit-button' data-file-id='{$mainFile->id}'><i class='fas fa-edit'></i></a>" .
                "<a href='#' id='downloadButton_{$mainFile->id}' class='download-button'><i class='fas fa-download'></i></a>",
                //"<a href='#' id='deleteButton_{$mainFile->id}' class='delete-button' data-file-id='{$mainFile->id}'><i class='fas fa-trash'></i></a>",
            'icon' => $iconClass,
        ];

        $fileData[] = $rootNode;

        foreach ($strategicDocumentFiles as $file) {
            if ($file->is_main) {
                continue;
            }
            $fileExtension = $file->content_type;
            $iconClass = $iconMapping[$fileExtension] ?? 'fas fa-file';

            $fileNode = [
                'id' => $file->id,
                'parent' => $file->parent_id ?: $mainFile->id,//'root',
                'text' => $file->display_name .
                    "<a href='#' id='editButton_{$file->id}' class='edit-button' data-file-id='{$file->id}'><i class='fas fa-edit'></i></a>" .
                    "<a href='#' id='downloadButton_{$file->id}' class='download-button'><i class='fas fa-download'></i></a>" .
                    "<a href='#' id='deleteButton_{$file->id}' class='delete-button' data-file-id='{$file->id}'><i class='fas fa-trash'></i></a>",
                'icon' => $iconClass,
            ];

            $fileData[] = $fileNode;
        }

        return $fileData;

        $fileData = [];
        if ($adminView) {
            $rootNode = [
                'id' => 'root',
                'parent' => '#',
                'text' => 'Файлова йерархия',
                'icon' => 'fas fa-folder'
            ];

            $fileData[] = $rootNode;
        }

        foreach ($strategicDocumentFiles as $file) {
            $iconMapping = [
                'application/pdf' => 'fas fa-file-pdf text-danger me-1',
                'application/msword' => 'fas fa-file-word text-info me-1',
                'application/vnd.ms-excel' => 'fas fa-file-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fas fa-file-excel',
            ];

            $fileExtension = $file->content_type;
            $iconClass = $iconMapping[$fileExtension] ?? 'fas fa-file';
            $parentNode = $adminView ? $file->parent_id ?? 'root' : $file->parent_id ?? '#';


            $fileNode = [
                'id' => $file->id,
                'parent' => $parentNode,//$file->parent_id ?: 'root',
                'text' => $file->display_name,
                'icon' => $iconClass,
            ];

            $fileData[] = $fileNode;
        }

        return $fileData;
    }
}

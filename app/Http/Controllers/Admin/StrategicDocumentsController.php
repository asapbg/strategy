<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreStrategicDocumentRequest;
use App\Http\Requests\StrategicDocumentFileUploadRequest;
use App\Models\Consultations\PublicConsultation;
use App\Models\LegalActType;
use App\Models\PolicyArea;
use App\Models\Pris;
use App\Models\StrategicDocument;
use App\Models\AuthorityAcceptingStrategic;
use App\Models\StrategicActType;
use App\Models\StrategicDocumentFile;
use App\Models\StrategicDocumentLevel;
use App\Models\StrategicDocumentType;
use App\Services\FileOcr;
use App\Services\StrategicDocuments\FileService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\File as FileModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class StrategicDocumentsController extends AdminController
{
    const LIST_ROUTE = 'admin.strategic_documents.index';
    const EDIT_ROUTE = 'admin.strategic_documents.edit';
    const STORE_ROUTE = 'admin.strategic_documents.store';
    const DELETE_ROUTE = 'admin.strategic_documents.delete';
    const LIST_VIEW = 'admin.strategic_documents.index';
    const EDIT_VIEW = 'admin.strategic_documents.edit';

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? StrategicDocument::PAGINATE;

        $items = StrategicDocument::with(['translation', 'documentLevel', 'documentLevel.translation',
            'documentType', 'documentType.translation',
            'acceptActInstitution', 'acceptActInstitution.translation',
            'files', 'files.translation', 'files.documentType', 'files.documentType.translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);

        $toggleBooleanModel = 'StrategicDocument';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param int $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id = 0)
    {
        $item = $this->getRecord($id, ['translation']);
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', StrategicDocument::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = StrategicDocument::translationFieldsProperties();
        $strategicDocumentLevels = StrategicDocumentLevel::all();
        $strategicDocumentTypes = StrategicDocumentType::all();
        $strategicActTypes = StrategicActType::all();
        $authoritiesAcceptingStrategic = AuthorityAcceptingStrategic::all();
        $policyAreas = PolicyArea::all();
        $prisActs = Pris::all();
        $strategicDocumentFiles = StrategicDocumentFile::all();
        $strategicDocumentFilesBg = StrategicDocumentFile::where('strategic_document_id', $item->id)->where('locale', 'bg')->get();
        $strategicDocumentFilesEn = StrategicDocumentFile::where('strategic_document_id', $item->id)->where('locale', 'en')->get();

        $strategicDocumentsFileService = app(FileService::class);

        $fileData = $strategicDocumentsFileService->prepareFileData($strategicDocumentFilesBg);
        $fileDataEn = $strategicDocumentsFileService->prepareFileData($strategicDocumentFilesEn);
        $legalActTypes = LegalActType::all();
        $consultations = PublicConsultation::Active()->get()->pluck('title', 'id');
        $documentDate = $item->pris?->document_date ? $item->pris?->document_date : $item->document_date;
        $mainFile = $strategicDocumentFilesBg->where('is_main', true)->first();
        $mainFiles = $item->files->where('is_main', true);

        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields',
            'strategicDocumentLevels', 'strategicDocumentTypes', 'strategicActTypes', 'authoritiesAcceptingStrategic',
            'policyAreas', 'prisActs', 'consultations', 'strategicDocumentFiles', 'fileData', 'fileDataEn', 'legalActTypes', 'documentDate', 'mainFile', 'mainFiles'));
    }

    public function store(StoreStrategicDocumentRequest $request)
    {
        $validated = $request->validated();
        $id = $validated['id'];
        $stay = isset($validated['stay']) ?? 0;
        $item = $id ? $this->getRecord($id) : new StrategicDocument();

        if( ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', StrategicDocument::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            DB::beginTransaction();
            if( $validated['accept_act_institution_type_id'] == AuthorityAcceptingStrategic::COUNCIL_MINISTERS ) {
                $validated['strategic_act_number'] = null;
                $validated['strategic_act_link'] = null;
                $validated['document_date'] = null;

                $datesToBeParsedToCarbon = [
                    'document_date_accepted',
                    'document_date_expiring',
                    'document_date',
                ];

                foreach ($datesToBeParsedToCarbon as $date) {
                    if (array_key_exists($date, $validated)) {
                        $validated[$date] = Carbon::parse($validated[$date]);
                    }
                }

            } else {
                $validated['pris_act_id'] = null;
            }

            $fillable = $this->getFillableValidated($validated, $item);

            $item->fill($fillable);

            $item->save();
            $this->storeTranslateOrNew(StrategicDocument::TRANSLATABLE_FIELDS, $item, $validated);
            try {
                $fileService = app(FileService::class);
                $validated = $fileService->prepareMainFileFields($validated);

                $bgFile = Arr::get($validated, 'file_strategic_documents_bg');
                $enFile =  Arr::get($validated, 'file_strategic_documents_en');

                if ($bgFile || $enFile) {
                    $fileService->uploadFiles($validated, $item, true);
                } else {
                    $locale = app()->getLocale();
                    $mainFile = $item->files->where('is_main', true)->where('locale', $locale)->first();
                    if ($mainFile) {
                        $validated = $fileService->prepareMainFileFields($validated);
                        $this->storeTranslateOrNew(StrategicDocumentFile::TRANSLATABLE_FIELDS, $mainFile, $validated);
                    }
                }
            } catch (\Throwable $throwable) {
                return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
            }

            DB::commit();

            if( $stay ) {
                return redirect(route(self::EDIT_ROUTE, ['id' => $item->id]))
                    ->with('success', trans_choice('custom.strategic_documents', 1)." ".($id ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
            }
            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.strategic_documents', 1)." ".($id ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create/Update strategic document ID('.$id.'): '.$e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function delete(int $id)
    {
        try {
            $item = $this->getRecord($id);
            if ($item && request()->user()->cannot('update', $item)) {
                return back()->with('warning', __('messages.unauthorized'));
            }
            /*
             * check if delete files is needed
            foreach ($item->files as $file) {
                $filePath = public_path('files/' . $file->path);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $file->delete();
            }
            */
            $item->delete();
            $item->documentLevel()->delete();
            $item->acceptActInstitution()->delete();
            $item->documentType()->delete();

            $item->delete();
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error('Delete strategic document ID('.$id.'): '.$throwable);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function uploadDcoFile(StrategicDocumentFileUploadRequest $request)
    {
        $validated = $request->validated();
        $strategicDoc = $this->getRecord($validated['id']);
        unset($validated['id']);
        if( $request->user()->cannot('update', $strategicDoc)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $bgFile = $validated['file_strategic_documents_bg'] ?? null;
            $enFile = $validated['file_strategic_documents_en'] ?? null;
            if (!$bgFile && !$enFile) {
                throw new \Exception('Files not found!');
            }

            $fileService = app(FileService::class);
            $fileService->uploadFiles($validated, $strategicDoc);

            return redirect(route(self::EDIT_ROUTE, ['id' => $strategicDoc->id]))
                ->with('success', trans_choice('custom.strategic_document_files', 1)." ".__('messages.created_successfully_m'));
        } catch (\Throwable $throwable) {
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
        /*
        dd('asdf');
        dd($fileService->uploadFiles($request, $strategicDoc));

        try {
            DB::beginTransaction();
            $validated['strategic_document_type_id'] = $validated['strategic_document_type'];
            unset($validated['strategic_document_type']);
            $file = new StrategicDocumentFile();

            $fillable = $this->getFillableValidated($validated, $file);
            $file->fill($fillable);

            $uploadedFile = $validated['file'];
            $fileNameToStore = round(microtime(true)).'.'.$uploadedFile->getClientOriginalExtension();
            $uploadedFile->storeAs(StrategicDocumentFile::DIR_PATH, $fileNameToStore, 'public_uploads');

            $file->content_type = $uploadedFile->getClientMimeType();
            $file->path = StrategicDocumentFile::DIR_PATH.$fileNameToStore;
            $file->sys_user = $request->user()->id;
            $file->filename = $fileNameToStore;
            $file->parent_id = Arr::get($validated, 'parent_id');
            $strategicDoc->files()->save($file);

            $this->storeTranslateOrNew(StrategicDocumentFile::TRANSLATABLE_FIELDS, $file, $validated);
            DB::commit();
            return redirect(route(self::EDIT_ROUTE, ['id' => $strategicDoc->id]))
                ->with('success', trans_choice('custom.strategic_document_files', 1)." ".__('messages.created_successfully_m'));
        } catch (\Exception $e){
            DB::rollBack();
            Log::error('Upload file to strategic document ID('.$strategicDoc->id.'): '.$e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
        */
    }

    public function updateDcoFile(Request $request, $id)
    {
        $rules = [
            'id' => ['required', 'numeric', 'exists:strategic_document_file,id'],
        ];
        $fields = StrategicDocumentFile::translationFieldsProperties();
        unset($fields['display_name']);
        foreach ($fields as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }

        $validator = Validator::make($request->all(), $rules);

        if( $validator->fails() ) {
            return back()->withErrors(['error_'.$id => $validator->errors()->first()]);
        }

        $validated = $validator->validated();
        $file = StrategicDocumentFile::find($validated['id']);

        if( $request->user()->cannot('update', $file->strategicDocument)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $this->storeTranslateOrNew(StrategicDocumentFile::TRANSLATABLE_FIELDS, $file, $validated);
            DB::commit();
            return redirect(route(self::EDIT_ROUTE, ['id' => $file->strategic_document_id]))
                ->with('success', trans_choice('custom.strategic_document_files', 1)." ".__('messages.updated_successfully_m'));
        } catch (\Exception $e){
            DB::rollBack();
            Log::error('Update strategic document file ID('.$file->id.'): '.$e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function downloadDocFile(StrategicDocumentFile $file)
    {
        if (Storage::disk('public_uploads')->has($file->path)) {
            return Storage::disk('public_uploads')->download($file->path, $file->filename);
        } else {
            return back()->with('warning', __('messages.record_not_found'));
        }
    }

    public function deleteDocFile(Request $request, StrategicDocumentFile $file)
    {
        if( $request->user()->cannot('update', $file->strategicDocument)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        try {
            $file->delete();
            return redirect(route(self::EDIT_ROUTE, ['id' => $file->strategic_document_id]))
                ->with('success', trans_choice('custom.strategic_document_files', 1)." ".__('messages.deleted_successfully_m'));
        } catch (\Exception $e) {
            Log::error('Delete Strategic doc file ID('.$file->id.'): '.$e);
            return redirect(route(self::EDIT_ROUTE, ['id' => $file->strategic_document_id]))
                ->with('danger', __('messages.system_error'));
        }
    }

    private function filters($request)
    {
        return array(
            'title' => array(
                'type' => 'text',
                'placeholder' => __('validation.attributes.title'),
                'value' => $request->input('title'),
                'col' => 'col-md-4'
            ),
            'category' => array(
                'type' => 'select',
                'value' => $request->input('category'),
                'options' => StrategicDocumentLevel::all()->map(function($item) {
                    return ['value' => $item->id, 'name' => $item->name];
                }),
                'col' => 'col-md-4'
            ),
        );
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = StrategicDocument::withTrashed();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            return new StrategicDocument();
        }
        return $item;
    }

    /**
     * @param Request $request
     * @return true
     * @throws \Exception
     */
    public function saveFileTree(Request $request)
    {
        try {
            $strategicDocument = StrategicDocument::findOrFail($request->get('strategicDocumentId'));
            $fileStructures = Arr::get($request->get('filesStructure'), '0') ?? [];
            if (isset($fileStructures['children'])) {
                foreach ($fileStructures['children'] as $child) {
                    $currentFile = StrategicDocumentFile::find($child['id']);
                    $currentFile->parent_id = null;
                    $currentFile->save();
                }

                foreach ($fileStructures['children'] as $child) {
                    $parentId = $child['id'];//$fileStructures['id'];
                    $this->processChild($child, $strategicDocument, $parentId);
                }
                return true;
            }
            return true;
        } catch (\Throwable $throwable) {
            Log::warning('Strategic documents save tree: ' . $throwable->getMessage());
            throw new \Exception('Something went wrong while saving the tree');
        }
    }

    /**
     * @param $node
     * @param $strategicDocument
     * @param $parent
     * @return void
     */
    protected function processChild($node, $strategicDocument, $parent)
    {
        $id = Arr::get($node, 'id');
        if ($id === null) {
            return;
        }
        $currentFile = StrategicDocumentFile::find($id);
        if ($parent == 'root') {
            return;
        }
        $parentFile = StrategicDocumentFile::find($parent);

        if (!$parentFile || !$currentFile) {
            return;
        }

        $currentFile->parent_id = $parentFile->id;

        if ($parentFile->id !== $currentFile->id) {
            $currentFile->save();
        }
        if (isset($node['children'])) {
            foreach ($node['children'] as $child) {
                $this->processChild($child, $strategicDocument, $id);
            }
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function prisActOptions($id)
    {
        try {
            $prisOptions = [];
            $prisActs = Pris::where('legal_act_type_id', $id)->get();
            foreach ($prisActs as $prisAct) {
                $prisOptions[] = [
                    'id' => $prisAct->id,
                    'text' => $prisAct->actType->name . ' N' . $prisAct->doc_num . ' ' . $prisAct->doc_date,
                ];
            }

            return response()->json(['prisOptions' => $prisOptions]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }
    }
}

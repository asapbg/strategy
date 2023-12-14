<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreStrategicDocumentRequest;
use App\Http\Requests\StrategicDocumentFileUploadRequest;
use App\Models\Consultations\PublicConsultation;
use App\Models\EkatteArea;
use App\Models\EkatteAreaTranslation;
use App\Models\EkatteMunicipality;
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
use App\Services\StrategicDocuments\CommonService;
use App\Services\StrategicDocuments\FileService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
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
    const PUBLISH_ROUTE = 'admin.strategic_documents.publish';
    const UNPUBLISH_ROUTE = 'admin.strategic_documents.unpublish';
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
        $publishRouteName = self::PUBLISH_ROUTE;
        $unPublishRouteName = self::UNPUBLISH_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName','publishRouteName', 'unPublishRouteName'));
    }

    /**
     * @param Request $request
     * @param int $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function edit(Request $request, $id = 0)
    {
        ini_set('memory_limit', '1024M');
        $currentLocale = app()->getLocale();
        $item = $this->getRecord($id, ['pris.actType','documentType.translations','translation', 'files.parentFile.versions.translations', 'files.translations','files.documentType.translations', 'files.parentFile.versions.user', 'documentType.translations', 'files.parentFile.versions.documentType.translations']);

        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', StrategicDocument::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = StrategicDocument::translationFieldsProperties();

        $strategicDocumentTypes = StrategicDocumentType::with('translations')->get();
        $strategicActTypes = StrategicActType::with('translations')->get();
        $policyAreas = PolicyArea::with('translations')->get();
        $prisActs = Pris::with('translations')->get();
        //$strategicDocumentFiles = StrategicDocumentFile::with('translations')->where('strategic_document_id', $item->id)->get();
        $strategicDocumentFilesBg = StrategicDocumentFile::with(['parentFile', 'translations', 'versions.translations', 'parentFile.versions.translations', 'documentType.translations', 'documentType.translations', 'parentFile.versions.documentType.translations'])->where('strategic_document_id', $item->id)->where('locale', 'bg')->orderBy('ord')->get();
        //$strategicDocumentFilesEn = StrategicDocumentFile::with('translations')->where('strategic_document_id', $item->id)->where('locale', 'en')->orderBy('ord')->get();
        $strategicDocumentsFileService = app(FileService::class);

        $fileData = $strategicDocumentsFileService->prepareFileData($strategicDocumentFilesBg);
        //$fileDataEn = $strategicDocumentsFileService->prepareFileData($strategicDocumentFilesEn);
        $fileDataEn = [];
        $legalActTypes = LegalActType::with('translations')->get();

        //$consultations = PublicConsultation::Active()->get()->pluck('title', 'id');
        $consultations = PublicConsultation::Active()->with('translations')->get();
        $documentDate = $item->pris?->document_date ? $item->pris?->document_date : $item->document_date;
        $mainFile = $strategicDocumentFilesBg->where('is_main', true)->sortByDesc('version')->first();
        $mainFiles = $item->files->where('is_main', true);
        $mainFile = $mainFile->parentFile?->latestVersion ?? $mainFile;
        //$strategicDocuments = StrategicDocument::with('translations')->where('policy_area_id', $item->policy_area_id)->get();
        //$strategicDocuments = collect();
        //$ekateAreas = EkatteArea::with('translations')->where('locale', $currentLocale)->get();
        //
        // testing
        //$strategicDocuments = collect();
        // end testing
        $ekateAreas = EkatteArea::select('ekatte_area.*')->with('translations', function($query) use ($currentLocale) {
            $query->where('locale', $currentLocale);
        })->joinTranslation(EkatteArea::class)->where('locale', $currentLocale);
        $ekateMunicipalities = EkatteMunicipality::select('ekatte_municipality.*')->with('translations', function($query) use ($currentLocale) {
            $query->where('locale', $currentLocale);
        })->joinTranslation(EkatteMunicipality::class)->where('locale', $currentLocale);

        $user = auth()->user();
        $adminUser = $user->hasRole('service_user') || $user->hasRole('super-admin');

        if ($user->hasRole('service_user') || $user->hasRole('super-admin')) {
            //$authoritiesAcceptingStrategic = $item->accept_act_institution_type_id ? AuthorityAcceptingStrategic::with('translations')->where('id', $item->accept_act_institution_type_id)->get() : AuthorityAcceptingStrategic::with('translations')->get();
            $authoritiesAcceptingStrategic = AuthorityAcceptingStrategic::with('translations')
                ->when($item->accept_act_institution_type_id, function ($query) use ($item) {
                    // Load specific records when item is 1
                    if ($item->accept_act_institution_type_id == 2 || $item->accept_act_institution_type_id == 1) {
                        return $query->whereIn('id', [1,2]);
                    }
                    return $query->where('id', $item->accept_act_institution_type_id);
                })->get();

            $strategicDocumentLevels = StrategicDocumentLevel::with('translations')->get();
            $ekateAreas = $ekateAreas->get();
            $ekateMunicipalities = $ekateMunicipalities->get();
        } else {
            $commonService = app(CommonService::class);
            $userInstitutions = $commonService->mapUserToInstitutions($user);
            $manipulicity = Arr::get($userInstitutions,'manipulicity');
            $area = Arr::get($userInstitutions,'area');
            $ekateMunicipalities = $manipulicity ? $ekateMunicipalities->where('ekatte_municipality.id', $manipulicity)->get() : $ekateMunicipalities->get();
            $ekateAreas = $area ? $ekateAreas->where('ekatte_area.id', $area)->get() : $ekateAreas->get();

            $authoritiesAcceptingStrategic = Arr::get($userInstitutions,'authority_accepting_strategic');
            $strategicDocumentLevels = Arr::get($userInstitutions,'strategic_document_level');
        }

        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields',
            'strategicDocumentLevels', 'strategicDocumentTypes', 'strategicActTypes', 'authoritiesAcceptingStrategic',
            'policyAreas', 'prisActs', 'consultations', 'fileData', 'fileDataEn', 'legalActTypes', 'documentDate', 'mainFile', 'mainFiles', 'ekateAreas', 'ekateMunicipalities', 'adminUser'));
    }

    public function store(StoreStrategicDocumentRequest $request)
    {
        $validated = $request->validated();
        $id = $validated['id'];
        $stay = Arr::get($validated, 'stay') || null;
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

                $prisActId = Arr::get($validated, 'pris_act_id');
                $validated['document_date_accepted'] = $prisActId ? Pris::find($prisActId)->doc_date : null;
                $datesToBeParsedToCarbon = [
                    'document_date_accepted',
                    'document_date_expiring',
                    'document_date',
                ];

                foreach ($datesToBeParsedToCarbon as $date) {
                    if (array_key_exists($date, $validated)) {
                        $validated[$date] = $validated[$date] ? Carbon::parse($validated[$date]) : null;
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
                    $fileService->uploadFiles($validated, $item, null, true);
                } else {
                    $locale = app()->getLocale();
                    $mainFile = $item->files->where('is_main', true)->where('locale', $locale)->first();
                    if ($mainFile) {
                        $validated = $fileService->prepareMainFileFields($validated);
                        $this->storeTranslateOrNew(StrategicDocumentFile::TRANSLATABLE_FIELDS, $mainFile, $validated);
                        $mainFile->strategic_document_type_id = Arr::get($validated, 'strategic_document_type_file_main_id');
                        $validAt = Arr::get($validated, 'valid_at_main');
                        $mainFile->valid_at = $validAt ? Carbon::parse($validAt) : null;
                        $mainFile->visible_in_report = Arr::get($validated, 'visible_in_report') ?? 0;
                        $mainFile->save();
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
     * @return RedirectResponse|void
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
            DB::commit();

            return redirect()->back()->with('success', __('custom.strategic_document_deleted'));
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
            $fileService->uploadFiles($validated, $strategicDoc, null);

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
            'valid_at_files' => ['required_if:date_valid_indefinite_files,0', 'date', 'nullable'],
            'visible_in_report_files' => ['nullable', 'numeric'],
            'strategic_document_type_file' => ['integer'],
            'display_name_file_edit_bg' => ['required', 'string', 'max:500'],
            'display_name_file_edit_en' => ['sometimes', 'nullable','string', 'max:500'],
            // to check
            'file_strategic_documents_bg' => ['sometimes', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', \App\Models\File::ALLOWED_FILE_EXTENSIONS)],
            'file_strategic_documents_en' => ['sometimes', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', \App\Models\File::ALLOWED_FILE_EXTENSIONS)],
            //'display_name_en' => ['sometimes', 'string', 'max:500'],
        ];
        $fields = StrategicDocumentFile::translationFieldsProperties();
        unset($fields['display_name']);
        foreach ($fields as $field => $properties) {
            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
        }
        $validator = Validator::make($request->all(), $rules);

        if( $validator->fails()) {
            return back()->withErrors(['error_'.$id => $validator->errors()->first()]);
        }

        $validated = $validator->validated();
        $file = StrategicDocumentFile::find($validated['id']);
        if( $request->user()->cannot('update', $file->strategicDocument)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            DB::beginTransaction();

            $fileEn = Arr::get($validated, 'file_strategic_documents_en');
            $fileBg = Arr::get($validated, 'file_strategic_documents_bg');
            $validated['display_name_bg'] = Arr::get($validated, 'display_name_file_edit_bg');
            $validated['display_name_en'] = Arr::get($validated, 'display_name_file_edit_en');

            if ($fileEn || $fileBg) {
                $strategicDocumentFileService = app(FileService::class);
                $theFile = $file->latestVersion ?? $file;
                $strategicDocumentFileService->uploadFiles($validated, $theFile->strategicDocument, $theFile);
            } else {
                $this->storeTranslateOrNew(StrategicDocumentFile::TRANSLATABLE_FIELDS, $file, $validated);
                $file->strategic_document_type_id = Arr::get($validated, 'strategic_document_type_file');
                $validAt = Arr::get($validated, 'valid_at_files');
                $file->valid_at = $validAt ? Carbon::parse($validAt) : null;
                $file->visible_in_report = Arr::get($validated, 'visible_in_report_files') ?? 0;
                $file->save();
            }

            DB::commit();
            return redirect(route(self::EDIT_ROUTE, ['id' => $file->strategic_document_id]))
                ->with('success', trans_choice('custom.strategic_document_files', 1)." ".__('messages.updated_successfully_m'));
        } catch (\Exception $e) {
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
                foreach ($fileStructures['children'] as $key => $child) {
                    $currentFile = StrategicDocumentFile::find($child['id']);
                    $currentFile->parent_id = null;
                    $currentFile->ord = $key + 1;
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
            if ($id == 'all') {
                $prisActs = Pris::with('translations')->get();
            } else {
                $prisActs = Pris::where('legal_act_type_id', $id)->get();
            }

            foreach ($prisActs as $prisAct) {
                $prisOptions[] = [
                    'id' => $prisAct->id,
                    'text' => $prisAct->regNum,//$prisAct->actType->name . ' N' . $prisAct->doc_num . ' ' . $prisAct->doc_date,
                ];
            }

            return response()->json(['prisOptions' => $prisOptions]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptActInstitutionOptions(int $id)
    {
        $user = auth()->user();
        if ($user->hasRole('service_user') || $user->hasRole('super-admin'))
        {
            try {
                $documentsAcceptingInstitutionsOptions = [];
                $strategicDocumentLevel = StrategicDocumentLevel::find($id);

                if ($strategicDocumentLevel->id == 1) {
                    $documentsAcceptingInstitutions = AuthorityAcceptingStrategic::whereIn('id', [1,2])->orderBy('id', 'desc')->get();
                }
                if ($strategicDocumentLevel->id == 2) {
                    $documentsAcceptingInstitutions = AuthorityAcceptingStrategic::where('id', 3)->get();
                }
                if ($strategicDocumentLevel->id == 3) {
                    $documentsAcceptingInstitutions = AuthorityAcceptingStrategic::where('id', 4)->get();
                }
                if (isset($documentsAcceptingInstitutions)) {
                    foreach ($documentsAcceptingInstitutions as $documentsAcceptingInstitution) {
                        $documentsAcceptingInstitutionsOptions[] = [
                            'id' => $documentsAcceptingInstitution->id,
                            'text' => $documentsAcceptingInstitution->name,
                        ];
                    }
                } else {
                    throw new \Exception('Resource not found.');
                }
                return response()->json(['documentsAcceptingInstitutionsOptions' => $documentsAcceptingInstitutionsOptions]);
            } catch (\Throwable $throwable) {
                return response()->json(['error' => 'Resource not found.'], 404);
            }
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function prisDetails($id)
    {
        try {
            $pris = Pris::findOrFail($id);

            return response()->json(['date' => $pris->doc_date, 'public_consultation_id' => $pris->public_consultation_id, 'legal_act_type_id' => $pris->legal_act_type_id]);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function publicConsultationDetails($id)
    {
        try {
            $publicConsultation = PublicConsultation::findOrFail($id);
            $prisActs = Pris::whereIn('public_consultation_id', [$publicConsultation->id])->get();

            $prisOptions = [];
            foreach ($prisActs as $prisAct) {
                $prisOptions[] = [
                    'id' => $prisAct->id,
                    'text' => $prisAct->displayName,
                ];
            }

            return response()->json(['date' => $publicConsultation->pris?->doc_date, 'pris_options' => $prisOptions, '','legal_act_type_id' => $publicConsultation->pris?->legal_act_type_id]);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }
    }

    public function strategicDocumentsFromSamePolicyArea(int $policyAreaId)
    {
        try {
            $strategicDocuments = StrategicDocument::with(['translations'])->where('policy_area_id', $policyAreaId)->get();
            return response()->json(['strategicDocuments' => $strategicDocuments]);
        } catch (\Throwable $throwable) {
            return response()->json(['error' => 'Resource not found.'], 404);
        }
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function publish(int $id, $stay = false)
    {
        $strategicDocument = StrategicDocument::findOrFail($id);

        return $this->publishUnPublish($strategicDocument,true, $stay);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function unPublish(int $id, $stay = false)
    {
        $strategicDocument = StrategicDocument::findOrFail($id);

        return $this->publishUnPublish($strategicDocument,false, $stay);
    }

    /**
     * @param StrategicDocument $strategicDocument
     * @param bool $publish
     * @param $stay
     * @return RedirectResponse
     */
    private function publishUnPublish(StrategicDocument $strategicDocument, bool $publish, $stay)
    {
        $strategicDocument->active = $publish;
        $strategicDocument->save();
        $redirectRoute = $stay == 'true' ? route(self::EDIT_ROUTE, ['id' => $strategicDocument->id]) : route(self::LIST_ROUTE);

        return redirect($redirectRoute)
            ->with('success', trans_choice('custom.strategic_documents', 1)." ".($strategicDocument->id ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
    }

    public function loadPrisActs(Request $request)
    {
        $strategicDocumentId = $request->get('documentId');
        $strategicDocument = StrategicDocument::find($strategicDocumentId);
        $strategicDocumentsCommonService = app(CommonService::class);
        $prisActs = $strategicDocumentsCommonService->prisActSelect2Search($request, $strategicDocument);

        $prisActs = $prisActs->paginate(20);
        $prisOptions = [
            'items' => $prisActs->map(function ($prisAct) {
                return [
                    'id' => $prisAct->id,
                    'text' => $prisAct->displayName
                ];
            }),
            'more' => $prisActs->hasMorePages()
        ];

        if ($strategicDocumentId && $prisActs->count() > 1) {
            $currentPrisOption = $strategicDocument?->pris;
            if ($currentPrisOption) {
                $customOption = [
                    'id' => $currentPrisOption->id,
                    'text' => $currentPrisOption->displayName,
                ];
                $prisOptions['items'] = $prisOptions['items']->toArray();
                array_unshift($prisOptions['items'], $customOption);
                $prisOptions['items'][0]['selected'] = true;
            }
        }

        return response()->json(
            $prisOptions,
        );
    }

    public function loadParentStrategicDocuments(Request $request)
    {
        $strategicDocumentId = $request->get('documentId');
        $strategicDocumentsCommonService = app(CommonService::class);
        $strategicDocuments = $strategicDocumentsCommonService->parentStrategicDocumentsSelect2Options($request);
        $strategicDocuments = $strategicDocuments->paginate(20);
        $documentOptions = [
            'items' => $strategicDocuments->map(function ($strategicDocument) {
                return [
                    'id' => $strategicDocument->id,
                    'text' => $strategicDocument->title
                ];
            }),
            'more' => $strategicDocuments->hasMorePages()
        ];

        if ($strategicDocumentId) {
            $parentDocument = StrategicDocument::find($strategicDocumentId)?->parentDocument;
            if ($parentDocument) {
                $customOption = [
                    'id' => $parentDocument->id,
                    'text' => $parentDocument->title,
                ];
                $documentOptions['items'] = $documentOptions['items']->toArray();
                array_unshift($documentOptions['items'], $customOption);
                $documentOptions['items'][0]['selected'] = true;
            }
        }

        return response()->json($documentOptions);
    }
}

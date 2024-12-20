<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InstitutionCategoryLevelEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\LanguageFileUploadRequest;
use App\Http\Requests\StoreStrategicDocumentRequest;
use App\Http\Requests\StrategicDocumentFileUploadRequest;
use App\Http\Requests\StrategicDocumentUploadFileRequest;
use App\Models\Consultations\PublicConsultation;
use App\Models\EkatteArea;
use App\Models\EkatteAreaTranslation;
use App\Models\EkatteMunicipality;
use App\Models\LegalActType;
use App\Models\PolicyArea;
use App\Models\Pris;
use App\Models\StrategicDocument;
use App\Models\AuthorityAcceptingStrategic;
use App\Models\CustomRole;
use App\Models\StrategicActType;
use App\Models\StrategicDocumentFile;
use App\Models\StrategicDocumentLevel;
use App\Models\StrategicDocumentType;
use App\Services\FileOcr;
use App\Services\StrategicDocuments\CommonService;
use App\Services\StrategicDocuments\FileService;
use App\Sorter\AdvisoryBoard\FieldOfAction;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
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
    const SECTIONS = ['general', 'files'];
    const SECTION_GENERAL = 'general';
    const SECTION_FILES = 'files';

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

        $q = StrategicDocument::select('strategic_document.*')->with(['translation',// 'documentLevel', 'documentLevel.translation',
            'documentType', 'documentType.translation',
            'acceptActInstitution', 'acceptActInstitution.translation',
            'files', 'files.translation', 'files.documentType', 'files.documentType.translation'])
            ->leftJoin('field_of_actions', 'field_of_actions.id' ,'=', 'strategic_document.policy_area_id')
            ->leftJoin('strategic_document_translations', function ($j){
                $j->on('strategic_document_translations.strategic_document_id' ,'=', 'strategic_document.id')->where('strategic_document_translations.locale', '=', app()->getLocale());
            })
            ->FilterBy($requestFilter)
            ->when(isset($requestFilter['only_deleted']), fn($q) => $q->onlyTrashed())
            ->when(($requestFilter['strategic_document_type_id'] ?? null), fn($q, $value) => $q->where('strategic_document_type_id', $value));

        if (!$request->user()->hasAnyRole([CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_STRATEGIC_DOCUMENTS])) {
            $userPolicyAreas = $request->user()->institution ?
                ($request->user()->institution->fieldsOfAction->count() ?
                        $request->user()->institution->fieldsOfAction->pluck('id')->toArray() : [0])
                : [0];
            $q->whereIn('field_of_actions.id', $userPolicyAreas);
        }

        $items = $q->paginate($paginate);
        $toggleBooleanModel = 'StrategicDocument';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $publishRouteName = self::PUBLISH_ROUTE;
        $unPublishRouteName = self::UNPUBLISH_ROUTE;

        return $this->view(self::LIST_VIEW, compact(
            'filter',
            'items',
            'toggleBooleanModel',
            'editRouteName',
            'listRouteName',
            'publishRouteName',
            'unPublishRouteName'
        ));
    }

    /**
     * @param Request $request
     * @param int $item
     * @param string $section
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function edit(Request $request, $id = 0, string $section = 'general', StrategicDocumentFile $strategicFile = NULL)
    {
        $user = auth()->user();
        $item = $this->getRecord($id, ['documents', 'documents.translations', 'pris.actType','documentType.translations','translation']);

        if( ($item && $item->id && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', StrategicDocument::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $heading = __('custom.creation_of') . ' ' . l_trans('custom.strategic_documents', 1);

        if (($item && $item->id)) {
            $heading = __('custom.edit_of') . ' ' . l_trans('custom.strategic_documents', 1);
        }

        $this->setBreadcrumbsTitle($heading);

        $strategicDocumentTypes = StrategicDocumentType::with('translations')->orderByTranslation('name')->get();

        if($section == self::SECTION_GENERAL){
            $storeRouteName = self::STORE_ROUTE;
            $listRouteName = self::LIST_ROUTE;
            $translatableFields = StrategicDocument::translationFieldsProperties();

            $strategicActTypes = StrategicActType::with('translations')->orderByTranslation('name')->get();
            $legalActTypes = LegalActType::StrategyCategories()->with('translations')->get();
            $authoritiesAcceptingStrategic = AuthorityAcceptingStrategic::with('translations')->whereNotNull('nomenclature_level_id')->get();
            $adminUser = $user->hasAnyRole(['service_user','super-admin', 'moderator-strategics']);
            if ($adminUser) {
                //$authoritiesAcceptingStrategic = AuthorityAcceptingStrategic::with('translations')->get();
                $strategicDocumentLevels =  enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'strategic_document.dropdown', !$item->id, [InstitutionCategoryLevelEnum::CENTRAL_OTHER->value]);

                //Field of actions split by parent categories
                $ekateAreas = \App\Models\FieldOfAction::Active()->Area()->with(['translations'])->orderByTranslation('name')->get();
                $ekateMunicipalities = \App\Models\FieldOfAction::Active()->Municipal()->with(['translations'])->orderByTranslation('name')->get();
                $policyAreas = \App\Models\FieldOfAction::Active()->Central()->with(['translations'])->orderByTranslation('name')->get();
            } else {
                //$authoritiesAcceptingStrategic = AuthorityAcceptingStrategic::with('translations')->get();
                if($item->id){
                    $strategicDocumentLevels = array(['value' => $item->strategic_document_level_id, 'name' => __('custom.nomenclature_level.' . InstitutionCategoryLevelEnum::keyByValue($item->strategic_document_level_id))]);
                } else{
                    $strategicDocumentLevels = !$user->institution ? []
                        : array(
                            ['value' => '', 'name' => ''],
                            [
                                'value' => ($user->institution->level->nomenclature_level == InstitutionCategoryLevelEnum::CENTRAL_OTHER->value ? InstitutionCategoryLevelEnum::CENTRAL->value : $user->institution->level->nomenclature_level) ,
                                'name' => __('custom.nomenclature_level.' . InstitutionCategoryLevelEnum::keyByValue($user->institution->level->nomenclature_level == InstitutionCategoryLevelEnum::CENTRAL_OTHER->value ? InstitutionCategoryLevelEnum::CENTRAL->value : $user->institution->level->nomenclature_level ))
                            ]
                        );
                }

                //Field of actions split by parent categories
                $userPolicyAreas = $user->institution ?
                    ($user->institution->fieldsOfAction->count() ? $user->institution->fieldsOfAction->pluck('id')->toArray() : [0])
                    : [0];
                $ekateAreas = $user->institution ? \App\Models\FieldOfAction::Active(true)->Area()->whereIn('field_of_actions.id', $userPolicyAreas)->with(['translations'])->orderByTranslation('name')->get() : null;
                $ekateMunicipalities = $user->institution ? \App\Models\FieldOfAction::Active(true)->Municipal()->whereIn('field_of_actions.id', $userPolicyAreas)->with(['translations'])->orderByTranslation('name')->get() : null;
                $policyAreas = $user->institution ? \App\Models\FieldOfAction::Active(true)->Central()->whereIn('field_of_actions.id', $userPolicyAreas)->with(['translations'])->orderByTranslation('name')->get() : null;
            }
            return $this->view(self::EDIT_VIEW, compact('section', 'item', 'storeRouteName', 'listRouteName', 'translatableFields',
                'strategicDocumentLevels', 'strategicDocumentTypes', 'strategicActTypes', 'authoritiesAcceptingStrategic',
                'policyAreas', 'legalActTypes', 'ekateAreas', 'ekateMunicipalities', 'adminUser'));
        } else if($section == self::SECTION_FILES) {
            return $this->view(self::EDIT_VIEW, compact('section', 'item', 'strategicDocumentTypes', 'strategicFile'));
        } else {
            return redirect(route('admin.strategic_documents.edit', [$item->id ?? 0, self::SECTION_GENERAL]));
        }


        //$consultations = PublicConsultation::with('translations')->get();
        //$prisActs = Pris::with('translations')->get();

//        $strategicDocumentFilesBg = StrategicDocumentFile::with(['parentFile', 'translations', 'versions.translations', 'parentFile.versions.translations', 'documentType.translations', 'documentType.translations', 'parentFile.versions.documentType.translations'])->where('strategic_document_id', $item->id)->where('locale', 'bg')->orderBy('ord')->get();
        //$strategicDocumentsFileService = app(FileService::class);
        //$fileData = $strategicDocumentsFileService->prepareFileData($strategicDocumentFilesBg);
        //$fileDataEn = $strategicDocumentsFileService->prepareFileData($strategicDocumentFilesEn);
       // $fileDataEn = [];
        //$documentDate = $item->pris?->document_date ? $item->pris?->document_date : $item->document_date;
        //$mainFile = $strategicDocumentFilesBg->where('is_main', true)->sortByDesc('version')->first();
        //$mainFiles = $item->files->where('is_main', true);
        //$mainFile = $mainFile->parentFile?->latestVersion ?? $mainFile;

        //based on user


        //'fileData', 'fileDataEn', 'documentDate', 'mainFile', 'mainFiles','prisActs', 'consultations',

    }

    /**
     * @param StrategicDocumentFileUploadRequest $request
     * @param $objectId
     * @param $typeObject
     * @param bool $redirect
     * @return Application|RedirectResponse|Redirector|void
     */
    public function uploadFileLanguagesSd(StrategicDocumentFileUploadRequest $request, $objectId, $typeObject, $redirect = true) {
        try {
            $validated = $request->all();
            // Upload File
            $pDir = match ((int)$typeObject) {
                \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT,
                \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN => \App\Models\StrategicDocumentFile::DIR_PATH,
                default => '',
            };

            foreach ($this->languages as $lang) {

                //$default = $lang['default'];
                $code = $lang['code'];

                if (!isset($validated['file_'.$code]) || !isset($validated['description_'.$code])) {
                    continue;
                }

                $file = $validated['file_'.$code];
//                $desc = $validated['description_'.$code];
                $fileNameToStore = round(microtime(true)).'.'.$file->getClientOriginalExtension();
                $file->storeAs($pDir, $fileNameToStore, 'public_uploads');
                $newFile = new StrategicDocumentFile([
                    'strategic_document_id' => $objectId,
                    'strategic_document_type_id' => $typeObject,
                    'filename' => $fileNameToStore,
                    'content_type' => $file->getClientMimeType(),
                    'path' => $pDir.$fileNameToStore,
                    'sys_user' => auth()->user()->id,
                    'locale' => $code,
                    'description' => $validated['description_'.$code],
                    'file_info' => $validated['file_info_'.$code] ?? NULL,
                    'version' => '1.0',
                    'visible_in_report' => isset($validated['is_visible_in_report'])
                ]);
                $newFile->save();

                //$newFile->translateOrNew($code)->display_name = $desc;

                $ocr = new FileOcr($newFile->refresh());
                $ocr->extractText();
            }

            switch ((int)$typeObject) {
                case \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT:
                    $route = route('admin.strategic_documents.edit', [$objectId, StrategicDocumentsController::SECTION_FILES]);
                    break;
                case \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN:
                    $route = route('admin.strategic_documents.document.edit', [$objectId, StrategicDocumentsController::SECTION_FILES]);
                    break;
                default:
                    $route = '';
            }
            if ($redirect) {
                return redirect($route)->with('success', 'Файлът е качен успешно');
            }
        } catch (\Exception $e) {
            throw $e;
            logError('Upload file strategic document', $e->getMessage());
            return $this->backWithError('danger', 'Възникна грешка при качването на файловете. Презаредете страницата и опитайте отново.');
        }
    }

    public function updateFileLanguage(Request $request, $objectId, $typeObject, StrategicDocumentFile $strategicFile = NULL) {
        try {
            $validated = $request->all();
            // Upload File
            $pDir = match ((int)$typeObject) {
                \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT,
                \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN => \App\Models\StrategicDocumentFile::DIR_PATH,
                default => '',
            };

            //$default = $lang['default'];
            $code = $strategicFile->locale;

            $data = [
                'strategic_document_id' => $objectId,
                'strategic_document_type_id' => $typeObject,
                'sys_user' => auth()->user()->id,
                'locale' => $code,
                'description' => $validated['description_'.$code],
                'file_info' => $validated['file_info_'.$code] ?? NULL,
                'version' => '1.0',
                'visible_in_report' => isset($validated['is_visible_in_report'])
            ];

            if (isset($validated['file_' . $code])) {
                /*
                 * We could delete the old file here, however in case the client wants some sort of version control of the files,
                 * we wouldn't be able to retrieve them if they're physically deleted.
                 * We can always get the old location of the file from the activity log.
                 */
                $file = $validated['file_'.$code];
//                $desc = $validated['description_'.$code];

                $fileNameToStore = round(microtime(true)).'.'.$file->getClientOriginalExtension();
                $file->storeAs($pDir, $fileNameToStore, 'public_uploads');

                $data['filename'] = $fileNameToStore;
                $data['content_type'] = $file->getClientMimeType();
                $data['path'] = $pDir.$fileNameToStore;
            }

            $strategicFile->update($data);

            $ocr = new FileOcr($strategicFile->refresh());
            $ocr->extractText();

            switch ((int)$typeObject) {
                case \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT:
                    $route = route('admin.strategic_documents.edit', [$objectId, StrategicDocumentsController::SECTION_FILES]);
                    break;
                case \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT_CHILDREN:
                    $route = route('admin.strategic_documents.document.edit', [$objectId, StrategicDocumentsController::SECTION_FILES]);
                    break;
                default:
                    $route = '';
            }

            return redirect($route)->with('success', 'Файлът е качен успешно');
        } catch (\Exception $e) {
            throw $e;
            logError('Upload file strategic document', $e->getMessage());
            return $this->backWithError('danger', 'Възникна грешка при качването на файловете. Презаредете страницата и опитайте отново.');
        }
    }

    public function store(StoreStrategicDocumentRequest $request)
    {
        $validated = $request->validated();
        $fileRequests = [];

        // Validate before we store
        if (count($request->get('files', []))) {
            foreach ($request->get('files') as $key => $file) {
                $descriptionData = [];
                $fileData = [];

                // Prepare the data for each file by going through all the languages
                foreach(config('available_languages') as $lang) {
                    $descriptionKey = 'description_' . $lang['code'];
                    $fileKey = 'file_' . $lang['code'];

                    if (isset($file[$descriptionKey])) {
                        $descriptionData[$descriptionKey] = $file[$descriptionKey];
                    }

                    if ($request->hasFile('files.' . $key . '.' . $fileKey)) {
                        $fileData[$fileKey] = $request->file('files.' . $key . '.' . $fileKey);
                    }
                }

                // Create a StrategicDocumentFileUploadRequest from a new Request
                $customRequest = new Request($descriptionData,
                    $descriptionData, [], [], $fileData);

                $fileRequest = StrategicDocumentFileUploadRequest::createFrom($customRequest);
                $fileRequest->validate($fileRequest->rules());

                $fileRequests[] = $fileRequest;
            }
        }

        $id = $validated['id'];
        $stay = Arr::get($validated, 'stay') || null;
        $item = $id ? $this->getRecord($id) : new StrategicDocument();

        if( ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', StrategicDocument::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            DB::beginTransaction();
            //START Ugly fix for wrong fields and connections
            //!!! DO not change
            if($validated['strategic_document_level_id'] == InstitutionCategoryLevelEnum::AREA->value) {
                $validated['policy_area_id'] = $validated['ekatte_area_id'] ?? null;
            }
            if($validated['strategic_document_level_id'] == InstitutionCategoryLevelEnum::MUNICIPAL->value) {
                $validated['policy_area_id'] = $validated['ekatte_municipality_id'] ?? null;
            }
            if(isset($validated['ekatte_area_id'])){
                unset($validated['ekatte_area_id']);
            }
            if(isset($validated['ekatte_municipality_id'])){
                unset($validated['ekatte_municipality_id']);
            }
            if (isset($validated['date_expiring_indefinite']) && $validated['date_expiring_indefinite']) {
                $validated['document_date_expiring'] = NULL;
            }
            //END Ugly fix for wrong fields and connections
            if( $validated['accept_act_institution_type_id'] == AuthorityAcceptingStrategic::COUNCIL_MINISTERS ) {
                $validated['strategic_act_number'] = null;
                $validated['strategic_act_type_id'] = null;
                $validated['strategic_act_link'] = null;
                $validated['document_date'] = null;

                $prisActId = Arr::get($validated, 'pris_act_id');
                $validated['document_date_accepted'] = $prisActId ? Pris::find($prisActId)->doc_date : ($validated['document_date_accepted'] ?? Carbon::now());
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

            if (count($fileRequests)) {
                foreach ($fileRequests as $fileRequest) {
                    $this->uploadFileLanguagesSd($fileRequest, $item->id, \App\Models\File::CODE_OBJ_STRATEGIC_DOCUMENT);
                }
            }

            DB::commit();
            if( $stay ) {
                return redirect(route(self::EDIT_ROUTE, ['id' => $item->id]))
                    ->with('success', trans_choice('custom.strategic_documents', 1)." ".($id ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
            }
            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.strategic_documents', 1)." ".($id ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            // This type of exception might get thrown from validating the files before we upload them
            throw $e;
        }
        catch (\Exception $e) {
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
        DB::beginTransaction();
        try {
            $item = $this->getRecord($id);
            if ($item && request()->user()->cannot('delete', $item)) {
                return back()->with('warning', __('messages.unauthorized'));
            }

            if($item->documents->count()){
                foreach ($item->documents as $d){
                    $d->files->each->delete();
                    $d->delete();
                }
            }

            $item->files->each->delete();
            $item->delete();
            DB::commit();

            if(url()->previous() == route('site.home')){
                return redirect(route('site.home'))
                    ->with('success', __('custom.the_record')." ".__('messages.deleted_successfully_m'));
            } else{
                return redirect()->back()->with('success', __('custom.strategic_document_deleted'));
            }
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error('Delete strategic document ID('.$id.'): '.$throwable);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $item = $this->getRecord($id);
            if ($item && request()->user()->cannot('restore', $item)) {
                return back()->with('warning', __('messages.unauthorized'));
            }

            if($item->documents->count()){
                foreach ($item->documents as $d){
                    $d->files->each->restore();
                    $d->restore();
                }
            }

            $item->files->each->restore();
            $item->restore();
            DB::commit();

            return redirect(route('admin.strategic_documents.edit', $id))
                ->with('success', __('custom.the_record')." ".__('messages.restored_successfully_m'));
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::error('Restore strategic document ID('.$id.'): '.$throwable);
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

//    public function updateDcoFile(Request $request, $id)
//    {
//        $rules = [
//            'id' => ['required', 'numeric', 'exists:strategic_document_file,id'],
//            'valid_at_files' => ['required_if:date_valid_indefinite_files,0', 'date', 'nullable'],
//            'visible_in_report_files' => ['nullable', 'numeric'],
//            'strategic_document_type_file' => ['integer'],
//            'display_name_file_edit_bg' => ['required', 'string', 'max:500'],
//            'display_name_file_edit_en' => ['sometimes', 'nullable','string', 'max:500'],
//            // to check
//            'file_strategic_documents_bg' => ['sometimes', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', \App\Models\File::ALLOWED_FILE_EXTENSIONS)],
//            'file_strategic_documents_en' => ['sometimes', 'file', 'max:'.config('filesystems.max_upload_file_size'), 'mimes:'.implode(',', \App\Models\File::ALLOWED_FILE_EXTENSIONS)],
//            //'display_name_en' => ['sometimes', 'string', 'max:500'],
//        ];
//        $fields = StrategicDocumentFile::translationFieldsProperties();
//        unset($fields['display_name']);
//        foreach ($fields as $field => $properties) {
//            $rules[$field .'_'. app()->getLocale()] = $properties['rules'];
//        }
//        $validator = Validator::make($request->all(), $rules);
//
//        if( $validator->fails()) {
//            return back()->withErrors(['error_'.$id => $validator->errors()->first()]);
//        }
//
//        $validated = $validator->validated();
//        $file = StrategicDocumentFile::find($validated['id']);
//        if( $request->user()->cannot('update', $file->strategicDocument)) {
//            return back()->with('warning', __('messages.unauthorized'));
//        }
//
//        try {
//            DB::beginTransaction();
//
//            $fileEn = Arr::get($validated, 'file_strategic_documents_en');
//            $fileBg = Arr::get($validated, 'file_strategic_documents_bg');
//            $validated['display_name_bg'] = Arr::get($validated, 'display_name_file_edit_bg');
//            $validated['display_name_en'] = Arr::get($validated, 'display_name_file_edit_en');
//
//            if ($fileEn || $fileBg) {
//                $strategicDocumentFileService = app(FileService::class);
//                $theFile = $file->latestVersion ?? $file;
//                $strategicDocumentFileService->uploadFiles($validated, $theFile->strategicDocument, $theFile);
//            } else {
//                $this->storeTranslateOrNew(StrategicDocumentFile::TRANSLATABLE_FIELDS, $file, $validated);
//                $file->strategic_document_type_id = Arr::get($validated, 'strategic_document_type_file');
//                $validAt = Arr::get($validated, 'valid_at_files');
//                $file->valid_at = $validAt ? Carbon::parse($validAt) : null;
//                $file->visible_in_report = Arr::get($validated, 'visible_in_report_files') ?? 0;
//                $file->save();
//            }
//
//            DB::commit();
//            return redirect(route(self::EDIT_ROUTE, ['id' => $file->strategic_document_id]))
//                ->with('success', trans_choice('custom.strategic_document_files', 1)." ".__('messages.updated_successfully_m'));
//        } catch (\Exception $e) {
//            DB::rollBack();
//            Log::error('Update strategic document file ID('.$file->id.'): '.$e);
//            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
//        }
//    }

    public function downloadDocFile(StrategicDocumentFile $file)
    {
        if (Storage::disk('public_uploads')->has($file->path)) {
            return Storage::disk('public_uploads')->download($file->path, $file->filename);
        } else {
            return back()->with('warning', __('messages.record_not_found'));
        }
    }

//    public function deleteDocFile(Request $request, StrategicDocumentFile $file)
//    {
//        if( $request->user()->cannot('update', $file->strategicDocument)) {
//            return back()->with('warning', __('messages.unauthorized'));
//        }
//        try {
//            $file->delete();
//            return redirect(route(self::EDIT_ROUTE, ['id' => $file->strategic_document_id]))
//                ->with('success', trans_choice('custom.strategic_document_files', 1)." ".__('messages.deleted_successfully_m'));
//        } catch (\Exception $e) {
//            Log::error('Delete Strategic doc file ID('.$file->id.'): '.$e);
//            return redirect(route(self::EDIT_ROUTE, ['id' => $file->strategic_document_id]))
//                ->with('danger', __('messages.system_error'));
//        }
//    }

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
                'placeholder' => trans_choice('custom.nomenclature.strategic_document_level', 1),
                'value' => $request->input('category'),
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'strategic_document.dropdown', false, [InstitutionCategoryLevelEnum::CENTRAL_OTHER->value]),
                'col' => 'col-md-4'
            ),
            'strategic_document_type_id' => array(
                'type' => 'select',
                'placeholder' => trans_choice('custom.nomenclature.strategic_document_type', 1),
                'value' => $request->input('strategic_document_type_id'),
                'options' => optionsFromModel(StrategicDocumentType::with('translations')->orderByTranslation('name')->get()),
                'col' => 'col-md-4'
            ),
            'status' => array(
                'type' => 'select',
                'label' => __('site.strategic_document.categories_based_on_livecycle'),
                'multiple' => false,
                'options' => array(
                    ['name' => '', 'value' => ''],
                    ['name' => trans_choice('custom.effective', 1), 'value' => 'active'],
                    ['name' => trans_choice('custom.expired', 1), 'value' => 'expired'],
                    ['name' => trans_choice('custom.in_process_of_consultation', 1), 'value' => 'public_consultation']
                ),
                'value' => request()->input('status', 'active'),
                'col' => 'col-md-4'
            ),
            'only_deleted' => array(
                'type' => 'checkbox',
                'checked' => $request->input('only_deleted'),
                'placeholder' => __('custom.all_deleted'),
                'value' => 1,
                'col' => 'col-md-12',
                'class' => 'fw-normal'
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
     * @deprecated
     * @param Request $request
     * @return true
     * @throws \Exception
     */
//    public function saveFileTree(Request $request)
//    {
//        try {
//            $strategicDocument = StrategicDocument::findOrFail($request->get('strategicDocumentId'));
//            $fileStructures = Arr::get($request->get('filesStructure'), '0') ?? [];
//            if (isset($fileStructures['children'])) {
//                foreach ($fileStructures['children'] as $key => $child) {
//                    $currentFile = StrategicDocumentFile::find($child['id']);
//                    $currentFile->parent_id = null;
//                    $currentFile->ord = $key + 1;
//                    $currentFile->save();
//                }
//
//                foreach ($fileStructures['children'] as $child) {
//                    $parentId = $child['id'];//$fileStructures['id'];
//                    $this->processChild($child, $strategicDocument, $parentId);
//                }
//                return true;
//            }
//            return true;
//        } catch (\Throwable $throwable) {
//            Log::warning('Strategic documents save tree: ' . $throwable->getMessage());
//            throw new \Exception('Something went wrong while saving the tree');
//        }
//    }

    /**
     * @deprecated
     * @param $node
     * @param $strategicDocument
     * @param $parent
     * @return void
     */
//    protected function processChild($node, $strategicDocument, $parent)
//    {
//        $id = Arr::get($node, 'id');
//        if ($id === null) {
//            return;
//        }
//        $currentFile = StrategicDocumentFile::find($id);
//        if ($parent == 'root') {
//            return;
//        }
//        $parentFile = StrategicDocumentFile::find($parent);
//
//        if (!$parentFile || !$currentFile) {
//            return;
//        }
//
//        $currentFile->parent_id = $parentFile->id;
//
//        if ($parentFile->id !== $currentFile->id) {
//            $currentFile->save();
//        }
//        if (isset($node['children'])) {
//            foreach ($node['children'] as $child) {
//                $this->processChild($child, $strategicDocument, $id);
//            }
//        }
//    }

    /**
     * @deprecated
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
//    public function prisActOptions($id)
//    {
//        try {
//            $prisOptions = [];
//            if ($id == 'all') {
//                $prisActs = Pris::with('translations')->get();
//            } else {
//                $prisActs = Pris::where('legal_act_type_id', $id)->get();
//            }
//
//            foreach ($prisActs as $prisAct) {
//                $prisOptions[] = [
//                    'id' => $prisAct->id,
//                    'text' => $prisAct->regNum,//$prisAct->actType->name . ' N' . $prisAct->doc_num . ' ' . $prisAct->doc_date,
//                ];
//            }
//
//            return response()->json(['prisOptions' => $prisOptions]);
//        } catch (\Exception $e) {
//            return response()->json(['error' => 'Resource not found.'], 404);
//        }
//    }

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
        $strategicDocument = StrategicDocument::find($strategicDocumentId);
        $strategicDocumentsCommonService = app(CommonService::class);
        $strategicDocuments = $strategicDocumentsCommonService->parentStrategicDocumentsSelect2Options($request, $strategicDocument);
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
            $filter = $strategicDocumentsCommonService->documentFilter($request);
            if (Arr::get($filter, 'key') == 'policy-area-id') {
                $policyAreaIdFilter = Arr::get($filter, 'value');
            }
            $parentDocument = $strategicDocument?->parentDocument;
            if ($parentDocument) {
                if (isset($policyAreaIdFilter) && $policyAreaIdFilter ==  $parentDocument->policy_area_id) {

                    $documentOptions = $strategicDocumentsCommonService->parentStrategicDocumentSelectedOption($parentDocument, $documentOptions);
                }
                if (!isset($policyAreaIdFilter)) {
                    $documentOptions = $strategicDocumentsCommonService->parentStrategicDocumentSelectedOption($parentDocument, $documentOptions);
                }
            }
        }

        return response()->json($documentOptions);
    }

    public function nomenclatures() {
        return $this->view('admin.strategic_documents.nomenclatures.index');
    }
}

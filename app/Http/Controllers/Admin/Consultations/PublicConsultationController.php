<?php

namespace App\Http\Controllers\Admin\Consultations;

use App\Enums\DocTypesEnum;
use App\Enums\DynamicStructureTypesEnum;
use App\Enums\InstitutionCategoryLevelEnum;
use App\Enums\PublicConsultationTimelineEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\PublicConsultationContactStoreRequest;
use App\Http\Requests\PublicConsultationContactsUpdateRequest;
use App\Http\Requests\PublicConsultationDocStoreRequest;
use App\Http\Requests\PublicConsultationKdStoreRequest;
use App\Http\Requests\PublicConsultationSubDocUploadRequest;
use App\Http\Requests\StorePublicConsultationProposalReport;
use App\Http\Requests\StorePublicConsultationRequest;
use App\Jobs\SendSubscribedUserEmailJob;
use App\Library\Facebook;
use App\Models\ActType;
use App\Models\Comments;
use App\Models\ConsultationLevel;
use App\Models\Consultations\ConsultationDocument;
use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\LegislativeProgramRow;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\OperationalProgramRow;
use App\Models\Consultations\PublicConsultation;
use App\Models\CustomRole;
use App\Models\DynamicStructure;
use App\Models\DynamicStructureColumn;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\Law;
use App\Models\LinkCategory;
use App\Models\Poll;
use App\Models\ProgramProject;
use App\Models\PublicConsultationContact;
use App\Models\Setting;
use App\Models\StrategicDocuments\Institution;
use App\Models\Timeline;
use App\Models\UserSubscribe;
use App\Services\FileOcr;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PublicConsultationController extends AdminController
{
    const LIST_ROUTE = 'admin.consultations.public_consultations.index';
    const EDIT_ROUTE = 'admin.consultations.public_consultations.edit';
    const STORE_ROUTE = 'admin.consultations.public_consultations.store';
    const LIST_VIEW = 'admin.consultations.public_consultations.index';
    const EDIT_VIEW = 'admin.consultations.public_consultations.edit';

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? PublicConsultation::PAGINATE;

        $items = PublicConsultation::with(['translation', 'consultations'])
            ->FilterBy($requestFilter)
            ->ByUser()
            ->orderByDesc('id')
            ->paginate($paginate);
        $toggleBooleanModel = 'PublicConsultation';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param PublicConsultation|null $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, PublicConsultation|null $item)
    {
        if (($item->id && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', PublicConsultation::class)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        if ($item->id) {
            $item = PublicConsultation::with([
                'translation', 'consultations.translations', 'comments.author', 'fieldOfAction.translation', 'message'
            ])
                ->find($item->id);
        }

        //TODO optimize next one
        $kdRowsDB = $item->id && $item->kd
            ? DynamicStructureColumn::whereIn('id', json_decode($item->kd->active_columns))->orderBy('id')->get()
            : DynamicStructure::with(['columns', 'columns.translation', 'groups', 'groups.translation'])
                ->where('type', '=', DynamicStructureTypesEnum::CONSULT_DOCUMENTS->value)
                ->where('active', '=', 1)
                ->first()
                ->columns;
        $dsGroups = DynamicStructure::where('type', '=', DynamicStructureTypesEnum::CONSULT_DOCUMENTS->value)->where('active', '=', 1)->first()->groups;

        $kdRows = [];
        foreach ($dsGroups as $kdGroup) {
            foreach ($kdRowsDB as $row) {
                if ($row->dynamic_structure_groups_id && $row->dynamic_structure_groups_id == $kdGroup->id) {
                    $kdRows[] = $row;
                }
            }
        }
        foreach ($kdRowsDB as $row) {
            if (!$row->dynamic_structure_groups_id) {
                $kdRows[] = $row;
            }
        }

        $kdValues = [];
        if ($item->kd) {
            $kdValues = $item->kd->records->pluck('value', 'dynamic_structures_column_id')->toArray();
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = PublicConsultation::translationFieldsProperties();

        $isAdmin = auth()->user()->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE]);
        $institutionLevels = $userInstitutionLevel = $institutions = null;
        if ($isAdmin) {
            $institutions = Institution::optionsListWithAttr();
            $institutionLevels = InstitutionCategoryLevelEnum::options();
            $fieldsOfActions = ($item->id && !$item->old_id)
                ? $item->importerInstitution->fieldsOfAction
                : FieldOfAction::with(['translation'])->Active()->orderByTranslation('name')->get();
            $actTypes = ($item->id && !$item->old_id)
                ? ActType::with(['translation'])->where('consultation_level_id', '=', $item->consultation_level_id)->get()
                : ActType::with(['translation'])->get();
        } else {
            $userInstitutionLevel = $request->user()->institution
                ? $request->user()->institution->level->nomenclature_level
                : 0;
            $fieldsOfActions = $item->id
                ? $item->importerInstitution->fieldsOfAction
                : (auth()->user() && auth()->user()->institution ? auth()->user()->institution->fieldsOfAction : null);
            $actTypes = ActType::with(['translation'])
                ->where('consultation_level_id', '=', $item->id ? $item->consultation_level_id : $userInstitutionLevel)
                ->get();
        }

        $consultationLevels = ConsultationLevel::with(['translation'])->get();


        $programProjects = ProgramProject::with(['translation'])->get();
        $linkCategories = LinkCategory::with(['translation'])->get();
        $operationalPrograms = OperationalProgram::get();
        $legislativePrograms = LegislativeProgram::get();

        $documents = [];
        foreach ($item->documents as $document) {
            $documents[$document->doc_type . '_' . $document->locale][] = $document;
        }
        $polls = $item->id ? Poll::whereDoesntHave('consultations')->Active()->NotExpired()->get() : null;

//        $pris = Pris::Decrees()->get();
        $pris = $item->pris;
        $laws = Law::with(['translations'])->get();

//        $diffInDays = null;
//        if($item->id){
//            $from = $item->open_from ? Carbon::parse($item->open_from) : null;
//            $to = $item->open_to ? Carbon::parse($item->open_to) : null;
//            $diffInDays = $to->diffInDays($from);
//        }

        $subDocumentsTypes = $item->documents()
            ->whereIn('doc_type', \App\Enums\DocTypesEnum::docsByActType($item->act_type_id))
            ->get()
            ->pluck('doc_type')
            ->unique()
            ->toArray();

        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields',
            'consultationLevels', 'actTypes', 'programProjects', 'linkCategories',
            'operationalPrograms', 'legislativePrograms', 'kdRows', 'dsGroups', 'kdValues', 'polls', 'documents', 'userInstitutionLevel',
            'fieldsOfActions', 'institutionLevels', 'isAdmin', 'institutions', 'pris', 'laws', 'subDocumentsTypes'));
    }

    public function store(Request $request, PublicConsultation $item)
    {
        $user = $request->user();
        $isAdmin = $user->hasRole([CustomRole::SUPER_USER_ROLE, CustomRole::ADMIN_USER_ROLE]);
        if (!$isAdmin && !$user->institution_id) {
            return back()->withInput($request->all())->with('danger', __('messages.you_are_not_associate_with_institution'));
        }

        $storeRequest = new StorePublicConsultationRequest();
        $storeRequest->item = $item;
        $validator = Validator::make($request->all(), $storeRequest->rules());
        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator->errors());
        }

        $id = $item->id;

        if (($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', PublicConsultation::class)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $validated = $validator->validated();

        $from = $validated['open_from'] ? Carbon::parse($validated['open_from']) : null;
        $to = $validated['open_to'] ? Carbon::parse($validated['open_to']) : null;
        if ($to->diffInDays($from) < PublicConsultation::MIN_DURATION_DAYS) {
            return back()->withInput()->withErrors(['open_from' => 'Минимланият период за обществена консултация е 14 дни']);
        }

        if (!$id && Carbon::parse($from)->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
            return back()->withInput()->withErrors(['open_from' => 'Консултацията може да стартира най-скоро с днешна дата']);
        }

        DB::beginTransaction();
        try {

            //doing this because using strange field name to prevent bad validation message
            $oldOpRow = $item->operational_program_row_id;
            $oldLpRow = $item->legislative_program_row_id;
            $oldOpenFrom = $item->open_from;
            $oldOpenTo = $item->open_to;

            $validated['operational_program_row_id'] = isset($validated['operational_program_row_id']) && (int)$validated['operational_program_row_id'] > 0 ? $validated['operational_program_row_id'] : null;
            $validated['legislative_program_row_id'] = isset($validated['legislative_program_row_id']) && (int)$validated['legislative_program_row_id'] > 0 ? $validated['legislative_program_row_id'] : null;

            $validated['operational_program_id'] = isset($validated['operational_program_id']) && (int)$validated['operational_program_id'] > 0 ? $validated['operational_program_id'] : null;
            $validated['legislative_program_id'] = isset($validated['legislative_program_id']) && (int)$validated['legislative_program_id'] > 0 ? $validated['legislative_program_id'] : null;

            $validated['pris_id'] = isset($validated['pris_id']) && $validated['pris_id'] > 0 ? $validated['pris_id'] : null;
            $validated['law_id'] = isset($validated['law_id']) && $validated['law_id'] > 0 ? $validated['law_id'] : null;

            $fillable = $this->getFillableValidated($validated, $item);
            if (!$id || $item->old_id) {
                $institution = $isAdmin ? Institution::find((int)$validated['institution_id']) : ($request->user()->institution ? $request->user()->institution : null);
                $fillable['consultation_level_id'] = $institution ? $institution->level->nomenclature_level : 0;
            }
            $item->fill($fillable);
            if (!$id) {
                $item->user_id = $user->id;
            }
            $item->active = $request->filled('active') ? $request->input('active') : 0;

            if (!$id || $item->old_id) {
                $item->importer_institution_id = $institution ? $institution->id : null;
                $item->responsible_institution_id = $institution ? $institution->id : null;
            }

            //cache days
//            $from = $validated['open_from'] ? Carbon::parse($validated['open_from']) : null;
//            $to = $validated['open_to'] ? Carbon::parse($validated['open_to']) : null;
            $item->active_in_days = $to && $from ? $to->diffInDays($from) : null;
            $item->save();
            if (!$id) {
                $item->reg_num = $item->id . '-K';
            }
            $this->storeTranslateOrNew(PublicConsultation::TRANSLATABLE_FIELDS, $item, $validated);

            $item->consultations()->sync($validated['connected_pc'] ?? []);

            //START Timeline
            $delete = $update = false;
            $programType = isset($validated['legislative_program_id']) ? LegislativeProgramRow::class : (isset($validated['operational_program_id']) ? OperationalProgramRow::class : null);
            $programRowID = $validated['operational_program_row_id'] ?? ($validated['legislative_program_row_id'] ?? null);

            //Check if changes
            //Programs
            if ((!is_null($validated['operational_program_row_id']) || !is_null($oldOpRow))
                && $validated['operational_program_row_id'] != $oldOpRow) {
                if (is_null($validated['operational_program_row_id']) && is_null($validated['legislative_program_row_id'])) {
                    $delete = true;
                } else {
                    $update = true;
                }
            }
            if ((!is_null($validated['legislative_program_row_id']) || !is_null($oldLpRow))
                && $validated['legislative_program_row_id'] != $oldLpRow) {
                if (is_null($validated['legislative_program_row_id']) && is_null($validated['operational_program_row_id'])) {
                    $delete = true;
                } else {
                    $update = true;
                }
            }
            $event = $item->timeline()->where('event_id', '=', PublicConsultationTimelineEnum::INCLUDE_TO_PROGRAM->value)->first();
            if ($delete && $event) {
                $item->timeline()
                    ->where('event_id', '=', PublicConsultationTimelineEnum::INCLUDE_TO_PROGRAM->value)
                    ->delete();
            }
            if ($update) {
                if ($event) {
                    $item->timeline()
                        ->where('event_id', '=', PublicConsultationTimelineEnum::INCLUDE_TO_PROGRAM->value)
                        ->update(['object_id' => $programRowID, 'object_type' => $programType]);
                } else {
                    $item->timeline()->save(new Timeline([
                        'event_id' => PublicConsultationTimelineEnum::INCLUDE_TO_PROGRAM->value,
                        'object_id' => $programRowID,
                        'object_type' => $programType
                    ]));
                }
            }
            //END Timeline

            //Update polls if necessary
            if ((displayDate($oldOpenFrom) != displayDate($item->open_from)) || (displayDate($oldOpenTo) != displayDate($item->open_from))) {
                $item->polls()->update(['start_date' => databaseDate($item->open_from), 'end_date' => databaseDate($item->open_to)]);
            }

            //Locke program if is selected
            if (isset($validated['legislative_program_id'])) {
                LegislativeProgram::where('id', '=', (int)$validated['legislative_program_id'])
                    ->where('locked', '=', 0)
                    ->update(['locked' => 1, 'public_consultation_id' => $item->id]);
            }
            if (isset($validated['operational_program_id'])) {
                OperationalProgram::where('id', '=', (int)$validated['operational_program_id'])
                    ->where('locked', '=', 0)
                    ->update(['locked' => 1, 'public_consultation_id' => $item->id]);
            }

            DB::commit();
            if (isset($validated['stay']) && $validated['stay'] && $user->can('update', $item)) {
                return redirect(route(self::EDIT_ROUTE, $item))
                    ->with('success', trans_choice('custom.public_consultations', 1) . " " . ($id ? __('messages.updated_successfully_f') : __('messages.created_successfully_f')));
            }
            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.public_consultations', 1) . " " . ($id ? __('messages.updated_successfully_f') : __('messages.created_successfully_f')));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    public function storeDocs(Request $request)
    {
        $storeRequest = new PublicConsultationDocStoreRequest();
        $validator = Validator::make($request->all(), $storeRequest->rules());
        if ($validator->fails()) {
            return redirect(url()->previous() . '#ct-doc')->withInput($request->all())->withErrors($validator->errors());
        }

        $validated = $validator->validated();
        $item = PublicConsultation::find($validated['id']);

        if ($request->user()->cannot('update', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        // Upload File
        $dir = File::PUBLIC_CONSULTATIONS_UPLOAD_DIR;

        DB::beginTransaction();
        try {
            foreach (DocTypesEnum::docsByActType($validated['act_type']) as $docType) {
                $fileIds = [];
                $bgFile = $validated['file_' . $docType . '_bg'] ?? null;
                $enFile = $validated['file_' . $docType . '_en'] ?? null;
                //If no file for this type skip next
                if (!$bgFile && !$enFile) {
                    continue;
                }
                foreach (['bg', 'en'] as $code) {
                    $version = File::where('locale', '=', $code)
                        ->where('id_object', '=', $item->id)
                        ->where('doc_type', '=', $docType)
                        ->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                        ->count();

                    //TODO fix me Ugly way while someone define rules
                    if (!${$code . 'File'}) {
                        //There is no previews version
                        if (!$version) {
                            if ($code == 'en' && !$enFile && $bgFile) {
                                $file = $bgFile;
                            }

                            if ($code == 'bg' && !$bgFile && $enFile) {
                                $file = $enFile;
                            }
                        } else {
                            //we have previews version and do not need to copy file for second language
                            $file = null;
                        }
                    } else {
                        $file = ${$code . 'File'};
                    }

                    if (is_null($file)) {
                        continue;
                    }

                    $newVersion = ($version + 1);
                    $fileNameToStore = round(microtime(true)) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs($dir, $fileNameToStore, 'public_uploads');
                    $newFile = new File([
                        'id_object' => $item->id,
                        'code_object' => File::CODE_OBJ_PUBLIC_CONSULTATION,
                        'filename' => $fileNameToStore,
                        'doc_type' => $docType,
                        'content_type' => $file->getClientMimeType(),
                        'path' => $dir . $fileNameToStore,
                        'description_' . $code => $validated['description_' . $code] ?? __('custom.public_consultation.doc_type.' . $docType, [], $code),
                        'sys_user' => $request->user()->id,
                        'locale' => $code,
                        'version' => $newVersion . '.0'
                    ]);
                    $newFile->save();
                    $fileIds[] = $newFile->id;

                    //timeline
                    if ($newVersion > 0 && $item->inPeriodBoolean) {
                        $item->timeline()->save(new Timeline([
                            'event_id' => PublicConsultationTimelineEnum::FILE_CHANGE->value,
                            'object_id' => $newFile->id,
                            'object_type' => File::class
                        ]));
                    }

                    $ocr = new FileOcr($newFile->refresh());
                    $ocr->extractText();
                }
                //File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
                //File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);
            }
            DB::commit();
            if (isset($validated['stay'])) {
                return redirect(route(self::EDIT_ROUTE, $item) . '#ct-doc')
                    ->with('success', trans_choice('custom.documents', 2) . " " . __('messages.updated_successfully_pl'));
            }
            return redirect(route(self::LIST_ROUTE))
                ->with('success', trans_choice('custom.documents', 2) . " " . __('messages.updated_successfully_pl'));
        } catch (\Exception $e) {
            Log::error('Error store public consultation(ID' . $item->id . ') documents: ' . PHP_EOL . 'Files: ' . json_encode($validated) . PHP_EOL . 'Error: ' . $e);
            DB::rollBack();
            return redirect(url()->previous() . '#ct-doc')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    public function storeSubDocs(Request $request)
    {
        $storeRequest = new PublicConsultationSubDocUploadRequest();
        $validator = Validator::make($request->all(), $storeRequest->rules());
        if ($validator->fails()) {
            return redirect(url()->previous() . '#ct-doc')->withInput($request->all())->withErrors($validator->errors());
        }
        DB::beginTransaction();
        try {
            $typeObjectToSave = File::CODE_OBJ_PUBLIC_CONSULTATION;
            $validated = $request->all();
            // Upload File
            $pDir = File::PUBLIC_CONSULTATIONS_UPLOAD_DIR;

            foreach ($this->languages as $lang) {
                $code = $lang['code'];

                if (!isset($validated['file_' . $code])) {
                    continue;
                }

                if (!isset($validated['file_' . $code])) {
                    $file = $validated['file_bg'];
                    $desc = $validated['description_bg'] ?? null;
                } else {
                    $file = isset($validated['file_' . $code]) && $validated['file_' . $code] ? $validated['file_' . $code] : $validated['file_bg'];
                    $desc = isset($validated['description_' . $code]) && !empty($validated['description_' . $code]) ? $validated['description_' . $code] : ($validated['description_' . config('app.default_lang')] ?? null);
                }
                $fileNameToStore = round(microtime(true)) . '.' . $file->getClientOriginalExtension();
                $file->storeAs($pDir, $fileNameToStore, 'public_uploads');
                $newFile = new File([
                    'id_object' => $validated['id'],
                    'code_object' => $typeObjectToSave,
                    'doc_type' => $validated['parent_type'],
                    'filename' => $fileNameToStore,
                    'content_type' => $file->getClientMimeType(),
                    'path' => $pDir . $fileNameToStore,
                    'description_' . $code => $desc,
                    'sys_user' => $request->user()->id,
                    'locale' => $code,
                    'version' => '1.0',
                    'is_visible' => 1,
                ]);
                $newFile->save();
                try {
                    $ocr = new FileOcr($newFile->refresh());
                    $ocr->extractText();
                } catch (\Exception $e) {
                    Log::error('Error extract file text form file ID (' . $newFile->id . ')');
                }

            }
            DB::commit();
            if (isset($validated['stay'])) {
                return redirect(route('admin.consultations.public_consultations.edit', ['item' => $validated['id']]) . '#ct-doc')
                    ->with('success', trans_choice('custom.documents', 2) . " " . __('messages.updated_successfully_pl'));
            }
            return redirect(route('admin.consultations.public_consultations.index', ['item' => $validated['id']]) . '#ct-doc')->with('success', 'Файлът/файловте са качени успешно');
        } catch (\Exception $e) {
            DB::rollBack();
            logError('Upload file', $e->getMessage());
            return $this->backWithMessage('danger', 'Възникна грешка при качването на файловете. Презаредете страницата и опитайте отново.');
        }
    }


    public function storeKd(Request $request)
    {
        $user = $request->user();
        $storeRequest = new PublicConsultationKdStoreRequest();
        $validator = Validator::make($request->all(), $storeRequest->rules());
        if ($validator->fails()) {
            return redirect(url()->previous() . '#ct-kd')->withInput($request->all())->withErrors($validator->errors());
        }

        $validated = $validator->validated();
        $item = PublicConsultation::with(['kd'])->find((int)$validated['id']);

        if (!$item) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($user->cannot('update', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $update = true;
        DB::beginTransaction();
        try {
            if (!$item->kd) {
                $update = false;
                $activeColumns = DynamicStructure::where('type', '=', DynamicStructureTypesEnum::CONSULT_DOCUMENTS->value)
                    ->where('active', '=', 1)
                    ->first()->columns
                    ->pluck('id')
                    ->toArray();

                $kd = new ConsultationDocument([
                    'public_consultation_id' => $item->id,
                    'active_columns' => json_encode($activeColumns)
                ]);
                $kd->save();
                $item->kd()->save($kd);
                $item->refresh();
            }

            if (isset($validated['row_id']) && sizeof($validated['row_id'])) {
                if (isset($validated['val']) && sizeof($validated['val'])) {
                    if (sizeof($validated['row_id']) === sizeof($validated['val'])) {
                        foreach ($validated['row_id'] as $k => $c) {
                            $item->kd->records()->updateOrCreate(
                                [
                                    'value' => $validated['val'][$k],
                                    'dynamic_structures_column_id' => $c,
                                ]
                            );
                        }
                    }
                }
            }


            $kdValues = [];
            if ($item->kd) {
                $kdValues = $item->kd->records->pluck('value', 'dynamic_structures_column_id')->toArray();
            }
            $kdRowsDB = $item->id && $item->kd ?
                DynamicStructureColumn::whereIn('id', json_decode($item->kd->active_columns))->orderBy('id')->get()
                : DynamicStructure::with(['columns', 'columns.translation', 'groups', 'groups.translation'])->where('type', '=', DynamicStructureTypesEnum::CONSULT_DOCUMENTS->value)->where('active', '=', 1)->first()->columns;
            $dsGroups = DynamicStructure::where('type', '=', DynamicStructureTypesEnum::CONSULT_DOCUMENTS->value)->where('active', '=', 1)->first()->groups;

            $kdRows = [];
            foreach ($dsGroups as $kdGroup) {
                foreach ($kdRowsDB as $row) {
                    if ($row->dynamic_structure_groups_id && $row->dynamic_structure_groups_id == $kdGroup->id) {
                        $kdRows[] = $row;
                    }
                }
            }
            foreach ($kdRowsDB as $row) {
                if (!$row->dynamic_structure_groups_id) {
                    $kdRows[] = $row;
                }
            }

            $path = File::PUBLIC_CONSULTATIONS_UPLOAD_DIR . $item->id . DIRECTORY_SEPARATOR;
            $fileName = 'kd_' . Carbon::now()->format('Y_m_d_H_i_s') . '.pdf';
            $pdf = PDF::loadView('admin.consultations.public_consultations.pdf_kd', ['kdRows' => $kdRows, 'kdValues' => $kdValues]);
            Storage::disk('public_uploads')->put($path . $fileName, $pdf->output());

            foreach (config('available_languages') as $lang) {
                $version = File::where('locale', '=', $lang['code'])
                    ->where('id_object', '=', $item->id)
                    ->where('doc_type', '=', DocTypesEnum::PC_KD_PDF->value)
                    ->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                    ->count();

                $file = new File([
                    'id_object' => $item->id,
                    'code_object' => File::CODE_OBJ_PUBLIC_CONSULTATION,
                    'doc_type' => DocTypesEnum::PC_KD_PDF->value,
                    'filename' => $fileName,
                    'content_type' => 'application/pdf',
                    'path' => $path . $fileName,
                    'description_' . $lang['code'] => trans('custom.public_consultation.doc_type.' . DocTypesEnum::PC_KD_PDF->value, [], $lang['code']),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'locale' => $lang['code'],
                    'version' => ($version + 1) . '.0'
                ]);
                $file->save();
                $ocr = new FileOcr($file->refresh());
                $ocr->extractText();
            }

            DB::commit();
            if ($validated['stay']) {
                return redirect(route(self::EDIT_ROUTE, $item) . '#ct-kd')
                    ->with('success', trans_choice('custom.consult_documents', 1) . " " . ($update ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
            }
            return redirect(route(self::LIST_ROUTE))
                ->with('success', trans_choice('custom.consult_documents', 1) . " " . ($update ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect(url()->previous() . '#ct-kd')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    public function addContact(PublicConsultationContactStoreRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();
        $item = PublicConsultation::find((int)$validated['pc_id']);

        if (!$item) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($user->cannot('update', $item)) {
            return redirect(url()->previous() . '#ct-contacts')->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $item->contactPersons()->create([
                'name' => $validated['new_name'],
                'email' => $validated['new_email'],
            ]);

            DB::commit();
            return redirect(route(self::EDIT_ROUTE, $item) . '#ct-contacts')
                ->with('success', trans_choice('custom.person_contacts', 1) . " " . __('messages.created_successfully_n'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect(url()->previous() . '#ct-contacts')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function removeContact(Request $request)
    {
        $user = $request->user();
        $contact = PublicConsultationContact::find((int)$request->input('id'));

        if (!$contact) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($user->cannot('update', $contact->publicConsultation)) {
            return redirect(url()->previous() . '#ct-contacts')->with('warning', __('messages.unauthorized'));
        }

        $contact->delete();

        return redirect(route(self::EDIT_ROUTE, $contact->publicConsultation) . '#ct-contacts')
            ->with('success', trans_choice('custom.person_contacts', 1) . " " . __('messages.deleted_successfully_n'));
    }

    public function updateContacts(PublicConsultationContactsUpdateRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();
        $item = PublicConsultation::find((int)$validated['pc_id']);

        if (!$item) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($user->cannot('update', $item)) {
            return redirect(url()->previous() . '#ct-contacts')->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            if (isset($validated['id']) && sizeof($validated['id'])) {
                foreach ($validated['id'] as $k => $c) {
                    $item->contactPersons()->where('id', '=', $c)->update([
                        'name' => $validated['name'][$k],
                        'email' => $validated['email'][$k],
                    ]);
                }
            }

            DB::commit();
            return redirect(route(self::EDIT_ROUTE, $item) . '#ct-contacts')
                ->with('success', trans_choice('custom.person_contacts', 2) . " " . __('messages.updated_successfully_pl'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect(url()->previous() . '#ct-contacts')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    private function filters($request)
    {
//        $fields = FieldOfAction::select('field_of_actions.*')
//            ->with('translations')
//            ->joinTranslation(FieldOfAction::class)
//            ->whereLocale(app()->getLocale())
//            ->orderBy('field_of_action_translations.name', 'asc')
//            ->get();
        return array(
            'name' => array(
                'type' => 'text',
                'placeholder' => __('validation.attributes.name'),
                'value' => $request->input('name'),
                'col' => 'col-md-4'
            ),
            'consultationNumber' => array(
                'type' => 'text',
                'placeholder' => __('custom.consultation_number_'),
                'value' => $request->input('consultationNumber'),
                'col' => 'col-md-4'
            ),
            'fieldOfActions' => array(
                'type' => 'select',
                'options' => optionsFromModel(FieldOfAction::optionsList()),
                'multiple' => true,
                'default' => '',
                'placeholder' => trans_choice('custom.field_of_actions', 1),
                'value' => $request->input('fieldOfActions'),
                'col' => 'col-md-4'
            ),
            'actTypes' => array(
                'type' => 'select',
                'options' => optionsFromModel(ActType::get(), true),
                'multiple' => true,
                'default' => '',
                'placeholder' => trans_choice('custom.act_type', 2),
                'value' => $request->input('actTypes'),
                'col' => 'col-md-4'
            ),
            'levels' => array(
                'type' => 'select',
                'options' => enumToSelectOptions(InstitutionCategoryLevelEnum::options(), 'nomenclature_level', true),
                'multiple' => true,
                'default' => '',
                'placeholder' => __('site.public_consultation.importer_type'),
                'value' => $request->input('levels'),
                'col' => 'col-md-4'
            ),
            'importers' => array(
                'type' => 'subjects',
                'placeholder' => __('site.public_consultation.importer'),
                'multiple' => true,
                'options' => optionsFromModel(Institution::simpleOptionsList(), true, '', __('site.public_consultation.importer')),
                'value' => request()->input('importers'),
                'default' => '',
            ),
        );
    }

    public function attachPoll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'numeric', 'exists:public_consultation,id'],
            'poll' => ['required', 'numeric', 'exists:poll,id'],
        ]);

        if ($validator->fails()) {
            return redirect(url()->previous() . '#ct-pools')->withErrors($validator->errors()->all());
        }

        try {
            $validated = $validator->validated();
            $consultation = PublicConsultation::find($validated['id']);
            $consultation->polls()->attach($validated['poll']);

            //update dates if need to
            $poll = Poll::find((int)$validated['poll']);
            if ($poll) {
                $update = false;
                if (Carbon::parse($poll->start_date)->format('Y-m-d') != Carbon::parse($consultation->open_from)->format('Y-m-d')) {
                    $update = true;
                    $poll->start_date = $consultation->open_from;
                }
                if (Carbon::parse($poll->end_date)->format('Y-m-d') != Carbon::parse($consultation->open_to)->format('Y-m-d')) {
                    $update = true;
                    $poll->end_date = $consultation->open_to;
                }

                if ($update) {
                    $poll->save();
                }

                //Send PC Send notification
                $data['event'] = 'pc_poll_created';
                $data['administrators'] = null;
                $data['moderators'] = null;
                $data['modelInstance'] = $poll;
                $data['secondModelInstance'] = $consultation;
                $data['markdown'] = 'public-consultation-poll';

                //get users by model ID
                $subscribedUsers = UserSubscribe::where('subscribable_type', PublicConsultation::class)
                    ->whereCondition(UserSubscribe::CONDITION_PUBLISHED)
                    ->whereChannel(UserSubscribe::CHANNEL_EMAIL)
                    ->where('is_subscribed', '=', UserSubscribe::SUBSCRIBED)
                    ->where('subscribable_id', '=', $consultation->id)
                    ->get();

                $data['subscribedUsers'] = $subscribedUsers;
                if ($data['administrators'] || $data['moderators'] || $data['subscribedUsers']->count()) {
                    SendSubscribedUserEmailJob::dispatch($data);
                }
            }


            return redirect(route(self::EDIT_ROUTE, $consultation) . '#ct-polls')
                ->with('success', trans_choice('custom.public_consultations', 2) . " " . __('messages.updated_successfully_pl'));
        } catch (\Exception $e) {
            Log::error('Error attach poll to public consultation' . $e);
            return redirect(url()->previous() . '#ct-polls')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function addProposalReport(StorePublicConsultationProposalReport $request)
    {
        $validated = $request->validated();
        $pc = PublicConsultation::find((int)$validated['id']);

        if (!$request->user()->can('proposalReport', $pc)) {
            return back()->with('warning', 'Действието не е позволено преди приключване на консултацията.');
        }

        $docType = DocTypesEnum::PC_COMMENTS_REPORT->value;
        try {
            // Upload File
            $dir = File::PUBLIC_CONSULTATIONS_UPLOAD_DIR;
            $bgFile = $validated['file_' . $docType . '_bg'] ?? null;
            $enFile = $validated['file_' . $docType . '_en'] ?? null;
            $fileEvent = null;

            foreach (['bg', 'en'] as $code) {
                $version = File::where('locale', '=', $code)
                    ->where('id_object', '=', $pc->id)
                    ->where('doc_type', '=', $docType)
                    ->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                    ->count();

                //TODO fix me Ugly way while someone define rules
                if (!${$code . 'File'}) {
                    //we have previews version and do not need to copy file for second language
                    $file = null;
                    if (!$version) {
                        if ($code == 'en' && !$enFile && $bgFile) {
                            $file = $bgFile;
                        }
                        if ($code == 'bg' && !$bgFile && $enFile) {
                            $file = $enFile;
                        }
                    }
                } else {
                    $file = ${$code . 'File'};
                }
                if (!$file) {
                    continue;
                }

                $fileNameToStore = round(microtime(true)) . '.' . $file->getClientOriginalExtension();
                $file->storeAs($dir, $fileNameToStore, 'public_uploads');
                $newFile = new File([
                    'id_object' => $pc->id,
                    'code_object' => File::CODE_OBJ_PUBLIC_CONSULTATION,
                    'filename' => $fileNameToStore,
                    'doc_type' => $docType,
                    'content_type' => $file->getClientMimeType(),
                    'path' => $dir . $fileNameToStore,
                    'description_' . $code => $validated['description_' . $code] ?? __('custom.public_consultation.doc_type.' . $docType, [], $code),
                    'sys_user' => $request->user()->id,
                    'locale' => $code,
                    'version' => ($version + 1) . '.0'
                ]);
                $newFile->save();
                $newFile->refresh();
                $ocr = new FileOcr($newFile->refresh());
                $ocr->extractText();

                if ($code == 'bg') {
                    $fileEvent = $newFile;
                }
            }

            if ($validated['message']) {
                $comment = new Comments([
                    'object_code' => Comments::PC_OBJ_CODE_MESSAGE,
                    'object_id' => $pc->id,
                    'content' => $validated['message'],
                    'created_at' => Carbon::parse($validated['report_date'] . ' ' . $validated['report_time'])->format('Y-m-d H:i:s'),
                    'user_id' => $request->user()->id
                ]);
                $comment->save();
            }

            if ($fileEvent) {
                $pc->timeline()->save(new Timeline([
                    'event_id' => PublicConsultationTimelineEnum::PUBLISH_PROPOSALS_REPORT->value,
                    'object_id' => $fileEvent->id,
                    'object_type' => File::class
                ]));
            }

            //Generate comments csv and pfd after pk end and show it in public page
            return redirect(route(self::EDIT_ROUTE, $pc) . '#ct-comments')
                ->with('success', trans_choice('custom.public_consultations', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            Log::error('Error save proposal report public consultation' . $e);
            return redirect(url()->previous() . '#ct-comments')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function publish(Request $request, PublicConsultation $item)
    {
        if ($request->user()->cannot('publish', $item)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        try {

            if (Setting::allowPostingToFacebook()) {
                $facebookApi = new Facebook();
                $facebookApi->postToFacebook($item);
            }

            $item->active = 1;
            $item->save();
            return redirect(route(self::LIST_ROUTE))
                ->with('success', trans_choice('custom.public_consultations', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            logError('Publish consultation program (ID ' . $item->id . ')', $e);
            return back()->with('danger', __('messages.system_error'));
        }
    }

    public function unPublish(Request $request, PublicConsultation $item)
    {
        if ($request->user()->cannot('unPublish', $item)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        DB::beginTransaction();
        try {
            $item->active = 0;
            $item->save();
            DB::commit();
            return redirect(route(self::LIST_ROUTE))
                ->with('success', trans_choice('custom.public_consultations', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            logError('Publish consultation (ID ' . $item->id . ')', $e);
            return back()->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Delete existing public consultation record
     *
     * @param PublicConsultation $item
     * @return RedirectResponse
     */
    public function destroy(Request $request, PublicConsultation $item)
    {
        if ($request->user()->cannot('delete', $item)) {
            abort(Response::HTTP_FORBIDDEN);
        }
        try {
            $item->delete();
            if (url()->previous() == route('site.home')) {
                return redirect(route('site.home'))
                    ->with('success', __('custom.the_record') . " " . __('messages.deleted_successfully_m'));
            } else {
                return redirect(url()->previous())
                    ->with('success', trans_choice('custom.public_consultations', 1) . " " . __('messages.deleted_successfully_f'));
            }
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(url()->previous())->with('danger', __('messages.system_error'));

        }
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = PublicConsultation::query();
        if (sizeof($with)) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if (!$item) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return $item;
    }
}

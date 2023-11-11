<?php

namespace App\Http\Controllers\Admin\Consultations;

use App\Enums\DocTypesEnum;
use App\Enums\DynamicStructureTypesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\PublicConsultationContactStoreRequest;
use App\Http\Requests\PublicConsultationContactsUpdateRequest;
use App\Http\Requests\PublicConsultationDocStoreRequest;
use App\Http\Requests\PublicConsultationKdStoreRequest;
use App\Http\Requests\StorePublicConsultationRequest;
use App\Models\ActType;
use App\Models\ConsultationLevel;
use App\Models\Consultations\ConsultationDocument;
use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\PublicConsultation;
use App\Models\ConsultationType;
use App\Models\DynamicStructure;
use App\Models\DynamicStructureColumn;
use App\Models\File;
use App\Models\LinkCategory;
use App\Models\Poll;
use App\Models\ProgramProject;
use App\Models\PublicConsultationContact;
use App\Models\RegulatoryAct;
use App\Services\FileOcr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
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

        $items = PublicConsultation::with(['translation'])
            ->FilterBy($requestFilter)
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
        if( ($item->id && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', PublicConsultation::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $kdRowsDB = $item->id && $item->kd ?
            DynamicStructureColumn::whereIn('id', json_decode($item->kd->active_columns))->orderBy('id')->get()
            : DynamicStructure::where('type', '=', DynamicStructureTypesEnum::CONSULT_DOCUMENTS->value)->where('active', '=', 1)->first()->columns;
        $dsGroups = DynamicStructure::where('type', '=', DynamicStructureTypesEnum::CONSULT_DOCUMENTS->value)->where('active', '=', 1)->first()->groups;

        $kdRows = [];
        foreach ($dsGroups as $kdGroup) {
            foreach ($kdRowsDB as $row) {
                if( $row->dynamic_structure_groups_id && $row->dynamic_structure_groups_id == $kdGroup->id ) {
                    $kdRows[] = $row;
                }
            }
        }
        foreach ($kdRowsDB as $row) {
            if( !$row->dynamic_structure_groups_id ) {
                $kdRows[] = $row;
            }
        }

        $kdValues = [];
        if( $item->kd ) {
            $kdValues = $item->kd->records->pluck('value', 'dynamic_structures_column_id')->toArray();
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = PublicConsultation::translationFieldsProperties();

        $consultationLevels = ConsultationLevel::all();
        $actTypes = ActType::with(['consultationLevel'])->get();
        $programProjects = ProgramProject::all();
        $linkCategories = LinkCategory::all();
        $regulatoryActs = RegulatoryAct::all(); //Нормативни актове номенклатура
        $prisActs = null; //TODO fix me Add them after PRIS module
        $operationalPrograms = OperationalProgram::NotLockedOrByCd($item->id ?? 0)->get();
        $legislativePrograms = LegislativeProgram::NotLockedOrByCd($item->id ?? 0)->get();


        $documents = [];
        foreach ($item->documents as $document){
            $documents[$document->doc_type.'_'.$document->locale][] = $document;
        }
        $polls = $item ? Poll::Active()->NotExpired()->get() : null;

        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields',
            'consultationLevels', 'actTypes', 'programProjects', 'linkCategories', 'regulatoryActs', 'prisActs',
            'operationalPrograms', 'legislativePrograms', 'kdRows', 'dsGroups', 'kdValues', 'polls', 'documents'));
    }

    public function store(Request $request, PublicConsultation $item)
    {
        $user = $request->user();

        if( !$user->institution_id ) {
            return back()->withInput($request->all())->with('danger', __('messages.you_are_not_associate_with_institution'));
        }

        $storeRequest = new StorePublicConsultationRequest();
        $validator = Validator::make($request->all(), $storeRequest->rules());
        if( $validator->fails() ) {
            return back()->withInput($request->all())->withErrors($validator->errors());
        }

        $id = $item->id;

        if( ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', PublicConsultation::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $validated = $validator->validated();
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->active = $request->input('active') ? 1 : 0;

            if( !$id ) {
                $item->importer_institution_id = $user->institution ? $user->institution->id : null;
                $item->responsible_institution_id = $user->institution ? $user->institution->id : null;
                $item->responsible_institution_address = $user->institution ? $user->institution->address : null;
            }

            $item->save();
            $this->storeTranslateOrNew(PublicConsultation::TRANSLATABLE_FIELDS, $item, $validated);

            if( !$id ) {
                $item->reg_num = '#'.$item->id.'-'.displayDate($item->created_at);
                $item->save();
            }

            //Locke program if is selected
            if( isset($validated['legislative_program_id']) ) {
                LegislativeProgram::where('id', '=', (int)$validated['legislative_program_id'])
                    ->where('locked', '=', 0)
                    ->update(['locked' => 1, 'public_consultation_id' => $item->id]);
            }
            if( isset($validated['operational_program_id']) ) {
                OperationalProgram::where('id', '=', (int)$validated['operational_program_id'])
                    ->where('locked', '=', 0)
                    ->update(['locked' => 1, 'public_consultation_id' => $item->id]);
            }

            DB::commit();
            if( isset($validated['stay']) && $validated['stay']) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.public_consultations', 1)." ".($id ? __('messages.updated_successfully_f') : __('messages.created_successfully_f')));
            }
            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.public_consultation', 1)." ".($id ? __('messages.updated_successfully_f') : __('messages.created_successfully_f')));
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
        if( $validator->fails() ) {
            return redirect(url()->previous().'#ct-doc')->withInput($request->all())->withErrors($validator->errors());
        }

        $validated = $validator->validated();
        $item = PublicConsultation::find($validated['id']);

        if( $request->user()->cannot('update', $item) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        // Upload File
        $dir = File::PUBLIC_CONSULTATIONS_UPLOAD_DIR;

        DB::beginTransaction();
        try {
                foreach (DocTypesEnum::docsByActType($validated['act_type']) as $docType) {
                    $fileIds = [];
                    $bgFile = $validated['file_'.$docType.'_bg'] ?? null;
                    $enFile = $validated['file_'.$docType.'_en'] ?? null;
                    //If no file for this type skip next
                    if(!$bgFile && !$enFile){
                        continue;
                    }
                    foreach (['bg', 'en'] as $code) {
                        $version = File::where('locale', '=', $code)
                            ->where('id_object', '=', $item->id)
                            ->where('doc_type', '=', $docType)
                            ->where('code_object', '=', File::CODE_OBJ_PUBLIC_CONSULTATION)
                            ->count();

                        //TODO fix me Ugly way while someone define rules
                        if( !${$code.'File'} ) {
                            //There is no previews version
                            if( !$version ) {
                                if( $code == 'en' && !$enFile && $bgFile ) {
                                    $file = $bgFile;
                                }

                                if( $code == 'bg' && !$bgFile && $enFile ) {
                                    $file = $enFile;
                                }
                            } else {
                                //we have previews version and do not need to copy file for second language
                                $file = null;
                            }
                        } else{
                            $file = ${$code.'File'};
                        }

                        if( is_null($file) ) {
                            continue;
                        }

                        $fileNameToStore = round(microtime(true)).'.'.$file->getClientOriginalExtension();
                        $file->storeAs($dir, $fileNameToStore, 'public_uploads');
                        $newFile = new File([
                            'id_object' => $item->id,
                            'code_object' => File::CODE_OBJ_PUBLIC_CONSULTATION,
                            'filename' => $fileNameToStore,
                            'doc_type' => $docType,
                            'content_type' => $file->getClientMimeType(),
                            'path' => $dir.$fileNameToStore,
                            'description' => __('custom.public_consultation.doc_type.'.$docType),
                            'sys_user' => $request->user()->id,
                            'locale' => $code,
                            'version' => ($version + 1).'.0'
                        ]);
                        $newFile->save();
                        $fileIds[] = $newFile->id;
                        $ocr = new FileOcr($newFile->refresh());
                        $ocr->extractText();
                    }
                    //File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
                    //File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);
                }
            DB::commit();
            if( $validated['stay'] ) {
                return redirect(route(self::EDIT_ROUTE, $item).'#ct-doc' )
                    ->with('success', trans_choice('custom.documents', 2)." ".__('messages.updated_successfully_pl'));
            }
            return redirect(route(self::LIST_ROUTE))
                ->with('success', trans_choice('custom.documents', 2)." ".__('messages.updated_successfully_pl'));
        } catch (\Exception $e){
            Log::error('Error store public consultation(ID'.$item->id.') documents: '.PHP_EOL.'Files: '.json_encode($validated).PHP_EOL.'Error: '.$e);
            DB::rollBack();
            return redirect(url()->previous().'#ct-doc')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

//
//        $fileIds = [];
//
//        foreach (['bg', 'en'] as $code) {
//            $version = File::where('locale', '=', $code)->where('id_object', '=', $objectId)->where('code_object', '=', File::CODE_OBJ_PRIS)->count();
//            $file = isset($validated['file_'.$code]) && $validated['file_'.$code] ? $validated['file_'.$code] : $validated['file_bg'];
//            $fileNameToStore = round(microtime(true)).'.'.$file->getClientOriginalExtension();
//            $file->storeAs($pDir, $fileNameToStore, 'public_uploads');
//            $item = new File([
//                'id_object' => $objectId,
//                'code_object' => $typeObject,
//                'filename' => $fileNameToStore,
//                'content_type' => $file->getClientMimeType(),
//                'path' => $pDir.$fileNameToStore,
//                'description' => $validated['description_'.$code] ?? ($validated['description_'.config('app.default_lang')] ?? null),
//                'sys_user' => $request->user()->id,
//                'locale' => $code,
//                'version' => ($version + 1).'.0'
//            ]);
//            $item->save();
//            $fileIds[] = $item->id;
//            $ocr = new FileOcr($item->refresh());
//            $ocr->extractText();
//        }
//
//        File::find($fileIds[0])->update(['lang_pair' => $fileIds[1]]);
//        File::find($fileIds[1])->update(['lang_pair' => $fileIds[0]]);

    }

    public function storeKd(Request $request)
    {
        $user = $request->user();
        $storeRequest = new PublicConsultationKdStoreRequest();
        $validator = Validator::make($request->all(), $storeRequest->rules());
        if( $validator->fails() ) {
            return redirect(url()->previous().'#ct-kd')->withInput($request->all())->withErrors($validator->errors());
        }

        $validated = $validator->validated();
        $item = PublicConsultation::with(['kd'])->find((int)$validated['id']);

        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( $user->cannot('update', $item) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $update = true;
        DB::beginTransaction();
        try {
            if( !$item->kd ) {
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

            DB::commit();
            if( $validated['stay'] ) {
                return redirect(route(self::EDIT_ROUTE, $item).'#ct-kd' )
                    ->with('success', trans_choice('custom.consult_documents', 1)." ".($update ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
            }
            return redirect(route(self::LIST_ROUTE))
                ->with('success', trans_choice('custom.consult_documents', 1)." ".($update ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect(url()->previous().'#ct-kd')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    public function addContact(PublicConsultationContactStoreRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();
        $item = PublicConsultation::find((int)$validated['pc_id']);

        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( $user->cannot('update', $item) ) {
            return redirect(url()->previous().'#ct-contacts')->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $item->contactPersons()->create([
                'name' => $validated['new_name'],
                'email' => $validated['new_email'],
            ]);

            DB::commit();
            return redirect(route(self::EDIT_ROUTE, $item).'#ct-contacts')
                ->with('success', trans_choice('custom.person_contacts', 1)." ".__('messages.created_successfully_n'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect(url()->previous().'#ct-contacts')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function removeContact(Request $request)
    {
        $user = $request->user();
        $contact = PublicConsultationContact::find((int)$request->input('id'));

        if( !$contact ) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( $user->cannot('update', $contact->publicConsultation) ) {
            return redirect(url()->previous().'#ct-contacts')->with('warning', __('messages.unauthorized'));
        }

        $contact->delete();

        return redirect(route(self::EDIT_ROUTE, $contact->publicConsultation).'#ct-contacts')
            ->with('success', trans_choice('custom.person_contacts', 1)." ".__('messages.deleted_successfully_n'));
    }

    public function updateContacts(PublicConsultationContactsUpdateRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();
        $item = PublicConsultation::find((int)$validated['pc_id']);

        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( $user->cannot('update', $item) ) {
            return redirect(url()->previous().'#ct-contacts')->with('warning', __('messages.unauthorized'));
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
            return redirect(route(self::EDIT_ROUTE, $item).'#ct-contacts')
                ->with('success', trans_choice('custom.person_contacts', 2)." ".__('messages.updated_successfully_pl'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect(url()->previous().'#ct-contacts')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    private function filters($request)
    {
        return array(
            'name' => array(
                'type' => 'text',
                'placeholder' => __('validation.attributes.name'),
                'value' => $request->input('name'),
                'col' => 'col-md-4'
            )
        );
    }

    public function attachPoll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'numeric', 'exists:public_consultation,id'],
            'poll' => ['required', 'numeric', 'exists:poll,id'],
        ]);

        if( $validator->fails() ){
            return redirect(url()->previous().'#ct-pools')->withErrors($validator->errors()->all());
        }

        try {
            $validated = $validator->validated();
            $consultation = PublicConsultation::find($validated['id']);
            $consultation->polls()->attach($validated['poll']);
            return redirect(route(self::EDIT_ROUTE, $consultation).'#ct-polls')
                ->with('success', trans_choice('custom.public_consultations', 2)." ".__('messages.updated_successfully_pl'));
        } catch (\Exception $e) {
            Log::error('Error attach poll to public consultation'.$e);
            return redirect(url()->previous().'#ct-polls')->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = PublicConsultation::query();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return $item;
    }
}

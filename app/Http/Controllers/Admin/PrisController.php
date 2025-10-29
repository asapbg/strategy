<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PrisConnectionStatusEnum;
use App\Enums\PrisDocChangeTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\PrisStoreRequest;
use App\Models\Consultations\PublicConsultation;
use App\Models\LegalActType;
use App\Models\Pris;
use App\Models\PrisChangePris;
use App\Models\StrategicDocuments\Institution;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PrisController extends AdminController
{
    const LIST_ROUTE = 'admin.pris';
    const EDIT_ROUTE = 'admin.pris.edit';
    const PREVIEW_ROUTE = 'admin.pris.preview';
    const STORE_ROUTE = 'admin.pris.store';
    const LIST_VIEW = 'admin.pris.index';
    const EDIT_VIEW = 'admin.pris.edit';

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $requestFilter = $request->all();

        $can_access_orders = $this->canAccessOrders($request);

        $filter = $this->filters($request, $can_access_orders);
        if (!$request->filled('search') && !$request->filled('active')) {
            $requestFilter['active'] = 1;
        }

        $paginate = $filter['paginate'] ?? Pris::PAGINATE;

        $institutions = $requestFilter['institutions'] ?? null;
        $importer = $requestFilter['importer'] ?? null;
        $fullKeyword = isset($requestFilter['fullKeyword']) ? true : false;
        $upperLowerCase = isset($requestFilter['upperLowerCase']) ? true : false;
        $locale = app()->getLocale();
        $condition = $upperLowerCase ? 'LIKE' : 'ILIKE';
        $whereImporter = "pris.old_importers $condition '%$importer%'
                    OR exists (select * from pris_translations t where pris.id = t.pris_id and locale = '$locale' AND importer::text $condition '%$importer%')
                ";
        if ($fullKeyword) {
            $whereImporter = "(";
            $whereImporter .= "pris.old_importers $condition '% $importer %'";
            $whereImporter .= " OR pris.old_importers $condition '% $importer'";
            $whereImporter .= " OR pris.old_importers $condition '$importer %'";
            $whereImporter .= ")";
            $whereImporter .= " OR exists (select * from pris_translations t where pris.id = t.pris_id and locale = '$locale' AND (";
            $whereImporter .= "importer::text $condition '% $importer %'";
            $whereImporter .= " OR importer::text $condition '% $importer'";
            $whereImporter .= " OR importer::text $condition '$importer %'";
            $whereImporter .= "))";
        }

        $items = Pris::select('pris.*')
            ->with(['translations', 'actType.translations', 'institutions.translation'])
            ->leftJoin('pris_translations', function ($j) {
                $j->on('pris_translations.pris_id', '=', 'pris.id')
                    ->where('pris_translations.locale', '=', app()->getLocale());
            })
            ->join('legal_act_type', 'legal_act_type.id', '=', 'pris.legal_act_type_id')
            ->join('legal_act_type_translations', function ($j) {
                $j->on('legal_act_type_translations.legal_act_type_id', '=', 'legal_act_type.id')
                    ->where('legal_act_type_translations.locale', '=', app()->getLocale());
            })
            ->where('pris.asap_last_version', '=', 1)
            ->when($institutions, function ($query) use ($institutions) {
                $query->join(
                    'pris_institution as pi',
                    'pi.pris_id',
                    '=',
                    DB::raw("pris.id AND pi.institution_id IN(" . implode(',', $institutions) . ")")
                )
                    ->join('institution', 'institution.id', '=', DB::raw("pi.institution_id AND institution.active = '1' AND institution.deleted_at IS NULL"))
                    ->join('institution_translations as it', 'it.institution_id', '=', DB::raw("pi.institution_id AND it.locale = '" . app()->getLocale() . "'"));
            })
            ->when($importer, function ($query) use ($whereImporter) {
                $query->where(function ($q) use ($whereImporter) {
                    $q->where('pris.id', '=', 0)->orWhereRaw($whereImporter);
                });
            })
            ->when(!$can_access_orders, function ($query) {
                $query->where('pris.legal_act_type_id', '<>', LegalActType::TYPE_ORDER);
            })
            ->FilterBy($requestFilter)
            ->orderBy('pris.doc_date', 'desc')
            ->paginate($paginate);
        $toggleBooleanModel = 'Pris';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $storeRouteName = self::STORE_ROUTE;
        $previewRouteName = self::PREVIEW_ROUTE;

        return $this->view(self::LIST_VIEW,
            compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName', 'storeRouteName', 'previewRouteName')
        );
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|View
     */
    public function edit(Request $request, int $id)
    {
        $item = $id ? $this->getRecord($id, ['translation', 'tags', 'changedDocs', 'changedDocs.actType', 'changedDocsWithoutRelation']) : new Pris();
        $can_access_orders = $this->canAccessOrders($request);

        if (($id && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', Pris::class)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $legalActTypes = LegalActType::with(['translation'])->Pris()
            ->when(!$can_access_orders, function ($query) {
                $query->where('id', '<>', LegalActType::TYPE_ORDER);
            })
            ->get();
        $institutions = optionsFromModel(Institution::simpleOptionsList());
        $publicConsultations = PublicConsultation::optionsList();
        $translatableFields = Pris::translationFieldsProperties();
        //$tags = Tag::optionsList();
        return $this->view(self::EDIT_VIEW,
            compact('item', 'storeRouteName', 'listRouteName', 'legalActTypes', 'institutions', 'publicConsultations', 'translatableFields')
        );
    }

    /**
     * @param PrisStoreRequest $request
     * @return RedirectResponse
     */
    public function store(PrisStoreRequest $request)
    {
        $validated = $request->validated();
        $id = $validated['id'];
        $item = $id ? $this->getRecord($id) : new Pris();

        if (($id && $request->user()->cannot('update', $item))
            || (!$id && $request->user()->cannot('create', $item))) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $item);
            if (isset($validated['publish']) && $validated['publish']) {
                $fillable['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
            }
            $fillable['in_archive'] = Carbon::parse($validated['doc_date'])->format('Y-m-d') > '1989-12-31' ? 0 : 1;
            $item->fill($fillable);

            $item->save();

            $item->tags()->sync($validated['tags'] ?? []);
            if (!empty($validated['institutions'])) {
                $item->institutions()->sync($validated['institutions']);
            }

            $this->storeTranslateOrNew(Pris::TRANSLATABLE_FIELDS, $item, $validated);
            DB::commit();

            return to_route(self::EDIT_ROUTE, $item->id)
                ->with('success', trans_choice('custom.pris_documents', 1) . " " . ($id ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Save pris document error: ' . $e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function connectDocuments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:pris,id'],
            'connectIds' => ['nullable', 'required_with:connect_type', 'array'],
            'connectIds.*' => ['nullable', 'exists:pris,id'],
            'connect_type' => ['nullable', 'required_without:connect_text', 'numeric'],
            'connect_text' => ['nullable', 'required_without:connect_type,connectIds', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], Response::HTTP_OK);
        }

        $validated = $validator->validated();
        $item = Pris::find((int)$validated['id']);
        if ($request->user()->cannot('update', $item)) {
            return response()->json(['success' => false, 'message' => __('messages.unauthorized')], Response::HTTP_UNAUTHORIZED);
        }

        if (isset($validated['connectIds'])) {
            $item->changedDocs()->attach(
                $validated['connectIds'],
                ['connect_type' => $validated['connect_type'], 'connect_text' => $validated['connect_text']]
            );
            Pris::whereIn('id', $validated['connectIds'])->update(['connection_status' => PrisDocChangeTypeEnum::toStatus($validated['connect_type'])]);
        } else {
            DB::table('pris_change_pris')->insert([
                'pris_id' => $item->id,
                'changed_pris_id' => null,
                'connect_type' => $validated['connect_type'],
                'connect_text' => $validated['connect_text']
            ]);
        }

        return response()->json(['success' => true], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function disconnectDocuments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:pris,id'],
            'disconnect' => ['nullable', 'exists:pris,id'],
            'connect_text' => ['nullable', 'string']
        ]);
        $validated = $validator->validated();
        if (!isset($validated['disconnect']) && !isset($validated['connect_text'])) {
            return response()->json(['error' => 1, 'message' => 'Липсват данни'], Response::HTTP_OK);
        }

        $pris = Pris::find((int)$validated['id']);
        if ($request->user()->cannot('update', $pris)) {
            return response()->json(['error' => 1, 'message' => __('messages.unauthorized')], Response::HTTP_UNAUTHORIZED);
        }

        if (isset($validated['disconnect']) && !is_null($validated['disconnect'])) {
            $pris->changedDocs()->detach($validated['disconnect']);
        }
        if (isset($validated['connect_text']) && !is_null($validated['connect_text'])) {
            PrisChangePris::where('pris_id', $pris->id)->where('connect_text', $validated['connect_text'])->delete();
        }

        return response()->json(['success' => 1], Response::HTTP_OK);
    }

    /**
     * Delete existing pris record
     *
     * @param Request $request
     * @param Pris $item
     * @return RedirectResponse
     */
    public function destroy(Request $request, Pris $item)
    {
        if ($request->user()->cannot('delete', $item)) {
            abort(Response::HTTP_FORBIDDEN);
        }
        try {
            $item->delete();
            return redirect(url()->previous())
                ->with('success', trans_choice('custom.pris_documents', 1) . " " . __('messages.deleted_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(url()->previous())->with('danger', __('messages.system_error'));

        }
    }

    public function ajaxForm(Request $request, Pris $item)
    {
        return view('admin.pris.new_tag_modal', compact('item'));
    }

    public function ajaxStore(Request $request, Pris $item)
    {
        if (!auth()->user()->can('update', $item)) {
            return redirect(route('admin.pris.edit', $item))->with('error', 'Нямате достъп до тази функционалност. Моля свържете се с администратор.');
        }

        $exist = Tag::whereHas('translation', function ($q) use ($request) {
            $q->where('label', '=', $request->input('label_bg'))->where('locale', '=', 'bg');
        })
            ->orWhereHas('translation', function ($q) use ($request) {
                $q->where('label', '=', $request->input('label_en'))->where('locale', '=', 'en');
            })
            ->first();
        if ($exist) {
            return redirect(route('admin.pris.edit', $item))
                ->with('warning', 'Вече съществува Термин с това име: ' . $exist->translate('bg')->label . '|' . $exist->translate('en')?->label);
        }

        try {
            $tag = new Tag();
            $tag->save();
            $this->storeTranslateOrNew(Tag::TRANSLATABLE_FIELDS, $tag, $request->all());
            $item->tags()->attach([$tag->id]);
            return redirect(route('admin.pris.edit', $item))
                ->with('success', trans_choice('custom.nomenclature.tags', 1) . " " . __('messages.created_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route('admin.pris.edit', $item))->with('danger', __('messages.system_error'));
        }
    }

    private function filters($request, $is_in_ip_range)
    {
        return array(
            'legalActTypes' => array(
                'type' => 'select',
                'options' => optionsFromModel(
                    LegalActType::with(['translations'])
                        ->Pris()
                        ->when(!$is_in_ip_range, function ($query) {
                            $query->where('id', '<>', LegalActType::TYPE_ORDER);
                        })
                        ->get(),
                    true
                ),
                'multiple' => true,
                'default' => '',
                'placeholder' => trans_choice('custom.legal_act_types', 1),
                'value' => $request->input('legalActTypes'),
                'col' => 'col-md-12'
            ),
            'about' => array(
                'type' => 'text',
                'placeholder' => __('custom.pris_about'),
                'value' => $request->input('about'),
                'col' => 'col-md-4'
            ),
            'legalReason' => array(
                'type' => 'text',
                'placeholder' => __('custom.pris_legal_reason'),
                'value' => $request->input('legalReason'),
                'col' => 'col-md-4'
            ),
            'tags' => array(
                'type' => 'text',
                'placeholder' => trans_choice('custom.tags', 2),
                'value' => $request->input('tags'),
                'col' => 'col-md-4'
            ),
            'importer' => array(
                'type' => 'text',
                'placeholder' => trans_choice('custom.importers', 1),
                'value' => $request->input('importer'),
                'col' => 'col-md-4'
            ),
            'institutions' => array(
                'type' => 'subjects',
                'placeholder' => trans_choice('custom.institutions', 1),
                'multiple' => true,
                'options' => optionsFromModel(Institution::simpleOptionsList(), true, '', trans_choice('custom.institutions', 1)),
                'value' => request()->input('institutions'),
                'default' => '',
                'col' => 'col-md-8'
            ),
            'fromDate' => array(
                'type' => 'datepicker',
                'value' => $request->input('fromDate'),
                'placeholder' => __('custom.begin_date'),
                'col' => 'col-md-2'
            ),
            'toDate' => array(
                'type' => 'datepicker',
                'value' => $request->input('toDate'),
                'placeholder' => __('custom.end_date'),
                'col' => 'col-md-2'
            ),
            'docNum' => array(
                'type' => 'text',
                'placeholder' => __('custom.document_number'),
                'value' => $request->input('docNum'),
                'col' => 'col-md-2'
            ),
            'year' => array(
                'type' => 'text',
                'placeholder' => __('custom.year'),
                'value' => $request->input('year'),
                'col' => 'col-md-2'
            ),
            'newspaperNumber' => array(
                'type' => 'text',
                'placeholder' => __('custom.newspaper_number'),
                'value' => $request->input('newspaperNumber'),
                'col' => 'col-md-2'
            ),
            'newspaperYear' => array(
                'type' => 'text',
                'placeholder' => __('custom.newspaper_year'),
                'value' => $request->input('newspaperYear'),
                'col' => 'col-md-2'
            ),
            'changes' => array(
                'type' => 'text',
                'placeholder' => __('custom.change_docs'),
                'value' => $request->input('changes'),
                'col' => 'col-md-3'
            ),
            'filesContent' => array(
                'type' => 'text',
                'placeholder' => __('custom.content'),
                'value' => $request->input('filesContent'),
                'col' => 'col-md-9'
            ),
            'fullKeyword' => array(
                'type' => 'checkbox',
                'checked' => $request->input('fullKeyword'),
                'placeholder' => __('custom.full_keyword'),
                'value' => 1,
                'col' => 'col-md-2'
            ),
            'upperLowerCase' => array(
                'type' => 'checkbox',
                'checked' => $request->input('upperLowerCase'),
                'placeholder' => __('custom.upper_lower_case'),
                'value' => 1,
                'col' => 'col-md-2'
            ),
            'logicalАnd' => array(
                'type' => 'checkbox',
                'checked' => $request->input('logicalАnd'),
                'placeholder' => __('custom.logical_and'),
                'value' => 1,
                'col' => 'col-md-2'
            ),
        );
    }

    /**
     * @param $id
     * @param array $with
     * @return Pris|null
     */
    private function getRecord($id, array $with = [])
    {
        $query = Pris::withTrashed()->LastVersion();
        if (sizeof($with)) {
            $query->with($with);
        }
        $pris = $query->find((int)$id);
        if (!$pris) {
            abort(Response::HTTP_NOT_FOUND);
        }
        return $pris;
    }
}

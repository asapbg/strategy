<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrisStoreRequest;
use App\Models\Consultations\PublicConsultation;
use App\Models\LegalActType;
use App\Models\Pris;
use App\Models\StrategicDocuments\Institution;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
        $filter = $this->filters($request);
        if( !$request->filled('search') && !$request->filled('active') ) {
            $requestFilter['active'] = 1;
        }

        $paginate = $filter['paginate'] ?? Pris::PAGINATE;

        $items = Pris::FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'Pris';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $storeRouteName = self::STORE_ROUTE;
        $previewRouteName = self::PREVIEW_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName', 'storeRouteName', 'previewRouteName'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, int $id)
    {
        $item = $id ? $this->getRecord($id, ['translation', 'tags', 'changedDocs', 'changedDocs.actType']) : new Pris();

        if( ($id && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', Pris::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $legalActTypes = LegalActType::with(['translation'])->Pris()->get();
        $institutions = optionsFromModel(Institution::simpleOptionsList());
        $publicConsultations = PublicConsultation::optionsList();
        $translatableFields = Pris::translationFieldsProperties();
        $tags = Tag::optionsList();
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'legalActTypes', 'institutions', 'publicConsultations', 'translatableFields', 'tags'));
    }

    public function store(PrisStoreRequest $request)
    {
        $validated = $request->validated();
        $id = $validated['id'];
        $item = $id ? $this->getRecord($id) : new Pris();

        if( ($id && $request->user()->cannot('update', $item))
            || (!$id && $request->user()->cannot('create', $item)) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $item);
            if(isset($validated['publish']) && $validated['publish']) {
                $fillable['published_at'] = Carbon::now();
            }
            $item->fill($fillable);

            $item->save();

            $item->tags()->sync($validated['tags'] ?? []);
            $item->changedDocs()->sync($validated['change_docs'] ?? []);


            $this->storeTranslateOrNew(Pris::TRANSLATABLE_FIELDS, $item, $validated);
            DB::commit();

            return to_route(self::EDIT_ROUTE, $item->id)
                ->with('success', trans_choice('custom.pris_documents', 1)." ".($id ? __('messages.updated_successfully_m') : __('messages.created_successfully_m')));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Save pris document error: '.$e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    private function filters($request)
    {
        return array(
            'about' => array(
                'type' => 'text',
                'placeholder' => __('custom.pris_about'),
                'value' => $request->input('about'),
                'col' => 'col-md-3'
            ),
            'legal_reason' => array(
                'type' => 'text',
                'placeholder' => __('custom.pris_legal_reason'),
                'value' => $request->input('legal_reason'),
                'col' => 'col-md-3'
            ),
            'legal_act_type' => array(
                'type' => 'select',
                'options' => optionsFromModel(LegalActType::Pris()->get()),
                'multiple' => true,
                'default' => '',
                'placeholder' => trans_choice('custom.legal_act_types', 1),
                'value' => $request->input('legal_act_type'),
                'col' => 'col-12'
            )
        );
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = Pris::withTrashed();
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

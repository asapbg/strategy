<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublicationTypesEnum;
use App\Http\Requests\LanguageFileUploadRequest;
use App\Http\Requests\StorePublicationRequest;
use App\Models\AdvisoryBoard;
use App\Models\CustomRole;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\Publication;
use App\Models\PublicationCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicationController extends AdminController
{
    const LIST_ROUTE = 'admin.publications.index';
    const EDIT_ROUTE = 'admin.publications.edit';
    const STORE_ROUTE = 'admin.publications.store';
    const LIST_VIEW = 'admin.publications.index';
    const EDIT_VIEW = 'admin.publications.edit';
    const MODEL_NAME = 'custom.publications';

    /**
     * Show the public consultations.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $type = $request->route('type') ?? $request->offsetGet('type');
        $source = $request->filled('source') ? $request->get('source') : '';
        $filter = $this->filters($request, $type);
        $paginate = $filter['paginate'] ?? Publication::PAGINATE;
        if( !isset($requestFilter['active']) ) {
            $requestFilter['active'] = 1;
        }

        $items = Publication::with(['translation', 'mainImg'])
            ->whereIn('type', [PublicationTypesEnum::TYPE_LIBRARY->value, PublicationTypesEnum::TYPE_NEWS->value, PublicationTypesEnum::TYPE_ADVISORY_BOARD->value])
            ->FilterBy($requestFilter)
            ->orderBy('id', 'desc')
            ->paginate($paginate);
        $toggleBooleanModel = 'Publication';
        $editRouteName = static::EDIT_ROUTE;
        $listRouteName = static::LIST_ROUTE;
        return $this->view(static::LIST_VIEW,
            compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName', 'type', 'source')
        );
    }

    /**
     * @param Request $request
     * @param Publication $item
     * @return View
     */
    public function edit(Request $request, $type = 0, $item = null)
    {
        $item = $this->getRecord($item, ['mainImg', 'files', 'category', 'translations']);
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', Publication::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
//        $type = $request->route('type') ?? $request->offsetGet('type');
        $source = $request->filled('source') ? $request->get('source') : '';
        $storeRouteName = static::STORE_ROUTE;
        $listRouteName = static::LIST_ROUTE;
        $translatableFields = Publication::translationFieldsProperties();
        $publicationCategories = PublicationCategory::optionsList(true);
        $fieldOfActionCategories = FieldOfAction::advisoryBoard()->with('translations')->select('id')->get();

        if (auth()->user()->hasExactRoles([CustomRole::MODERATOR_ADVISORY_BOARD])) {
            $fieldOfActionCategories = $fieldOfActionCategories->whereIn('id', auth()->user()->getModerateFieldOfActionIds());
            $fieldOfActionCategories = $fieldOfActionCategories->values();
        }

        return $this->view(static::EDIT_VIEW, compact(
            'item',
            'type',
            'storeRouteName',
            'listRouteName',
            'translatableFields',
            'publicationCategories',
            'fieldOfActionCategories',
            'source'
        ));
    }

    /**
     * @param StorePublicationRequest $request
     * @param Publication $item
     * @return RedirectResponse
     */
    public function store(StorePublicationRequest $request, Publication $item)
    {
        $id = $item->id;
        $validated = $request->validated();

        foreach ($this->languages as $lang) {
            foreach (Publication::translationFieldsProperties() as $field => $properties) {
                if (empty($validated['short_content_'.$lang['code']])) {
                    $validated['short_content_'.$lang['code']] = Str::limit(strip_tags($validated['content_'.$lang['code']]), 1000);
                }
            }
        }

        if( ($item->id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', Publication::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            if( empty($validated['slug']) ) {
                $validated['slug'] = Str::slug($validated['title_bg']);
            }

            $itemImg = $validated['file'] ?? null;
            unset($validated['file']);

            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->advisory_boards_id = $validated['adv_board'] ?? null;
            $item->save();

            // Upload File
            if( $item && $itemImg ) {
                $file_name = Str::limit($validated['slug'], 70);
                $fileNameToStore = $file_name.'.'.$itemImg->getClientOriginalExtension();
                // Upload File
                $itemImg->storeAs(File::PUBLICATION_UPLOAD_DIR, $fileNameToStore, 'public_uploads');
                $file = new File([
                    'id_object' => $item->id,
                    'code_object' => File::CODE_OBJ_PUBLICATION,
                    'filename' => $fileNameToStore,
                    'content_type' => $itemImg->getClientMimeType(),
                    'path' => 'files'.DIRECTORY_SEPARATOR.File::PUBLICATION_UPLOAD_DIR.$fileNameToStore,
                    'sys_user' => $request->user()->id,
                ]);
                $file->save();

                if( $file ) {
                    $item->file_id = $file->id;
                    $item->save();
                }
            }

            $langReq = LanguageFileUploadRequest::createFrom($request);
            $this->uploadFileLanguages($langReq, $item->id, File::CODE_OBJ_PUBLICATION, false);

            $this->storeTranslateOrNew(Publication::TRANSLATABLE_FIELDS, $item, $validated);
            DB::commit();


            if(isset($validated['stay']) && $validated['stay']) {
                $source = isset($validated['source']) ? '?source='.$validated['source'] : '';
                $route = route(static::EDIT_ROUTE, ['type' => $validated['type'], 'item' => $item]).$source;
            } else{
                $source = isset($validated['source']) ? '&source='.$validated['source'] : '';
                $route = route(static::LIST_ROUTE).'?type='.$validated['type'].$source;
            }
            return redirect($route)
                ->with('success', trans_choice('custom.publications', 1)." ".($id ? __('messages.updated_successfully_f') : __('messages.created_successfully_f')));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->backWithError('danger', __('messages.system_error'));
        }

    }

    private function filters($request, $type)
    {
        return array(
            'title' => array(
                'type' => 'text',
                'placeholder' => __('validation.attributes.title'),
                'value' => $request->input('title'),
                'col' => 'col-md-3'
            ),
            'category' => array(
                'type' => 'select',
                'placeholder' => trans_choice('custom.categories', 1),
                'value' => $request->input('category'),
                'options' => PublicationCategory::optionsList(true, $type)->pluck('name','id')->toArray(),
                'col' => 'col-md-4'
            )
        );
    }

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = Publication::withTrashed();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            return new Publication();
        }
        return $item;
    }
}

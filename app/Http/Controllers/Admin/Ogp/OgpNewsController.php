<?php

namespace App\Http\Controllers\Admin\Ogp;

use App\Enums\PublicationTypesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageFileUploadRequest;
use App\Http\Requests\StorePublicationRequest;
use App\Models\CustomRole;
use App\Models\File;
use App\Models\Publication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class OgpNewsController extends AdminController
{
    const LIST_ROUTE = 'admin.ogp.news.index';
    const EDIT_ROUTE = 'admin.ogp.news.edit';
    const STORE_ROUTE = 'admin.ogp.news.store';
    const LIST_VIEW = 'admin.ogp.news.index';
    const EDIT_VIEW = 'admin.ogp.news.edit';
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
        $filter = $this->filters($request, $type);
        $paginate = $filter['paginate'] ?? Publication::PAGINATE;
        if( !isset($requestFilter['active']) ) {
            $requestFilter['active'] = 1;
        }

        $userAdvBoards = $request->user()->hasAnyRole(
            [CustomRole::ADMIN_USER_ROLE, CustomRole::SUPER_USER_ROLE, CustomRole::MODERATOR_PARTNERSHIP]) ?
            null
            : ($request->user()->advisoryBoards->count() ? $request->user()->advisoryBoards->pluck('advisory_board_id')->toArray() : [0]);

        $items = Publication::with(['translation', 'mainImg'])
            ->Ogp()
            ->FilterBy($requestFilter)
            ->orderBy('id', 'desc')
            ->paginate($paginate);
        $toggleBooleanModel = 'Publication';
        $editRouteName = static::EDIT_ROUTE;
        $listRouteName = static::LIST_ROUTE;
        return $this->view(static::LIST_VIEW,
            compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName', 'type')
        );
    }

    /**
     * @param Request $request
     * @param Publication $item
     * @return View
     */
    public function edit(Request $request, $item = null)
    {
        $item = $this->getRecord($item, ['mainImg', 'files', 'category', 'translations']);
        if( ($item && $item->id && $request->user()->cannot('updateAdvBoard', $item)) || $request->user()->cannot('createAdvBoard', Publication::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = static::STORE_ROUTE;
        $listRouteName = static::LIST_ROUTE;
        $translatableFields = Publication::translationFieldsProperties();
        return $this->view(static::EDIT_VIEW, compact(
            'item',
            'storeRouteName',
            'listRouteName',
            'translatableFields',
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

//        foreach ($this->languages as $lang) {
//            foreach (Publication::translationFieldsProperties() as $field => $properties) {
//                if (empty($validated['short_content_'.$lang['code']])) {
//                    $validated['short_content_'.$lang['code']] = Str::limit(strip_tags($validated['content_'.$lang['code']]), 1000);
//                }
//            }
//        }

        if( ($item->id && $request->user()->cannot('updateAdvBoard', $item))
            || $request->user()->cannot('createAdvBoard', Publication::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            if( empty($validated['slug']) ) {
                $validated['slug'] = Str::slug($validated['title_bg']);
            }

            $itemImg = $validated['file'] ?? null;
            unset($validated['file']);

            if(isset($validated['published_at'])) {
                $validated['published_at'] = databaseDate($validated['published_at']);
            }

            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->advisory_boards_id = $validated['adv_board'] ?? null;
            if(!$id){
                $item->users_id = auth()->user()->id;
                //cache for search adv board publication by users role
                if($request->user()->hasAnyRole([CustomRole::MODERATOR_ADVISORY_BOARD, CustomRole::MODERATOR_ADVISORY_BOARDS])) {
                    $item->is_adv_board_user = 1;
                }
            }
            $item->save();
            // Upload File
            if( $item && $itemImg ) {
//                $file_name = Str::limit($validated['slug'], 70);
//                $fileNameToStore = $file_name.'.'.->getClientOriginalExtension();
                $fileNameToStore = str_replace('.', '', microtime(true)) . '.' . $itemImg->getClientOriginalExtension();
                // Upload File
                $itemImg->storeAs(File::PUBLICATION_UPLOAD_DIR, $fileNameToStore, 'public_uploads');

                if($item->file_id) {
                    $file = File::find((int)$item->file_id);
                    if($file) {
                        $file->filename = $fileNameToStore;
                        $file->path = File::PUBLICATION_UPLOAD_DIR.$fileNameToStore;
                        $file->content_type = $itemImg->getClientMimeType();
                        $file->sys_user = $request->user()->id;
                        $file->save();
                    }
                } else{
                    $file = new File([
                        'id_object' => $item->id,
                        'code_object' => File::CODE_OBJ_PUBLICATION,
                        'filename' => $fileNameToStore,
                        'content_type' => $itemImg->getClientMimeType(),
                        'path' => File::PUBLICATION_UPLOAD_DIR.$fileNameToStore,
                        'sys_user' => $request->user()->id,
                    ]);

                    $file->save();
                    if( $file ) {
                        $item->file_id = $file->id;
                        $item->save();
                    }
                }
            }

            $langReq = LanguageFileUploadRequest::createFrom($request);
            $this->uploadFileLanguages($langReq, $item->id, File::CODE_OBJ_PUBLICATION, false);

            $this->storeTranslateOrNew(Publication::TRANSLATABLE_FIELDS, $item, $validated);
            DB::commit();

            if(isset($validated['stay']) && $validated['stay']) {
                $route = route(static::EDIT_ROUTE, ['type' => $validated['type'], 'item' => $item]);
            } elseif(isset($validated['stay_in_files']) && $validated['stay_in_files']) {
                $route = route(static::EDIT_ROUTE, ['type' => $validated['type'], 'item' => $item]).'#ct-files';
            } else{
                $route = route(static::LIST_ROUTE).'?type='.$validated['type'];
            }


            return redirect($route)
                ->with('success', trans_choice('custom.news', 1)." ".($id ? __('messages.updated_successfully_f') : __('messages.created_successfully_f')));
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
            )
        );
    }

    /**
     * Delete existing publication
     *
     * @param Publication $item
     * @return RedirectResponse
     */
    public function destroy(Request $request, Publication $item)
    {
        if($request->user()->cannot('deleteAdvBoard', $item)) {
            abort(Response::HTTP_FORBIDDEN);
        }
        try {
            $item->delete();
            return redirect(url()->previous())
                ->with('success', __('custom.news')." ".__('messages.deleted_successfully_m'));
        }
        catch (\Exception $e) {
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

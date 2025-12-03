<?php

namespace App\Http\Controllers\Admin\Nomenclature;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StoreAuthorityAdvisoryBoardRequest;
use App\Models\AuthorityAdvisoryBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthorityAdvisoryBoardController extends AdminController
{

    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $active = $request->get('active') ?? 1;
        $paginate = $filter['paginate'] ?? AuthorityAdvisoryBoard::PAGINATE;

        $items = AuthorityAdvisoryBoard::with(['translation'])
            ->FilterBy($requestFilter)
            ->whereActive($active)
            ->paginate($paginate);
        $toggleBooleanModel = 'AuthorityAdvisoryBoard';
        $editRouteName = 'admin.advisory-boards.nomenclature.authority-advisory-board.edit';
        $listRouteName = 'admin.advisory-boards.nomenclature.authority-advisory-board';

        return $this->view('admin.advisory-boards.nomenclatures.authority-advisory-board.index', compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param AuthorityAdvisoryBoard $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, AuthorityAdvisoryBoard $item)
    {
        if (($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', AuthorityAdvisoryBoard::class)) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = 'admin.advisory-boards.nomenclature.authority-advisory-board.store';
        $listRouteName = 'admin.advisory-boards.nomenclature.authority-advisory-board';
        $translatableFields = AuthorityAdvisoryBoard::translationFieldsProperties();
        return $this->view('admin.advisory-boards.nomenclatures.authority-advisory-board.edit', compact('item', 'storeRouteName', 'listRouteName', 'translatableFields'));
    }

    public function store(StoreAuthorityAdvisoryBoardRequest $request, AuthorityAdvisoryBoard $item)
    {
        $id = $item->id;
        $validated = $request->validated();
        if (
            ($id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', AuthorityAdvisoryBoard::class)
        ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $fillable['created_by'] = !$id ? $request->user()->id : null;
            $item->fill($fillable);
            $item->save();
            $this->storeTranslateOrNew(AuthorityAdvisoryBoard::TRANSLATABLE_FIELDS, $item, $validated);

            if ($id) {
                return redirect(route('admin.advisory-boards.nomenclature.authority-advisory-board.edit', $item))
                    ->with('success', trans_choice('custom.nomenclature.authority_advisory_board', 1) . " " . __('messages.updated_successfully_m'));
            }

            return to_route('admin.advisory-boards.nomenclature.authority-advisory-board')
                ->with('success', trans_choice('custom.nomenclature.authority_advisory_board', 1) . " " . __('messages.created_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
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

    /**
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = AuthorityAdvisoryBoard::query();
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

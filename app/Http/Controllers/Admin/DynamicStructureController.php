<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DynamicStructureColumnStoreRequest;
use App\Models\DynamicStructure;
use App\Models\DynamicStructureColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DynamicStructureController extends AdminController
{
    const LIST_ROUTE = 'admin.dynamic_structures';
    const EDIT_ROUTE = 'admin.dynamic_structures.edit';
    const STORE_ROUTE = 'admin.dynamic_structures.store';
    const LIST_VIEW = 'admin.dynamic_structures.index';
    const EDIT_VIEW = 'admin.dynamic_structures.edit';

    public function index(Request $request)
    {
        $items = DynamicStructure::orderBy('type', 'asc')->get();
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('items', 'editRouteName', 'listRouteName'));
    }

    public function edit(Request $request, DynamicStructure $item)
    {
        if( !$item->id ) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName'));
    }

    public function addColumn(DynamicStructureColumnStoreRequest $request)
    {
        $validated = $request->validated();
        $item = DynamicStructure::find((int)$validated['id']);

        if( !$item ) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( $request->user()->cannot('update', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $maxOrd = max($item->columns->pluck('ord')->toArray());
            $newColumn = new DynamicStructureColumn();
            $fillable = $this->getFillableValidated($validated, $newColumn);
            $fillable['ord'] = $maxOrd +1;
            $fillable['dynamic_structure_id'] = $item->id;
            $fillable['dynamic_structure_groups_id'] = $validated['in_group'] ?? null;

//            dd($validated, $fillable);
            $newColumn->fill($fillable);
            $newColumn->save();
            $this->storeTranslateOrNew(DynamicStructureColumn::TRANSLATABLE_FIELDS, $newColumn, $validated);

            return redirect(route(self::EDIT_ROUTE, $item) )
                ->with('success', trans_choice('custom.dynamic_structures', 1)." ".__('messages.updated_successfully_f'));

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }
}

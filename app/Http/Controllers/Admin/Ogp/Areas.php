<?php

namespace App\Http\Controllers\Admin\Ogp;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\OgpAreaRequest;
use App\Models\OgpArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Areas extends AdminController
{
    public function index(Request $request): \Illuminate\View\View
    {
        $name = ($request->filled('name')) ? $request->get('name') : null;
        $active = $request->filled('active') ? $request->get('active') : 1;
        $paginate = $request->filled('paginate') ? $request->get('paginate') : OgpArea::PAGINATE;

        $items = OgpArea::where('active', $active)
            ->when($name, function ($query, $name) {
                return $query->where('name', 'ILIKE', "%$name%");
            })
            ->paginate($paginate);

        return $this->view('admin.ogp_area.index',
            compact('items',  'items')
        );
    }

    public function create(Request $request): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        return $this->edit($request);
    }

    public function edit(Request $request, $id = 0): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $item = $id ? OgpArea::find($id) : new OgpArea();

        if($request->user()->cannot($id ? 'update' : 'create', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $translatableFields = \App\Models\OgpArea::translationFieldsProperties();

        return $this->view('admin.ogp_area.edit', compact('item', 'translatableFields'));
    }

    public function store(OgpAreaRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $id = $request->get('id');
        $item = $id ? OgpArea::find($id) : new OgpArea();

        if($request->user()->cannot($id ? 'update' : 'create', $item)) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            if($id == 0) {
                $item->author_id = $request->user()->id;
            }
            $item->save();

            $this->storeTranslateOrNew(OgpArea::TRANSLATABLE_FIELDS, $item, $validated);

            return to_route('admin.ogp.area.edit', ['id' => $item->id])
                ->with('success', trans_choice('custom.ogp_areas', 1)." ".__('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

    }

    public function destroy(Request $request, OgpArea $area): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        if($user->cannot('delete', $area)) {
            return response()->json([
                'error' => 1,
                'message' => __('messages.no_rights_to_view_content')
            ]);
        }

        try {
            $area->delete();
            return response()->json([
                'error' => 0,
                'row_id' => $request->get('row_id')
            ]);
        }
        catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => 1,
                'message' => __('messages.system_error')
            ]);
        }
    }
}

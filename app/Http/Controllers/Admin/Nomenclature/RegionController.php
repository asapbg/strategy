<?php

namespace App\Http\Controllers\Admin\Nomenclature;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Region;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegionController extends AdminController
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->get('name');
        $active = $request->get('active') ?? true;
        $paginate = $request->get('paginate') ?? Region::PAGINATE;

        $regions = Region::with(['translation'])
            ->when($name, function ($query, $name) {
                return $query->whereHas('translation', function ($query) use ($name) {
                    $query->where('name', 'ILIKE', "%$name%");
                });
            })
            ->where('active', $active)
            ->orderBy('id')
            ->paginate($paginate);

        return $this->view('admin.nomenclatures.regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws Exception
     */
    public function create(): \Illuminate\View\View
    {
        $translatableFields = Region::translationFieldsProperties();

        return $this->view('admin.nomenclatures.regions.create', compact('translatableFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $region = Region::create([
                'code' => $validated['nuts2_code'],
            ]);

            $this->storeTranslateOrNew(Region::TRANSLATABLE_FIELDS, $region, $validated);

            DB::commit();

            return to_route('admin.nomenclatures.regions.index')
                ->with('success', trans_choice('custom.regions', 1) . " " . __('messages.created_successfully_f'));
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->backWithMessage('danger', __('messages.system_error'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws Exception
     */
    public function edit(Region $region): View
    {
        $region->load(['translations']);

        $translatableFields = Region::translationFieldsProperties();

        return $this->view('admin.nomenclatures.regions.edit', compact('region', 'translatableFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $region->code = $validated['nuts2_code'];

            $this->storeTranslateOrNew(Region::TRANSLATABLE_FIELDS, $region, $validated);

            DB::commit();

            return to_route('admin.nomenclatures.regions.index')
                ->with('success', trans_choice('custom.regions', 1) . ' ' . __('messages.updated_successfully_f'));
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->backWithMessage('danger', __('messages.system_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Region $region): RedirectResponse
    {
        try {
            $region->delete();

            return to_route('admin.nomenclatures.regions.index')
                ->with('success', trans_choice('custom.regions', 1) . " " . __('messages.deleted_successfully_f'));
        } catch (Exception $e) {
            Log::error($e);
            return $this->backWithMessage('danger', __('messages.system_error'));
        }
    }
}

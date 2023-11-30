<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Enums\AdvisoryTypeEnum;
use App\Enums\DocTypesEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\DeleteAdvisoryBoardRequest;
use App\Http\Requests\Admin\AdvisoryBoard\RestoreAdvisoryBoardRequest;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardRequest;
use App\Models\AdvisoryActType;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMember;
use App\Models\AdvisoryBoardSecretaryCouncil;
use App\Models\AdvisoryChairmanType;
use App\Models\AuthorityAdvisoryBoard;
use App\Models\ConsultationLevel;
use App\Models\File;
use App\Models\PolicyArea;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdvisoryBoardController extends AdminController
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $this->authorize('viewAny', AdvisoryBoard::class);

        $items = AdvisoryBoard::withTrashed()->with(['policyArea'])->orderBy('id', 'desc')->paginate(10);

        return $this->view('admin.advisory-boards.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $this->authorize('create', AdvisoryBoard::class);

        $item = new AdvisoryBoard();
        $policy_areas = PolicyArea::orderBy('id')->get();
        $authorities = AuthorityAdvisoryBoard::orderBy('id')->get();
        $advisory_act_types = AdvisoryActType::orderBy('id')->get();
        $advisory_chairman_types = AdvisoryChairmanType::orderBy('id')->get();
        $institutions = Institution::with('translations')->select('id')->orderBy('id')->get();

        return $this->view(
            'admin.advisory-boards.create',
            compact('item', 'policy_areas', 'authorities', 'advisory_act_types', 'advisory_chairman_types', 'institutions')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvisoryBoardRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreAdvisoryBoardRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $item = new AdvisoryBoard();
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            $this->storeTranslateOrNew(AdvisoryBoard::TRANSLATABLE_FIELDS, $item, $validated);

            if (isset($validated['member_name_' . app()->getLocale()])) {
                $member = new AdvisoryBoardMember();
                $member->advisory_board_id = $item->id;
                $member->advisory_type_id = AdvisoryTypeEnum::CHAIRMAN->value;
                $member->advisory_chairman_type_id = AdvisoryChairmanType::VICE_CHAIRMAN;
                $member->save();

                $this->storeTranslateOrNew(AdvisoryBoardMember::TRANSLATABLE_FIELDS, $member, $validated);
            }

            DB::commit();
            return redirect()->route('admin.advisory-boards.index')
                ->with('success', trans_choice('custom.advisory_boards', 1) . " " . __('messages.created_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param AdvisoryBoard $item
     *
     * @return View
     */
    public function show(AdvisoryBoard $item)
    {
        $this->authorize('view', $item);

        $members = $item->members;
        $functions = $item->advisoryFunction?->translations;
        $files = File::query()->where(['id_object' => $item->id, 'code_object' => File::CODE_AB_FUNCTION])->get();

        return $this->view('admin.advisory-boards.view', compact('item', 'members', 'functions', 'files'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AdvisoryBoard $item
     *
     * @return View
     */
    public function edit(AdvisoryBoard $item): View
    {
        $this->authorize('update', $item);

        $policy_areas = PolicyArea::orderBy('id')->get();
        $advisory_chairman_types = AdvisoryChairmanType::orderBy('id')->get();
        $advisory_act_types = AdvisoryActType::orderBy('id')->get();
        $institutions = Institution::with('translations')->select('id')->orderBy('id')->get();
        $consultation_levels = ConsultationLevel::with('translations')->orderBy('id')->get();
        $members = AdvisoryBoardMember::withTrashed()->where('advisory_board_id', $item->id)->orderBy('id')->get();
        $function = $item->advisoryFunction;
        $secretariat = $item->secretariat;
        $authorities = AuthorityAdvisoryBoard::orderBy('id')->get();
        $secretaries_council = AdvisoryBoardSecretaryCouncil::withTrashed()->where('advisory_board_id', $item->id)->get();
        $meetings = $item->meetings;

        $function_files = File::query()
            ->when(request()->get('show_deleted_functions_files', 0) == 1, function ($query) {
                $query->withTrashed()->orderBy('deleted_at', 'desc');
            })
            ->where(['id_object' => $item->id, 'code_object' => File::CODE_AB_FUNCTION, 'doc_type' => DocTypesEnum::AB_FUNCTION])
            ->get();

        $secretariat_files = File::query()
            ->when(request()->get('show_deleted_secretariat_files', 0) == 1, function ($query) {
                $query->withTrashed()->orderBy('deleted_at', 'desc');
            })
            ->where(['id_object' => $item->id, 'code_object' => File::CODE_AB_FUNCTION, 'doc_type' => DocTypesEnum::AB_SECRETARIAT])
            ->get();

        $regulatory_framework_files = File::query()
            ->when(request()->get('show_deleted_regulatory_files', 0) == 1, function ($query) {
                $query->withTrashed()->orderBy('deleted_at', 'desc');
            })
            ->where(['id_object' => $item->id, 'code_object' => File::CODE_AB_FUNCTION, 'doc_type' => DocTypesEnum::AB_REGULATORY_FRAMEWORK])
            ->get();

        return $this->view(
            'admin.advisory-boards.edit',
            compact(
                'item',
                'policy_areas',
                'advisory_chairman_types',
                'advisory_act_types',
                'institutions',
                'consultation_levels',
                'members',
                'function',
                'function_files',
                'authorities',
                'secretaries_council',
                'secretariat',
                'secretariat_files',
                'regulatory_framework_files',
                'meetings',
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvisoryBoardRequest $request
     * @param AdvisoryBoard              $item
     *
     * @return RedirectResponse
     */
    public function update(UpdateAdvisoryBoardRequest $request, AdvisoryBoard $item): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            $this->storeTranslateOrNew(AdvisoryBoard::TRANSLATABLE_FIELDS, $item, $validated);

            DB::commit();
            return redirect()->route('admin.advisory-boards.edit', $item)
                ->with('success', trans_choice('custom.advisory_boards', 1) . " " . __('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteAdvisoryBoardRequest $request
     * @param AdvisoryBoard              $item
     *
     * @return RedirectResponse
     */
    public function destroy(DeleteAdvisoryBoardRequest $request, AdvisoryBoard $item): RedirectResponse
    {
        try {
            $item->delete();

            return redirect()->route('admin.advisory-boards.index')
                ->with('success', trans_choice('custom.advisory_boards', 1) . " $item->name " . __('messages.deleted_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route('admin.legislative_initiatives.index', $item))->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Restore the specified resource.
     */
    public function restore(RestoreAdvisoryBoardRequest $request, AdvisoryBoard $item)
    {
        try {
            $item->restore();

            return redirect()->route('admin.advisory-boards.index')
                ->with('success', trans_choice('custom.advisory_boards', 1) . " $item->name " . __('messages.restored_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('danger', __('messages.system_error'));
        }
    }
}

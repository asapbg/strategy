<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Enums\DocTypesEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\AdvisoryBoard\DeleteAdvisoryBoardRequest;
use App\Http\Requests\Admin\AdvisoryBoard\RestoreAdvisoryBoardRequest;
use App\Http\Requests\Admin\AdvisoryBoard\StoreAdvisoryBoardRequest;
use App\Http\Requests\Admin\AdvisoryBoard\UpdateAdvisoryBoardRequest;
use App\Models\AdvisoryActType;
use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardCustom;
use App\Models\AdvisoryBoardFunction;
use App\Models\AdvisoryBoardMeeting;
use App\Models\AdvisoryBoardModerator;
use App\Models\AdvisoryChairmanType;
use App\Models\AuthorityAdvisoryBoard;
use App\Models\ConsultationLevel;
use App\Models\CustomRole;
use App\Models\File;
use App\Models\PolicyArea;
use App\Models\StrategicDocuments\Institution;
use App\Models\User;
use App\Services\AdvisoryBoard\AdvisoryBoardNpoService;
use App\Services\AdvisoryBoard\AdvisoryBoardService;
use Carbon\Carbon;
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

        $keywords = request()->offsetGet('keywords');
        $status = request()->offsetGet('status');

        $items = AdvisoryBoard::withTrashed();

        if (!auth()->user()->hasRole(CustomRole::ADMIN_USER_ROLE) && !auth()->user()->hasRole(CustomRole::MODERATOR_ADVISORY_BOARDS)) {
            $items = $items->moderatorListing();
        }

        $items = $items->with(['policyArea', 'translations'])
            ->where(function ($query) use ($keywords) {
                $query->when(!empty($keywords) && is_numeric($keywords), function ($query) use ($keywords) {
                    $query->where('id', $keywords);
                })
                    ->when(!empty($keywords) && !is_numeric($keywords), function ($query) use ($keywords) {
                        $query->whereHas('translations', function ($query) use ($keywords) {
                            $query->where('name', 'like', '%' . $keywords . '%');
                        });
                    });
            })
            ->when($status != '', function ($query) use ($status) {
                $query->where('active', $status == '0' ? 'false' : 'true');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

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

            $npo_service = app(AdvisoryBoardNpoService::class, ['board' => $item]);

            if (isset($validated['npo_bg'])) {
                foreach ($validated['npo_bg'] as $key => $presenter) {
                    $names = [$presenter];
                    $names[] = $validated['npo_en'][$key] ?? $presenter;
                    $npo_service->storeMember($names);
                }
            }

            $service = app(AdvisoryBoardService::class, ['board' => $item]);
            $service->createDependencyTables();

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

        $archive_category = request()->get('archive_category', '');

        $members = $item->allMembers;
        $functions = $item->advisoryFunction?->translations;
        $files = File::query()->where(['id_object' => $item->advisoryFunction?->id, 'code_object' => File::CODE_AB])->get();
        $secretariat = $item->secretariat;
        $secretariat_files = File::query()
            ->when(request()->get('show_deleted_secretariat_files', 0) == 1, function ($query) {
                $query->withTrashed()->orderBy('deleted_at', 'desc');
            })
            ->where(['id_object' => $secretariat?->id, 'code_object' => File::CODE_AB, 'doc_type' => DocTypesEnum::AB_SECRETARIAT])
            ->get();
        $regulatory_framework_files = File::query()
            ->when(request()->get('show_deleted_regulatory_files', 0) == 1, function ($query) {
                $query->withTrashed()->orderBy('deleted_at', 'desc');
            })
            ->where(['id_object' => $item->id, 'code_object' => File::CODE_AB, 'doc_type' => DocTypesEnum::AB_ORGANIZATION_RULES])
            ->get();
        $meetings_decisions_files = File::query()
            ->when(request()->get('show_deleted_decisions_files', 0) == 1, function ($query) {
                $query->withTrashed()->orderBy('deleted_at', 'desc');
            })
            ->where(['id_object' => $item->id, 'code_object' => File::CODE_AB, 'doc_type' => DocTypesEnum::AB_MEETINGS_AND_DECISIONS])
            ->get();
        $sections = AdvisoryBoardCustom::query()->with(['files' => function ($query) {
            $query->when(request()->get('show_deleted_custom_files', 0) == 1, function ($query) {
                $query->withTrashed();
            });
        }])
            ->when(request()->get('show_deleted_sections', 0) == 1, function ($query) {
                $query->withTrashed();
            })
            ->where('advisory_board_id', $item->id)
            ->orderBy('order')->get();

        $archive = collect();

        if ($archive_category == '1') {
            $archive = AdvisoryBoardMeeting::with('files')
                ->where('advisory_board_id', $item->id)
                ->whereYear('created_at', '<', Carbon::now()->year)
                ->orderBy('created_at', 'desc')->paginate(10);
        }

        if ($archive_category == '2') {
            $archive = AdvisoryBoardFunction::with('files')
                ->where('advisory_board_id', $item->id)
                ->where('status', StatusEnum::INACTIVE->value)
                ->orderBy('created_at', 'desc')->paginate(10);
        }

        return $this->view('admin.advisory-boards.view',
            compact(
                'item',
                'members',
                'functions',
                'files',
                'secretariat',
                'secretariat_files',
                'regulatory_framework_files',
                'meetings_decisions_files',
                'sections',
                'archive',
            )
        );
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

        $archive_category = request()->get('archive_category', '');

        $query = $item->newQuery();
        $item = $query->with(['advisoryFunctions' => function ($query) {
            $query->when(request()->get('show_deleted_functions', 0) == 1, function ($query) {
                $query->withTrashed();
            })->with('files', function ($query) {
                $query->when(request()->get('show_deleted_functions_files', 0) == 1, function ($query) {
                    $query->withTrashed();
                });
            });
        }, 'organizationRule' => function ($query) {
            $query->with('files');
        }, 'establishment' => function ($query) {
            $query->with(['files', 'translations']);
        }, 'meetings' => function ($query) {
            $query->when(request()->get('show_deleted_meetings', 0) == 1, function ($query) {
                $query->withTrashed()->orderBy('next_meeting', 'desc')->paginate(AdvisoryBoardMeeting::PAGINATE);
            })->whereYear('next_meeting', '>=', now()->year);
        }, 'customSections' => function ($query) {
            $query->with(['files' => function ($query) {
                $query->when(request()->get('show_deleted_custom_files', 0) == 1, function ($query) {
                    $query->withTrashed();
                });
            }, 'translations'])->when(request()->get('show_deleted_sections', 0) == 1, function ($query) {
                $query->withTrashed();
            })->orderBy('order');
        }, 'members' => function ($query) {
            $query->withTrashed()->with('translations')->orderBy('id');
        }, 'npos' => function ($query) {
            $query->with('translations');
        }])->find($item->id);

        $policy_areas = PolicyArea::with('translations')->orderBy('id')->get();
        $advisory_chairman_types = AdvisoryChairmanType::with('translations')->orderBy('id')->get();
        $advisory_act_types = AdvisoryActType::with('translations')->orderBy('id')->get();
        $institutions = Institution::with('translations')->select('id')->orderBy('id')->get();
        $consultation_levels = ConsultationLevel::with('translations')->orderBy('id')->get();
        $secretariat = $item->secretariat;
        $authorities = AuthorityAdvisoryBoard::with('translations')->orderBy('id')->get();
        $all_users = User::select(['id', 'username'])
            ->orderBy('username')
            ->whereNotIn('id', function ($query) {
                $query->select('user_id')->from((new AdvisoryBoardModerator())->getTable());
            })->get();
        $moderators = $item->moderators;

        $archive = collect();

        if ($archive_category == '1') {
            $archive = AdvisoryBoardMeeting::with('files')
                ->where('advisory_board_id', $item->id)
                ->whereYear('next_meeting', '<', Carbon::now()->year)
                ->orderBy('next_meeting', 'desc')->paginate(AdvisoryBoardMeeting::PAGINATE);
        }

        if ($archive_category == '2') {
            $archive = AdvisoryBoardFunction::with('files')
                ->where('advisory_board_id', $item->id)
                ->where('status', StatusEnum::INACTIVE->value)
                ->orderBy('created_at', 'desc')->paginate(10);
        }

        $secretariat_files = request()->get('show_deleted_secretariat_files', 0) == 1 ? $secretariat?->allFiles : $secretariat?->files;
        $regulatory_framework_files = request()->get('show_deleted_regulatory_files', 0) == 1 ? $item->regulatoryAllFiles : $item->regulatoryFiles;

        return $this->view(
            'admin.advisory-boards.edit',
            compact(
                'item',
                'policy_areas',
                'advisory_chairman_types',
                'advisory_act_types',
                'institutions',
                'consultation_levels',
                'authorities',
                'secretariat',
                'secretariat_files',
                'regulatory_framework_files',
                'archive',
                'all_users',
                'moderators',
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

            $npo_service = app(AdvisoryBoardNpoService::class, ['board' => $item]);

            $npo_service->removeCompletely();

            if (isset($validated['npo_bg'])) {
                foreach ($validated['npo_bg'] as $key => $presenter) {
                    $names = [$presenter];
                    $names[] = $validated['npo_en'][$key] ?? $presenter;
                    $npo_service->storeMember($names);
                }
            }

            if (!isset($validated['has_npo_presence'])) {
                $item->has_npo_presence = false;
                $item->save();
            }

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
            $item->active = false;
            $item->save();
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

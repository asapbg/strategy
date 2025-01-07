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
use App\Models\AdvisoryBoardNpo;
use App\Models\AdvisoryChairmanType;
use App\Models\AuthorityAdvisoryBoard;
use App\Models\ConsultationLevel;
use App\Models\CustomRole;
use App\Models\FieldOfAction;
use App\Models\File;
use App\Models\StrategicDocuments\Institution;
use App\Models\User;
use App\Services\AdvisoryBoard\AdvisoryBoardNpoService;
use App\Services\AdvisoryBoard\AdvisoryBoardService;
use App\Services\Notifications;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

        $limitItems = false;
        if (!(auth()->user()->hasAnyRole([CustomRole::ADMIN_USER_ROLE,CustomRole::MODERATOR_ADVISORY_BOARDS]))) {
            $limitItems = true;
        }
        $items = $items->with(['policyArea', 'translations'])
            ->where(function ($query) use ($keywords) {
//                $query->when(!empty($keywords) && is_numeric($keywords), function ($query) use ($keywords) {
//                    $query->where('id', $keywords);
//                })
                    $query->when(!empty($keywords) && !is_numeric($keywords), function ($query) use ($keywords) {
                        $query->whereHas('translations', function ($query) use ($keywords) {
                            $query->where('name', 'ilike', '%' . $keywords . '%');
                        });
                    });
            })
            ->when($status != '' && $status > -1, function ($query) use ($status) {
                $query->where('active', $status == '0' ? 'false' : 'true');
            })
            ->when($limitItems, function ($query){
                $query->whereHas('moderators', function ($query) {
                    $query->where('user_id', '=', auth()->user()->id);
                });
            })
            ->orderBy('active', 'desc')
            ->orderByTranslation('name')
            ->paginate(AdvisoryBoard::PAGINATE);

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
        $field_of_actions = FieldOfAction::advisoryBoard()->orderByTranslation('name')->where('active', true)->get();
        $authorities = AuthorityAdvisoryBoard::orderBy('id')->get();
        $advisory_act_types = AdvisoryActType::orderBy('id')->get();
        $advisory_chairman_types = AdvisoryChairmanType::orderBy('id')->get();
        $institutions = Institution::with('translations')->select('id')->orderBy('id')->get();
        $translatableFields = AdvisoryBoard::translationFieldsProperties();
        $all_users = User::select(['id', 'first_name', 'middle_name', 'last_name', 'email', 'phone', 'job', 'unit', 'institution_id'])
            ->with(['institution' => fn($q) => $q->with(['translations']), 'moderateAdvisoryBoards' => fn($q) => $q->with(['board' => fn($q) => $q->with(['translations'])])])
            ->orderByRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) ASC")
            ->where('user_type', '=', 1)
            ->get();

        return $this->view('admin.advisory-boards.create', compact(
            'item', 'field_of_actions', 'authorities', 'advisory_act_types',
            'advisory_chairman_types', 'institutions', 'translatableFields', 'all_users',
            )
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
            $itemImg = $validated['file'] ?? null;
            unset($validated['file']);

            $item = new AdvisoryBoard();
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->save();

            // Upload File
            if( $item && $itemImg ) {
                $file_name = Str::limit($itemImg->getClientOriginalName(), 70);
                $fileNameToStore = $file_name.'.'.$itemImg->getClientOriginalExtension();
                // Upload File
                $itemImg->storeAs(File::ADVISORY_BOARD_UPLOAD_DIR, $fileNameToStore, 'public_uploads');
                $file = new File([
                    'id_object' => $item->id,
                    'code_object' => File::CODE_AB,
                    'filename' => $fileNameToStore,
                    'content_type' => $itemImg->getClientMimeType(),
                    'path' => 'files'.DIRECTORY_SEPARATOR.File::ADVISORY_BOARD_UPLOAD_DIR.$fileNameToStore,
                    'sys_user' => $request->user()->id,
                ]);
                $file->save();

                if( $file ) {
                    $item->file_id = $file->id;
                    $item->save();
                }
            }

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

            // store moderator
            if (!empty($validated['moderator_id'])) {
                $moderator = AdvisoryBoardModerator::create([
                    'advisory_board_id' => $item->id,
                    'user_id'           => $validated['moderator_id'],
                ]);

                $moderator->user->assignRole(CustomRole::MODERATOR_ADVISORY_BOARD);
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
            })->whereYear('working_year', '>=', now()->year);
        }, 'organizationRule' => function ($query) {
            $query->with('files');
        }, 'establishment' => function ($query) {
            $query->with(['files', 'translations']);
        }, 'meetings' => function ($query) {
            $query->when(request()->get('show_deleted_meetings', 0) == 1, function ($query) {
                $query->withTrashed()->where('next_meeting', '>=', Carbon::now()->startOfYear()->format('Y-m-d H:i:s'))->orderBy('next_meeting', 'desc')->paginate(AdvisoryBoardMeeting::PAGINATE);
            })->where('next_meeting', '>=', Carbon::now()->startOfYear()->format('Y-m-d H:i:s'))
                ->orderBy('next_meeting', 'desc');
        }, 'customSections' => function ($query) {
            $query->with(['files' => function ($query) {
                $query->when(request()->get('show_deleted_custom_files', 0) == 1, function ($query) {
                    $query->withTrashed();
                });
            }, 'translations'])->when(request()->get('show_deleted_sections', 0) == 1, function ($query) {
                $query->withTrashed();
            })->orderBy('order');
        }, 'members' => function ($query) {
            $query->with('translations')->orderBy('id');
        }, 'npos' => function ($query) {
            $query->with('translations');
        }])->find($item->id);

        $field_of_actions = FieldOfAction::advisoryBoard()->with('translations')->orderByTranslation('name')->where('active', true)->get();
        $advisory_chairman_types = AdvisoryChairmanType::with('translations')->orderBy('id')->get();
        $advisory_act_types = AdvisoryActType::with('translations')->orderBy('id')->get();
        $institutions = Institution::with('translations')->select('id')->orderBy('id')->get();
        $consultation_levels = ConsultationLevel::with('translations')->orderBy('id')->get();
        $secretariat = $item->secretariat;
        $authorities = AuthorityAdvisoryBoard::with('translations')->orderBy('id')->get();

        $moderators = $item->moderators;
        $all_users = User::select(['id', 'first_name', 'middle_name', 'last_name', 'email', 'phone', 'job', 'unit', 'institution_id'])
            ->with(['institution' => fn($q) => $q->with(['translations']), 'moderateAdvisoryBoards' => fn($q) => $q->with(['board' => fn($q) => $q->with(['translations'])])])
            ->orderByRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) ASC")
            ->where('user_type', '=', 1)
            ->when($moderators, function ($q) use($moderators){
                if($moderators->count()){
                    $q->whereNotIn('id', $moderators->pluck('user_id')->toArray());
                }
            })->get();

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
                ->whereYear('working_year', '<', Carbon::now()->year)
                ->orderBy('working_year', 'desc')->paginate(10);
        }

        $secretariat_files = request()->get('show_deleted_secretariat_files', 0) == 1 ? $secretariat?->allFiles : $secretariat?->files;
        $regulatory_framework_files = request()->get('show_deleted_regulatory_files', 0) == 1 ? $item->regulatoryAllFiles : $item->regulatoryFiles;
        $translatableFields = AdvisoryBoard::translationFieldsProperties();
        return $this->view(
            'admin.advisory-boards.edit',
            compact(
                'item',
                'field_of_actions',
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
                'translatableFields'
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
            $validated['meetings_per_year'] = isset($validated['no_meetings_per_year']) ? null : $validated['meetings_per_year'];

            $itemImg = $validated['file'] ?? null;
            unset($validated['file']);

            $fillable = $this->getFillableValidated($validated, $item);
            $changes = $this->mainChanges(AdvisoryBoard::CHANGEABLE_FIELDS, $item, $validated);
            $item->fill($fillable);
            $changes = array_merge($this->translateChanges(AdvisoryBoard::TRANSLATABLE_FIELDS, $item, $validated), $changes);//use it to send detail changes in notification

            $item->save();

            // Upload File
            if( $item && $itemImg ) {
                $file_name = Str::limit($itemImg->getClientOriginalName(), 70);
                $fileNameToStore = $file_name.'.'.$itemImg->getClientOriginalExtension();
                // Upload File
                $itemImg->storeAs(File::ADVISORY_BOARD_UPLOAD_DIR, $fileNameToStore, 'public_uploads');

                if($item->file_id) {
                    $file = File::find($item->file_id);
                    $file->filename = $fileNameToStore;
                    $file->content_type = $itemImg->getClientMimeType();
                    $file->path = 'files'.DIRECTORY_SEPARATOR.File::ADVISORY_BOARD_UPLOAD_DIR.$fileNameToStore;
                    $file->sys_user = $request->user()->id;
                    $file->save();
                } else{
                    $file = new File([
                        'id_object' => $item->id,
                        'code_object' => File::CODE_AB,
                        'filename' => $fileNameToStore,
                        'content_type' => $itemImg->getClientMimeType(),
                        'path' => 'files'.DIRECTORY_SEPARATOR.File::ADVISORY_BOARD_UPLOAD_DIR.$fileNameToStore,
                        'sys_user' => $request->user()->id,
                    ]);
                    $file->save();
                    $item->file_id = $file->id;
                    $item->save();
                }
            }

            $this->storeTranslateOrNew(AdvisoryBoard::TRANSLATABLE_FIELDS, $item, $validated);

            $npo_service = app(AdvisoryBoardNpoService::class, ['board' => $item]);
            //$npo_service->removeCompletely();
            $itemOldNpoIds = $item->npos->pluck('id', 'id')->toArray();
            if(isset($validated['npo_id']) && sizeof($validated['npo_id'])) {
                foreach ($validated['npo_id'] as $key => $kid) {
                    $npo = AdvisoryBoardNpo::find((int)$kid);
                    if($npo){
                        foreach (config('available_languages')  as $lang){
                            $npo->translateOrNew($lang['code'])->name = $validated['npo_'.$lang['code']][$key] ?? '';
                        }
                        $npo->save();
                    } else{
                        $newNpo = $item->npos()->create();
                        foreach (config('available_languages')  as $lang){
                            $newNpo->translateOrNew($lang['code'])->name = $validated['npo_'.$lang['code']][$key] ?? '';
                        }
                        $newNpo->save();
                    }
                    if(isset($itemOldNpoIds[$kid])){
                        unset($itemOldNpoIds[$kid]);
                    }
                }
                if(isset($itemOldNpoIds) && sizeof($itemOldNpoIds)){
                    $npo_service->removeCompletely($itemOldNpoIds);
                }
            } else{
                $npo_service->removeCompletely();
            }

            if (!isset($validated['has_npo_presence'])) {
                $item->has_npo_presence = false;
                $item->save();
            }

            DB::commit();

            //TODO alert adb board modeRATOR
            if(sizeof($changes)){
                $notifyService = new Notifications();
                $notifyService->advChanges($item, request()->user(), __('custom.base_information'), $changes);
            }

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

            //->route('admin.advisory-boards.index')
            return redirect(url()->previous())
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
            //->route('admin.advisory-boards.index')
            return redirect(url()->previous())
                ->with('success', trans_choice('custom.advisory_boards', 1) . " $item->name " . __('messages.restored_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Publish the specified resource.
     */
    public function publish(Request $request, AdvisoryBoard $item)
    {
        try {
            $item->public = true;
            $item->save();

            //->route('admin.advisory-boards.index')
            return redirect(url()->previous())
                ->with('success', trans_choice('custom.advisory_boards', 1) . " $item->name " . __('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('danger', __('messages.system_error'));
        }
    }

    /**
     * Draft the specified resource.
     */
    public function draft(Request $request, AdvisoryBoard $item)
    {
        try {
            $item->public = false;
            $item->save();

            //->route('admin.advisory-boards.index')
            return redirect(url()->previous())
                ->with('success', trans_choice('custom.advisory_boards', 1) . " $item->name " . __('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('danger', __('messages.system_error'));
        }
    }
}

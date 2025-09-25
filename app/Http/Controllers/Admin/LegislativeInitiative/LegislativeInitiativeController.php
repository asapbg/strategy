<?php

namespace App\Http\Controllers\Admin\LegislativeInitiative;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Consultations\OperationalProgramController;
use App\Http\Requests\Admin\LegislativeInitiative\AdminIndexLegislativeInitiativeRequest;
use App\Http\Requests\Admin\LegislativeInitiative\AdminUpdateLegislativeInitiativeRequest;
use App\Http\Requests\Admin\LegislativeInitiative\AdminViewLegislativeInitiativeRequest;
use App\Http\Requests\DeleteLegislativeInitiativeRequest;
use App\Http\Requests\RestoreLegislativeInitiativeRequest;
use App\Models\Consultations\OperationalProgramRow;
use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeComment;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LegislativeInitiativeController extends AdminController
{

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(AdminIndexLegislativeInitiativeRequest $request)
    {
        $institutions = Institution::select('id')->orderBy('id')->with('translation')->get();
        $count_results = $request->get('count_results', 10);
        $keywords = $request->offsetGet('keywords');
        $institution = $request->offsetGet('institution');
        $status = $request->offsetGet('status');
        $only_deleted = $request->offsetGet('only_deleted');
        $items = LegislativeInitiative::select('legislative_initiative.*')
            ->when($only_deleted, fn($q) => $q->onlyTrashed())
            ->join('law', 'law.id', '=', 'legislative_initiative.law_id')
            ->when(!empty($institution), function ($query) use ($institution) {
                $query->join('law_institution', function ($query) use ($institution) {
                    $query->on('law_institution.law_id', '=', 'law.id')
                        ->whereIn('law_institution.institution_id',$institution);
                });
            })
            ->join('law_translations', function ($q){
                $q->on('law_translations.law_id', '=', 'law.id')->where('law_translations.locale', '=', app()->getLocale());
            })
            ->when(!empty($keywords), function ($query) use ($keywords){
                $query->where('law_translations.name', 'ilike', '%' . $keywords . '%');
                $query->orWhere('legislative_initiative.description', 'ilike', '%' . $keywords . '%')
                    ->orWhereHas('user', function ($query) use ($keywords) {
                        $query->where('first_name', 'like', '%' . $keywords . '%');
                        $query->orWhere('middle_name', 'like', '%' . $keywords . '%');
                        $query->orWhere('last_name', 'like', '%' . $keywords . '%');
                    });
            })
            // Status search
            ->when(!empty($status), function ($query) use ($status) {
                $query->where('legislative_initiative.status', $status);
            })
            ->groupBy('legislative_initiative.id')
            ->paginate($count_results);

        return $this->view('admin.legislative_initiatives.index', compact('institutions', 'items'));
    }

    /**
     * @param AdminViewLegislativeInitiativeRequest $request
     * @param LegislativeInitiative                 $item
     *
     * @return View
     */
    public function show(AdminViewLegislativeInitiativeRequest $request, LegislativeInitiative $item): View
    {
        $show_deleted_comments = $request->offsetGet('show_deleted_comments');

        $query = LegislativeInitiativeComment::query();

        if ($show_deleted_comments == '1') {
            $query = $query->withTrashed();
        }

        $comments = $query->where('legislative_initiative_id', $item->id)->get();
        $needSupport = ($item->cap - $item->countSupport());
        return $this->view('admin.legislative_initiatives.show', compact('item', 'comments', 'needSupport'));
    }

    public function update(AdminUpdateLegislativeInitiativeRequest $request, LegislativeInitiative $item)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $item->fill($validated);
            $item->save();

            DB::commit();

            return redirect()->back()->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.updated_successfully_f'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function destroy(DeleteLegislativeInitiativeRequest $request, LegislativeInitiative $item)
    {
        try {
            $item->delete();

            return redirect(route('admin.legislative_initiatives.index', $item))
                ->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.deleted_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route('admin.legislative_initiatives.index', $item))->with('danger', __('messages.system_error'));
        }
    }

    public function restore(RestoreLegislativeInitiativeRequest $request, LegislativeInitiative $item)
    {
        try {
            $item->restore();

            return redirect(route('admin.legislative_initiatives.index', $item))
                ->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.restored_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('danger', __('messages.system_error'));
        }
    }
}

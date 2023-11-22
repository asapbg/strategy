<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreLegislativeInitiativeRequest;
use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeComment;
use App\Models\PolicyArea;
use App\Models\RegulatoryAct;
use App\Models\RegulatoryActType;
use App\Models\StrategicDocuments\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use function Clue\StreamFilter\fun;

class LegislativeInitiativeController extends AdminController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->title_singular = 'Законодателна инициатива';
    }

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $politicRanges = PolicyArea::orderBy('id')->get();
        $institutions = Institution::select('id')->orderBy('id')->with('translation')->get();
        $countResults = $request->get('count_results', 10);
        $keywords = $request->offsetGet('keywords');
        $politicRange = $request->offsetGet('politic-range');
        $filter = $this->filters($request);

        $items = LegislativeInitiative::withTrashed()->with(['comments'])
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $query->where('description', 'like', '%' . $keywords . '%')
                    ->orWhereHas('user', function ($query) use ($keywords) {
                        $query->where('first_name', 'like', '%' . $keywords . '%');
                        $query->orWhere('middle_name', 'like', '%' . $keywords . '%');
                        $query->orWhere('last_name', 'like', '%' . $keywords . '%');
                    });
            })
            ->paginate($countResults);

        return $this->view('admin.legislative_initiatives.index', compact('filter', 'items'));
    }

    private function filters($request)
    {
        return array(
            'title' => array(
                'type' => 'text',
                'placeholder' => __('validation.attributes.title'),
                'value' => $request->input('title'),
                'col' => 'col-md-4'
            ),
        );
    }

    /**
     * @param Request               $request
     * @param LegislativeInitiative $item
     *
     * @return View
     */
    public function show(Request $request, LegislativeInitiative $item): View
    {
        $show_deleted_comments = $request->offsetGet('show_deleted_comments');

        $query = LegislativeInitiativeComment::query();

        if ($show_deleted_comments == '1') {
            $query = $query->withTrashed();
        }

        $comments = $query->where('legislative_initiative_id', $item->id)->get();

        return $this->view('admin.legislative_initiatives.show', compact('item', 'comments'));
    }

    public function update(StoreLegislativeInitiativeRequest $request, LegislativeInitiative $item)
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

    public function destroy(LegislativeInitiative $item)
    {
        try {
            $item->delete();

            return redirect(route(self::LIST_ROUTE, $item))
                ->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.deleted_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route(self::LIST_ROUTE, $item))->with('danger', __('messages.system_error'));
        }
    }

    public function restore(LegislativeInitiative $item)
    {
        try {
            $item->restore();

            return redirect(route(self::LIST_ROUTE, $item))
                ->with('success', trans_choice('custom.legislative_initiatives', 1) . " " . __('messages.restored_successfully_f'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect(route(self::LIST_ROUTE, $item))->with('danger', __('messages.system_error'));
        }
    }
}

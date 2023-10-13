<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\StorePollRequest;
use App\Models\Poll;
use App\Models\PollAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PollController extends AdminController
{
    const LIST_ROUTE = 'admin.polls.index';
    const EDIT_ROUTE = 'admin.polls.edit';
    const STORE_ROUTE = 'admin.polls.store';
    const LIST_VIEW = 'admin.polls.index';
    const EDIT_VIEW = 'admin.polls.edit';

    /**
     * Show the public consultations.
     *
     * @return View
     */
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? Poll::PAGINATE;

        $items = Poll::with(['translation'])
            ->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'Poll';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName'));
    }

    /**
     * @param Request $request
     * @param Poll $item
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $item = null)
    {
        $item = $this->getRecord($item, ['answers']);
        if( ($item && $request->user()->cannot('update', $item)) || $request->user()->cannot('create', Poll::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $translatableFields = Poll::translationFieldsProperties();

        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'translatableFields'));
    }

    public function store(StorePollRequest $request, $item = null)
    {
        $item = $this->getRecord($item, ['answers']);
        $validated = $request->validated();
        if( ($item->id && $request->user()->cannot('update', $item))
            || $request->user()->cannot('create', Poll::class) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        try {
            $fillable = $this->getFillableValidated($validated, $item);
            $item->fill($fillable);
            $item->active = $request->input('active') ? 1 : 0;
            if ($item->id) {
                if ($request->input('deleted')) {
                    $item->deleteTranslations();
                    $item->delete();
                }
                else if ($item->deleted_at) {
                    $item->restore();
                }
            }
            $item->save();
            $this->storeTranslateOrNewCurrent(Poll::TRANSLATABLE_FIELDS, $item, $validated);

            if ($request->has('answers')) {
                foreach ($item->answers as $answer) {
                    $answer->deleteTranslations();
                }
                $item->answers()->delete();
                foreach ($request->input('answers') as $answer) {
                    if (!$answer) continue;
                    $answerModel = new PollAnswer(['title' => $answer, 'poll_id' => $item->id]);
                    $answerModel->save();
                }
            }

            if( $item->id ) {
                return redirect(route(self::EDIT_ROUTE, $item) )
                    ->with('success', trans_choice('custom.polls', 1)." ".__('messages.updated_successfully_m'));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.polls', 1)." ".__('messages.created_successfully_m'));
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }

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
     * @param $id
     * @param array $with
     */
    private function getRecord($id, array $with = []): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $qItem = Poll::withTrashed();
        if( sizeof($with) ) {
            $qItem->with($with);
        }
        $item = $qItem->find((int)$id);
        if( !$item ) {
            return new Poll();
        }
        return $item;
    }
}

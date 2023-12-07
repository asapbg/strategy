<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PollStatusEnum;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\QuestionCreateRequest;
use App\Http\Requests\QuestionEditRequest;
use App\Http\Requests\StorePollRequest;
use App\Models\Consultations\PublicConsultation;
use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\PollQuestion;
use App\Models\PollQuestionOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PollController extends AdminController
{
    const LIST_ROUTE = 'admin.polls.index';
    const EDIT_ROUTE = 'admin.polls.edit';
    const PREVIEW_ROUTE = 'admin.polls.preview';
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

        $items = Poll::ByUserPermission()->FilterBy($requestFilter)
            ->paginate($paginate);
        $toggleBooleanModel = 'Poll';
        $editRouteName = self::EDIT_ROUTE;
        $listRouteName = self::LIST_ROUTE;
        $storeRouteName = self::STORE_ROUTE;
        $previewRouteName = self::PREVIEW_ROUTE;

        return $this->view(self::LIST_VIEW, compact('filter', 'items', 'toggleBooleanModel', 'editRouteName', 'listRouteName', 'storeRouteName', 'previewRouteName'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, int $id)
    {
        $pc = $request->filled('pc') ? PublicConsultation::find((int)$request->input('pc')) : null;

        $item = $this->getRecord($id);

        if( ($id && $request->user()->cannot('update', $item))
            || (!$id && $request->user()->cannot('create', Poll::class)) ) {
            return redirect(route(self::LIST_ROUTE))->with('warning', __('messages.unauthorized'));
        }
        $storeRouteName = self::STORE_ROUTE;
        $listRouteName = self::LIST_ROUTE;

        return $this->view(self::EDIT_VIEW, compact('item', 'storeRouteName', 'listRouteName', 'pc'));
    }

    public function store(StorePollRequest $request)
//    public function store(Request $request)
    {
//        $r = new StorePollRequest();
//        $v = Validator::make($request->all(), $r->rules());
//        if($v->fails()) {
//            dd($v->errors());
//        }

        $validated = $request->validated();
        $id = $validated['id'];
        $stay = $validated['stay'] ?? 0;
        $saveToPc = $validated['save_to_pc'] ?? 0;
        $pc = isset($validated['pc']) && $validated['pc'] ? PublicConsultation::find((int)$validated['pc']) : null;
        unset($validated['pc'], $validated['save_to_pc']);

        $item = $id ? $this->getRecord($id) : new Poll();

        if( !$item && $validated['id'] ) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( ($id && $request->user()->cannot('update', $item))
            || (!$id && $request->user()->cannot('create', $item)) ) {
            return redirect(route(self::LIST_ROUTE))->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            unset($validated['id']);
            unset($validated['stay']);
            $item->fill($validated);
            $item->status = (int)($request->input('status'));
            $item->is_once = (int)($request->filled('is_once'));
            $item->only_registered = (int)($request->filled('only_registered'));
            if( !$id ){
                $item->user_id = auth()->user()->id;
            }
            $item->save();

            if($pc) {
                $item->consultations()->syncWithoutDetaching([$pc->id]);
            }

            DB::commit();
            if( $stay ) {
                return redirect(route(self::EDIT_ROUTE, ['id' => $item->id]).(isset($pc) && $pc ? '?pc='.$pc->id : '') )
                    ->with('success', trans_choice('custom.polls', 1)." ".($id ? __('messages.updated_successfully_f') : __('messages.created_successfully_f')));
            } elseif ($saveToPc) {
                return redirect(route('admin.consultations.public_consultations.edit', $pc).'#ct-polls' )
                    ->with('success', trans_choice('custom.polls', 1)." ".($id ? __('messages.updated_successfully_f') : __('messages.created_successfully_f')));
            }

            return to_route(self::LIST_ROUTE)
                ->with('success', trans_choice('custom.polls', 1)." ".($id ? __('messages.updated_successfully_f') : __('messages.created_successfully_f')));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Save poll error: '.$e);
            return redirect()->back()->withInput(request()->all())->with('danger', __('messages.system_error'));
        }
    }

    public function createQuestion(Request $request)
    {
        $storeRequest = new QuestionCreateRequest();
        $validator = Validator::make($request->all(), $storeRequest->rules());
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }
        $validated = $validator->validated();
        $poll = $this->getRecord($validated['poll_id']);
        $pc = isset($validated['pc']) && $validated['pc'] ? PublicConsultation::find((int)$validated['pc']) : null;

        if( $request->user()->cannot('update', $poll) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }
        DB::beginTransaction();
        try {
            $question = $poll->questions()->create([
                'name' => $validated['new_question_name'],
            ]);

            foreach ($validated['new_answers'] as $key => $row) {
                $question->answers()->create([
                    'name' => $row,
                ]);
            }

            DB::commit();
            return redirect(route(self::EDIT_ROUTE, ['id' => $validated['poll_id']]).( $pc ? '?pc='.$pc->id : ''))->with('success', trans_choice('custom.questions', 1).' '.__('messages.created_successfully_m'));
        } catch (\Exception $e) {
            Log::error('Create question :'.$e->getMessage());
            DB::rollBack();
            return back()->with('danger', __('messages.system_error'))->withInput();
        }
    }

    public function editQuestion(QuestionEditRequest $request)
    {
        $validated = $request->validated();
        $question = PollQuestion::with(['answers'])->find($validated['question_id']);
        $pc = isset($validated['pc']) && $validated['pc'] ? PublicConsultation::find((int)$validated['pc']) : null;
        if (!$question) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( $request->user()->cannot('update', $question->poll) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        DB::beginTransaction();
        try {
            $oldAnswers = $question->answers->pluck('id')->toArray();

            $question->name = $validated['question_name'];
            $question->save();

            foreach ($validated['answer_id'] as $key => $a_id) {
                $oldAnswers = array_diff($oldAnswers, [$a_id]);
                PollQuestionOption::where('id', (int)$a_id)->update([
                    'name' => $validated['answer_name'][$key]
                ]);
            }

            if (sizeof($oldAnswers)) {
                PollQuestionOption::whereIn('id', $oldAnswers)->delete();
            }

            DB::commit();
            return redirect(route(self::EDIT_ROUTE, ['id' => $question->poll_id]).( $pc ? '?pc='.$pc->id : ''))->with('success', trans_choice('custom.questions', 1).' '.__('messages.updated_successfully_m'));
        } catch (\Exception $e) {
            Log::error('Edit question :'.$e->getMessage());
            DB::rollBack();
            return back()->with('danger', __('messages.system_error'))->withInput();
        }
    }

    public function questionDelete(Request $request, $id)
    {
        $question = PollQuestion::find((int)$id);
        if (!$question) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( $request->user()->cannot('update', $question->poll) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        $object = __('site.label_question');
        $name = $question->name;
        $delete_url = route('admin.poll.question.delete.confirm');
        $back_url = route('admin.poll.edit', ['id' => $question->poll_id]);

        return view('admin.partial.confirm_delete', compact('id', 'name', 'object', 'delete_url', 'back_url'));
    }

    public function questionConfirmDelete(Request $request)
    {
        $question = PollQuestion::with(['poll', 'poll.questions', 'answers'])->find((int)$request->get('id'));
        if (!$question) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( $request->user()->cannot('update', $question->poll) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        if ($question->poll->questions->count() < 2) {
            return redirect(route('admin.poll.edit', ['id' => $question->poll_id]))
                ->with('danger', __('custom.poll_need_al_least_one_question'));
        }

        $question->answers()->delete();
        $question->delete();

        return redirect(route('admin.poll.edit', ['id' => $question->poll_id]))->with('success', trans_choice('custom.questions', 1).' '.__('messages.deleted_successfully_m'));
    }

    /**
     * Delete existing pris record
     *
     * @param Poll $item
     * @return RedirectResponse
     */
    public function destroy(Request $request, Poll $item)
    {
        if($request->user()->cannot('delete', $item)) {
            abort(Response::HTTP_FORBIDDEN);
        }
        try {
            $item->delete();
            return redirect(url()->previous())
                ->with('success', trans_choice('custom.polls', 1)." ".__('messages.deleted_successfully_f'));
        }
        catch (\Exception $e) {
            Log::error($e);
            return redirect(url()->previous())->with('danger', __('messages.system_error'));

        }
    }

    /**
     * Show statistic
     *
     * @param Poll $item
     */
    public function preview(Request $request, Poll $item)
    {
        if($request->user()->cannot('preview', $item)) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $statistic = $item->getStats();
        return $this->view('admin.polls.statistic', compact('item', 'statistic'));
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
            'content' => array(
                'type' => 'text',
                'default' => '',
                'placeholder' => __('custom.content'),
                'value' => $request->input('content'),
                'col' => 'col-md-4'
            ),
            'active' => array(
                'type' => 'select',
                'options' => PollStatusEnum::statusOptions(__('custom.status').' ('.__('custom.any').')'),
                'default' => '',
                'placeholder' => __('custom.status'),
                'value' => $request->input('active'),
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

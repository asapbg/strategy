<?php

namespace App\Http\Controllers;

use App\Enums\PollStatusEnum;
use App\Exports\PollExport;
use App\Http\Requests\StoreUserPollRequest;
use App\Models\Poll;
use App\Models\UserPoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class PollController extends Controller
{
    public function index(Request $request)
    {
        //Filter
        $rf = $request->all();
        $requestFilter = $request->all();
        $filter = $this->filters($request);

        //Sorter
        $sorter = $this->sorters();
        $sort = $request->filled('order_by') ? $request->input('order_by') : 'id';
        $sortOrd = $request->filled('direction') ? $request->input('direction') : (!$request->filled('order_by') ? 'desc' : 'asc');

        $paginate = $requestFilter['paginate'] ?? Poll::PAGINATE;

        $contentSearch = $requestFilter['content'] ?? null;
        $items = Poll::select('poll.id', 'poll.name', 'poll.status', 'poll.start_date', 'poll.end_date', 'poll.has_entry')
            ->Active()
            ->Public()
            ->FilterBy($requestFilter)
            ->leftJoin('public_consultation_poll', 'public_consultation_poll.poll_id', '=', 'poll.id')
            ->leftjoin('poll_question', 'poll_question.poll_id', '=', 'poll.id')
            ->leftjoin('poll_question_option', 'poll_question_option.poll_question_id', '=', 'poll_question.id')
            ->when($contentSearch, function ($query) use($contentSearch){
                return $query->where(function ($query) use ($contentSearch){
                    $query->where('poll_question.name', 'ilike', '%'.$contentSearch.'%')
                        ->orWhere('poll_question_option.name', 'ilike', '%'.$contentSearch.'%')
                        ->orWhere('poll.name', 'ilike', '%'.$contentSearch.'%');
                });
            })
            ->whereNotNull('poll_question.id')
            ->whereNull('public_consultation_poll.poll_id')
            ->groupBy('poll.id')
            ->SortedBy($sort,$sortOrd)
            ->paginate($paginate);

        if( $request->ajax() ) {
            return $this->view('site.polls.list', compact('filter','sorter', 'items', 'rf'));
        }

        $pageTitle = __('site.public_consultation.polls');
        return $this->view('site.polls.index', compact('filter','sorter', 'items', 'rf', 'pageTitle'));
    }

    public function show(Request $request, $id)
    {
        $ip = $request->getClientIp();
        $user = $request->user();
        $item = Poll::find((int)$id);

        if( !$item || $item->consultations->count()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( ($item->only_registered && !$user) ) {
            return redirect(route('login'))->with('warning', __('messages.poll_only_registered'));
        }

        if(
            $item->is_once
            && (
                ($user && $user->polls()->where('poll_id', '=', $item->id)->count())
                || (!$user && UserPoll::where('ip', '=', $ip)->where('poll_id', '=', $item->id)->count())
            )) {
            return redirect(route('poll.index'))->with('warning', __('messages.poll_one_time'));
        }

        if( !$item->inPeriod ) {
            return back()->with('warning', __('messages.poll_expired'));
        }

        return $this->view('site.polls.show', compact('item'));
    }

    public function statistic(Request $request, $id)
    {
        $item = Poll::with('questions', 'questions.answers')->find((int)$id);
        if( !$item || $item->consultations->count()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if( $item->inPeriod ) {
            return back()->with('warning', __('messages.poll_not_expired'));
        }

        $statistic = $item->getStats();
        return $this->view('site.polls.statistic', compact('item', 'statistic'));
    }

    public function store(Request $request)
    {
        $r = new StoreUserPollRequest();
        $validator = Validator::make($request->all(), $r->rules(), $r->messages());
        if( $validator->fails() ) {
            return back()->withInput()->withErrors($validator->errors())->with('danger', __('messages.check_for_errors'));
        }
        $validated = $validator->validated();
        $poll = Poll::Active()->find((int)$validated['id']);

        if( !$poll->inPeriod ) {
            return back()->with('warning', __('messages.poll_expired'));
        }

        $ip = $request->getClientIp();
        $user = $request->user();

        if( $poll->only_registered && !$user ) {
            return back()->with('warning', __('messages.poll_only_registered'));
        }

        if( $poll->is_once ) {
            if($user) {
                $pollExist = $user->polls()->where('poll_id', '=', (int)$validated['id'])->first();
            } else {
                $pollExist = UserPoll::where('ip', '=', $ip)->where('poll_id', '=', (int)$validated['id'])->first();
            }
            if( $pollExist ) {
                return back()->with('warning', __('messages.poll_one_time'));
            }
        }



        DB::beginTransaction();
        try {
            $userPoll = UserPoll::create([
                'poll_id' => (int)$validated['id'],
                'user_id' => $user ? $user->id : null,
                'ip' => $ip ?? null,
            ]);

            $answers = [];
            foreach ($validated['q'] as $qId) {
                foreach ($validated as $key => $value) {
                    if( $key == 'a_'.$qId ) {
                        if(is_array($value)){
                            foreach ($value as $v) {
                                $answers[] = $v;
                            }
                        } else {
                            $answers[] = $value;
                        }

                    }
                }
            }
            $userPoll->answers()->attach($answers);
            $poll->has_entry = 1;
            $poll->save();
            DB::commit();
            if( isset($validated['source']) ) {
                if($validated['source'] == 'regular') {
                    return redirect(route('poll.index'))->with('success', __('messages.poll_save_success'));
                } elseif ($validated['source'] == 'pc') {
                    return redirect(route('public_consultation.view', ['id' => $validated['pc_id']]))->with('success', __('messages.poll_save_success'));
                }
            }
            return redirect(url()->previous())->with('success', __('messages.poll_save_success'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return back()->with('warning', __('messages.system_error'));
        }
    }

    public function export(Request $request, $id, $format = 'pdf')
    {
        $poll = Poll::Expired()->find($id);
        if(!$poll){
            return back()->with('warning', __('messages.record_not_found'));
        }

        try {
            if($format == 'excel'){
                return Excel::download(new PollExport($poll), date('d_m_Y_H_i_s').'_poll.xlsx');

            } else{
                $isPdf = true;
                $statistic = $poll->getStats();
                $pdf = PDF::loadView('exports.poll', compact('poll', 'statistic', 'isPdf'));
                return $pdf->download(date('d_m_Y_H_i_s').'_poll.pdf');
            }
        }
        catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('warning', "Възникна грешка при експортирането, моля опитайте отново");
        }
    }

    private function sorters()
    {
        return array(
            'keyWord' => ['class' => 'col-md-4', 'label' => trans_choice('custom.keyword', 1)],
            'fieldOfAction' => ['class' => 'col-md-4', 'label' => trans_choice('custom.field_of_actions', 1)],
            'date' => ['class' => 'col-md-2', 'label' => __('custom.date')],
        );
    }

    private function filters($request)
    {
        return array(
            'content' => array(
                'type' => 'text',
                'default' => '',
                'label' => __('custom.content'),
                'value' => $request->input('content'),
                'col' => 'col-md-4'
            ),
            'active' => array(
                'type' => 'select',
                'options' => PollStatusEnum::statusOptions(),
                'default' => '',
                'label' => __('custom.status'),
                'value' => $request->input('active'),
                'col' => 'col-md-4'
            ),
            'paginate' => array(
                'type' => 'select',
                'options' => paginationSelect(),
                'multiple' => false,
                'default' => '',
                'label' => __('custom.filter_pagination'),
                'value' => $request->input('paginate') ?? Poll::PAGINATE,
                'col' => 'col-md-3'
            ),

        );
    }

}

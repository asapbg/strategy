<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPollRequest;
use App\Models\FieldOfAction;
use App\Models\Poll;
use App\Models\UserPoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

        $items = Poll::Public()->orderBy('id', 'desc')
            ->FilterBy($requestFilter)
            ->SortedBy($sort,$sortOrd)
            ->paginate($paginate);

        if( $request->ajax() ) {
            return view('site.polls.list', compact('filter','sorter', 'items', 'rf'));
        }

        $pageTitle = __('site.public_consultation.polls');
        return $this->view('site.polls.index', compact('filter','sorter', 'items', 'rf', 'pageTitle'));
    }

    public function show(Request $request, $id)
    {
        return $this->view('site.polls.show');
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

        if( $poll->only_registered && !$user ) {
            return back()->with('warning', __('messages.poll_one_time'));
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
            DB::commit();
            return redirect(url()->previous())->with('success', __('messages.poll_save_success'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return back()->with('warning', __('messages.system_error'));
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
//            'keyWords' => array(
//                'type' => 'text',
//                'default' => '',
//                'label' => trans_choice('custom.keyword', 2),
//                'value' => $request->input('keyWords'),
//                'col' => 'col-md-4'
//            ),
//            'fieldOfAction' => array(
//                'type' => 'select',
//                'options' => optionsFromModel(FieldOfAction::get()),
//                'multiple' => false,
//                'default' => '',
//                'label' => trans_choice('custom.field_of_actions', 1),
//                'value' => $request->input('fieldOfAction'),
//                'col' => 'col-md-4'
//            ),
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

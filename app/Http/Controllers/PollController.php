<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPollRequest;
use App\Models\Poll;
use App\Models\UserPoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PollController extends Controller
{
    public function index()
    {
        return $this->view('site.polls.index');
    }

    public function show()
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
            DB::commit();
            return redirect(url()->previous())->with('success', __('messages.poll_save_success'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return back()->with('warning', __('messages.system_error'));
        }
    }

}

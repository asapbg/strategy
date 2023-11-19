<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPollRequest;
use App\Models\Poll;
use Illuminate\Http\Request;
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
//            dd($validator->errors());
            return back()->withInput()->withErrors($validator->errors())->with('danger', __('messages.check_for_errors'));
        }
        $validated = $validator->validated();
        return back()->with('warning', 'Функционалността е в процес на разработка');
        $poll = Poll::find((int)$validated['id']);

        if( $request->user()->cannot('send', $poll) ) {
            return back()->with('warning', __('messages.unauthorized'));
        }

        return $this->view('site.polls.show');
    }

}

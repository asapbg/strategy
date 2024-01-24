<?php

namespace App\Http\Controllers\Admin\AdvisoryBoard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvBoardStoreMessage;
use App\Models\CustomRole;
use App\Models\User;
use App\Notifications\AdvBoardsEmailMsgToModerator;
use App\Notifications\AdvBoardsMsgToModerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdvisoryBoardMessagesController extends Controller
{
    public function index(Request $request){
        $items = DB::table('notifications')
            ->where('type', '=', 'App\Notifications\AdvBoardsMsgToModerator')
            ->orderBy('read_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $this->view('admin.advisory-boards.messages.list', compact('items'));
    }

    public function show(Request $request, $item){
        $notification = DB::table('notifications')
            ->where('id', '=', $item)
            ->first();

        if(!$notification) {
            return back()->with('warning', __('custom.record_not_found'));
        }

        return $this->view('admin.advisory-boards.messages.view', compact('notification'));
    }

    public function send(Request $request){

        if($request->isMethod('post')){
            $rq = new AdvBoardStoreMessage();
            $validator = Validator::make($request->all(), $rq->rules());
            if($validator->fails()){
                return back()->withInput()->withErrors($validator->errors());
            }

            if( !$request->user()->canAny(['manage.*', 'manage.advisory-boards']) ) {
                return back()->with('danger', 'Нямате достъп до тази функционалност. Моля свържете се с администратор.');
            }

            $validated = $validator->validated();
            foreach ($validated['recipient'] as $userId){
                $user = User::find($userId);
                if($user){
                    $user->notify(new AdvBoardsMsgToModerator($validated));
                    $lastMsg = $user->notifications()->latest()->limit(1)->get();

                    if($lastMsg->count() == 1){
                        $user->notify(new AdvBoardsEmailMsgToModerator($lastMsg[0]));
                    }
                }
            }

            return redirect(route('admin.advisory-boards.messages'))->with('success', 'Съобщението е изпратено успешно');
        }


        $moderators = User::whereHas('roles', function($q){
            $q->where("name", CustomRole::MODERATOR_ADVISORY_BOARD);
        })->get();
        return $this->view('admin.advisory-boards.messages.edit', compact('moderators'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvisoryBoard;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $user = $request->user();
        $requestFilter = $request->all();
        $filter = $this->filters($request);

        $q = $user->notifications();
        if(isset($requestFilter['status']) && (int)$requestFilter['status'] > 0){
            if((int)$requestFilter['status'] == 1){
                $q->whereNull('read_at');
            } else{
                $q->whereNotNull('read_at');
            }
        }
        if(isset($requestFilter['fromDate']) && !empty($requestFilter['fromDate'])){
            $q->where('created_at', '>=', Carbon::parse($requestFilter['fromDate'])->format('Y-m-d').' 00:00:00');
        }
        if(isset($requestFilter['toDate']) && !empty($requestFilter['toDate'])){
            $q->where('created_at', '<=', Carbon::parse($requestFilter['toDate'])->format('Y-m-d').' 23:59:59');
        }

        $notifications = $q->paginate(User::PAGINATE);

        return $this->view('admin.notifications.index', compact('user', 'notifications', 'filter'));
    }

    public function show(Request $request, $id): \Illuminate\View\View
    {
        $user = $request->user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        $item = $itemUrl = null;
        if(isset($notification->data['model']) && class_exists($notification->data['model'])) {
            $model = new $notification->data['model'];
            if($model instanceof AdvisoryBoard) {
                $item = $notification->data['model']::find($notification->data['id']);
                $itemUrl = ['route' => route('admin.advisory-boards.edit', $item), 'name' => $item->name];
            }

        }

        return $this->view('admin.notifications.show', compact('notification', 'item', 'itemUrl'));
    }

    public function markAllAsRead(Request $request){
        $user = auth()->user();
        if($user){
            $user->unreadNotifications->markAsRead();
        }
        return redirect(route('admin.user.notifications'));
    }

    private function filters($request)
    {
        return array(
            'fromDate' => array(
                'type' => 'datepicker',
                'value' => $request->input('fromDate'),
                'placeholder' => __('custom.begin_date'),
                'col' => 'col-md-2'
            ),
            'toDate' => array(
                'type' => 'datepicker',
                'value' => $request->input('toDate'),
                'placeholder' => __('custom.end_date'),
                'col' => 'col-md-2'
            ),
            'status' => array(
                'type' => 'select',
                'options' => array(
                    ['name' => __('custom.status').' (всички)', 'value' => 0],
                    ['name' => trans_choice('custom.unreads', 2), 'value' => 1],
                    ['name' => trans_choice('custom.reads', 2), 'value' => 2],
                ),
                'default' => '',
                'placeholder' => __('custom.status'),
                'value' => $request->input('status'),
                'col' => 'col-md-4'
            ),
        );
    }
}

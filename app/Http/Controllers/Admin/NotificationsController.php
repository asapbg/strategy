<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvisoryBoard;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $user = $request->user();
        $notifications = $user->notifications()->paginate(User::PAGINATE);

        return $this->view('admin.notifications.index', compact('user', 'notifications'));
    }
    public function show(Request $request, $id): \Illuminate\View\View
    {
        $user = $request->user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        $item = $itemUrl = null;
        if(class_exists($notification->data['model'])) {
            $model = new $notification->data['model'];
            if($model instanceof AdvisoryBoard) {
                $item = $notification->data['model']::find($notification->data['id']);
                $itemUrl = ['route' => route('admin.advisory-boards.edit', $item), 'name' => $item->name];
            }

        }

        return $this->view('admin.notifications.show', compact('notification', 'item', 'itemUrl'));
    }

}

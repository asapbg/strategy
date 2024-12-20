<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationsController extends Controller
{

    public function show(Request $request, $id): View
    {
        $user = $request->user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        $item = isset($notification->data['model']) && class_exists($notification->data['model'])
            ? ( $notification->data['model'] == "App\Models\ApplicationForm" ? $notification->data['model']::NoParentScope()->find($notification->data['id']) : $notification->data['model']::find($notification->data['id'])  )
            : null;

        return $this->view('site.notifications.show', compact('notification', 'item'));
    }
}

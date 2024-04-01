<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultations\PublicConsultation;
use App\Models\UserChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserChangeRequestController extends Controller
{
    public function index(Request $request)
    {
        $requestFilter = $request->all();
        $filter = $this->filters($request);
        $paginate = $filter['paginate'] ?? PublicConsultation::PAGINATE;
        $items = UserChangeRequest::select('user_change_request.*')
            ->join('users as user', 'user.id', '=', 'user_change_request.user_id')
            ->leftJoin('users as status_user', 'status_user.id', '=', 'user_change_request.status_user_id')
            ->FilterBy($requestFilter)
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
        return $this->view('admin.users_change_request.index', compact('items', 'filter'));
    }

    public function approve(Request $request)
    {
        $itemId = $request->input('change_id', 0);
        $item = UserChangeRequest::find($itemId);
        if(!$item){
            return back()->with('danger', __('messages.record_not_found'));
        }

        if($request->user()->cannot('approve', $item)){
            return back()->with('danger', __('messages.unauthorized'));
        }

        if(!$item->user){
            return back()->with('danger', 'Потребителят не е открит');
        }

        try {

            $data = json_decode($item->data, null);
            $user = $item->user;
            foreach ($data as $k => $v){
                $user->{$k} = $v;
            }
            $user->save();

            $item->status = UserChangeRequest::APPROVED;
            $item->status_user_id = $request->user()->id;
            $item->save();
            return redirect(route('admin.users.change_request'))->with('success', __('messages.record_updated_successfully_m'));
        } catch (\Exception $e){
            Log::error('Withdrew user change request: '.$e);
            return back()->with('danger', __('messages.system_error'));
        }
    }

    public function reject(Request $request)
    {
        $itemId = $request->input('change_id', 0);
        $item = UserChangeRequest::find($itemId);
        if(!$item){
            return back()->with('danger', __('messages.record_not_found'));
        }

        if($request->user()->cannot('reject', $item)){
            return back()->with('danger', __('messages.unauthorized'));
        }

        try {
            $item->status = UserChangeRequest::REJECTED;
            $item->status_user_id = $request->user()->id;
            $item->save();
            return redirect(route('admin.users.change_request'))->with('success', __('messages.record_updated_successfully_m'));
        } catch (\Exception $e){
            Log::error('Withdrew user change request: '.$e);
            return back()->with('danger', __('messages.system_error'));
        }
    }

    private function filters($request)
    {
        return array(
            'userName' => array(
                'type' => 'text',
                'placeholder' => __('validation.attributes.name'),
                'value' => $request->input('userName'),
                'col' => 'col-md-4'
            ),
            'statusUserName' => array(
                'type' => 'text',
                'placeholder' => __('custom.status_user'),
                'value' => $request->input('statusUserName'),
                'col' => 'col-md-4'
            ),
            'status' => array(
                'type' => 'select',
                'options' => array(
                    ['name' => 'Всички', 'value' => 0],
                    ['name' => __('custom.user_change_request_status.1'), 'value' => 1],
                    ['name' => __('custom.user_change_request_status.2'), 'value' => 2],
                    ['name' => __('custom.user_change_request_status.3'), 'value' => 3],
                    ['name' => __('custom.user_change_request_status.4'), 'value' => 4],
                ),
                'default' => '',
                'placeholder' => __('custom.status'),
                'value' => $request->input('status'),
                'col' => 'col-md-4'
            ),
        );
    }
}

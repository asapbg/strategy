<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeUserDataRequest;
use App\Mail\UsersChangePassword;
use App\Models\Consultations\PublicConsultation;
use App\Models\FormInput;
use App\Models\LegislativeInitiative;
use App\Models\UserChangeRequest;
use App\Models\UserSubscribe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index($tab = null)
    {
        $pageTitle = trans_choice('custom.profiles', 1);
        $secondTitle = __('custom.main_information');
        $profile = auth()->user();
        $tab = $tab ? $tab : 'change_info';
        $data = null;
        $breadcrumbs = [
            ['name' => $pageTitle, 'url' => route('profile')]
        ];

        switch ($tab){
            case 'subscriptions':
                $data = \DB::select('
                    select
                        user_subscribes.subscribable_type,
                        json_agg(json_build_object(\'id\', user_subscribes.id, \'subscribable_id\', user_subscribes.subscribable_id, \'search_filters\', user_subscribes.search_filters, \'is_subscribed\', user_subscribes.is_subscribed)) as subscriptions
                    from user_subscribes
                    where
                        user_subscribes.user_id = '.auth()->user()->id.'
                        and user_subscribes.deleted_at is null
                        and user_subscribes.channel = '.UserSubscribe::CHANNEL_EMAIL.'
                    group by user_subscribes.subscribable_type

                ');
                $breadcrumbs[] = ['name' => __('custom.subscriptions'), 'url' => ''];
                $secondTitle = __('custom.subscriptions');
                break;
            case 'form_inputs':
                $data = FormInput::whereUserId($profile->id)->get();
                $breadcrumbs[] = ['name' => __('custom.form_inputs'), 'url' => ''];
                $secondTitle = __('custom.form_inputs');
                break;
            case 'pc':
                $pcIds = $profile->commentsPc->pluck('object_id')->unique()->toArray();
                $data = PublicConsultation::with(['comments' => function ($q) use($profile){
                    $q->where('user_id', '=', $profile->id);
                }])->whereIn('id', $pcIds)->get();
                $breadcrumbs[] = ['name' => trans_choice('custom.public_consultations', 2), 'url' => ''];
                $secondTitle = trans_choice('custom.public_consultations', 2);
                break;
            case 'li':
                //Author
                $ids = $profile->legislativeInitiatives->pluck('id')->toArray();
                $commentsLiIds = $profile->legislativeInitiativesComments->pluck('legislative_initiative_id')->toArray();
                if(sizeof($commentsLiIds)){
                    $ids = array_merge($ids, $commentsLiIds);
                }
                $votedLiIds = $profile->legislativeInitiativesLike->pluck('legislative_initiative_id')->toArray();
                if(sizeof($votedLiIds)){
                    $ids = array_merge($ids, $votedLiIds);
                }
                $data = LegislativeInitiative::whereIn('id', array_keys($ids))->get();
                $breadcrumbs[] = ['name' => trans_choice('custom.legislative_initiatives', 2), 'url' => ''];
                $secondTitle = trans_choice('custom.legislative_initiatives', 2);
                break;
            default:
                $breadcrumbs[] = ['name' => __('custom.change_info'), 'url' => ''];
        }

        $this->setBreadcrumbsFull($breadcrumbs);
        return $this->view('site.profile', compact('profile', 'tab', 'data', 'pageTitle', 'secondTitle'));
    }

    public function store(ChangeUserDataRequest $request) {
        $validated = $request->validated();
//        return back()->with('danger', 'Функционалността е в процес на разработка');
//        dd($validated);

        try {
            if(!isset($validated['edit']) || !$validated['edit']){
                $changes = [];
                foreach (['first_name', 'middle_name', 'last_name', 'email', 'org_name'] as $k){
                    if(
                        (isset($validated[$k]) && $validated[$k] != $request->user()->{$k})
                        || (!isset($validated[$k]) && !empty($request->user()->{$k}))
                    ){
                        $changes[$k] = $validated[$k];
                    }
                }
                if(!sizeof($changes)){
                    return back()->withInput()->with('danger', __('site.change_request_not_send'));
                }

                $request->user()->changeRequests()->create([
                    'data' => json_encode($changes, JSON_UNESCAPED_UNICODE)
                ]);
                return redirect(route('profile'))->with('success', __('site.change_request_success'));
            } else{
                $user = auth()->user();
                foreach (['notification_email'] as $k){
                    $user->{$k} = $validated[$k];
                }
                $user->save();
                return redirect(route('profile'))->with('success', __('messages.data_updated_successfully'));
            }

        } catch (\Exception $e){
            Log::error('Change profile request for user ('.$request->user()->id.'): '.$e);
            return back()->with('danger', __('messages.system_error'));
        }
    }

    public function subscriptionState(Request $request, int $id, int $status) {
        $user = auth()->user();
        $subscription = UserSubscribe::find($id);
        if(!$subscription){
            return back()->with('warning', __('messages.record_not_found'));
        }

        if($subscription->user_id != $user->id){
            return back()->with('warning', __('messages.unauthorized'));
        }

        $subscription->is_subscribed = (bool)$status;
        $subscription->save();

        return redirect(route('profile', ['tab' => 'subscriptions']))->with('success', $status ? __('messages.success_subscribe') : __('messages.success_unsubscribe') );
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = auth()->user();
            $user->password = bcrypt($validated['password']);
            $user->password_changed_at = Carbon::now();
            $user->save();
            return redirect()->back()->with('success', __('site.password_change_success'));

        } catch (\Exception $e) {

            Log::error($e);

            return redirect()->back()->with('danger', __('messages.system_error'));
        }
    }

    public function withdrew(Request $request)
    {
        $itemId = $request->input('change_id', 0);
        $item = UserChangeRequest::where('user_id', '=', $request->user()->id)->find($itemId);
        if(!$item){
            return back()->with('danger', __('messages.record_not_found'));
        }

        try {
            $item->status = UserChangeRequest::CANCELED;
            $item->save();
            return redirect(route('profile'))->with('success', __('site.withdrew_change_request_success'));
        } catch (\Exception $e){
            Log::error('Withdrew user change request: '.$e);
            return redirect(route('profile'))->with('danger', __('messages.system_error'));
        }
    }
}

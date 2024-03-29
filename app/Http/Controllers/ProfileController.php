<?php

namespace App\Http\Controllers;

use App\Models\Consultations\PublicConsultation;
use App\Models\FormInput;
use App\Models\LegislativeInitiative;
use App\Models\UserSubscribe;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index($tab = null)
    {
        $pageTitle = trans_choice('custom.profiles', 1);
        $secondTitle = trans_choice('custom.profiles', 1);
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
        }

        $this->setBreadcrumbsFull($breadcrumbs);
        return $this->view('site.profile', compact('profile', 'tab', 'data', 'pageTitle', 'secondTitle'));
    }

    public function store(Request $request) {
        $user = app('auth')->user();
        $data = $request->all();
        if ($user->is_org) {
            $user->org_name = $request->input('org_name');
        } else {
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
        }
        $user->save();
        return redirect()->back();
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
}

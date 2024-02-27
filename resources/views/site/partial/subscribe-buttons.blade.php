@auth()
    @if(App\Models\User::isSubscribed(App\Models\UserSubscribe::CHANNEL_RSS))
        <button class="btn rss-sub main-color unsubscribe" @if(isset($subscribe_params)) data-filter="{{ json_encode($subscribe_params) }}" @endif data-channel="{{ App\Models\UserSubscribe::CHANNEL_RSS }}">
            <i class="fas fa-square-rss text-warning"></i>{{ __('custom.rss_subscribe') }} <span>{{ __('custom.unsubscribe') }}</span>
        </button>
    @else
        <button class="btn rss-sub main-color subscribe" @if(isset($subscribe_params)) data-filter="{{ json_encode($subscribe_params) }}" @endif data-channel="{{ App\Models\UserSubscribe::CHANNEL_RSS }}">
            <i class="fas fa-square-rss text-warning"></i>{{ __('custom.rss_subscribe') }}
        </button>
    @endif
    @if(App\Models\User::isSubscribed(App\Models\UserSubscribe::CHANNEL_EMAIL))
        <button class="btn email-sub main-color unsubscribe" @if(isset($subscribe_params)) data-filter="{{ json_encode($subscribe_params) }}" @endif data-channel="{{ App\Models\UserSubscribe::CHANNEL_EMAIL }}">
            <i class="fas fa-envelope"></i><span>{{ __('custom.unsubscribe') }}</span>
        </button>
    @else
        <button class="btn email-sub main-color subscribe" @if(isset($subscribe_params)) data-filter="{{ json_encode($subscribe_params) }}" @endif data-channel="{{ App\Models\UserSubscribe::CHANNEL_EMAIL }}">
            <i class="fas fa-envelope"></i><span>{{ __('custom.subscribe') }}</span>
        </button>
    @endif
    <input type="hidden" id="subscribe_text" value="{{ __('custom.subscribe') }}">
    <input type="hidden" id="unsubscribe_text" value="{{ __('custom.unsubscribe') }}">
@endauth


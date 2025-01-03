
{{--    @if(App\Models\User::isSubscribed(App\Models\UserSubscribe::CHANNEL_RSS))--}}
{{--    @if(isset($hasSubscribeRss) && $hasSubscribeRss)--}}
{{--        <button class="btn rss-sub main-color unsubscribe" @if(isset($subscribe_params)) data-filter="{{ json_encode($subscribe_params) }}" @endif data-channel="{{ App\Models\UserSubscribe::CHANNEL_RSS }}">--}}
{{--            <i class="fas fa-square-rss text-warning"></i>{{ __('custom.rss_subscribe') }} <span>{{ __('custom.unsubscribe') }}</span>--}}
{{--        </button>--}}
{{--    @else--}}
        @if(((isset($no_rss) && !$no_rss) || !isset($no_rss)) && isset($rssUrl))
            <a href="{{ $rssUrl.(isset($subscribe_params) && sizeof($subscribe_params) ? '?'.urldecode(http_build_query($subscribe_params)) : '') }}" id="rss-link"
               class="btn rss-sub main-color text-decoration-none"
               target="_blank" title="{{ __('custom.subscribe') }}">
                <i class="fas fa-square-rss text-warning"></i>{{ __('custom.rss_subscribe') }}
            </a>
        @endif
{{--        <button class="btn rss-sub main-color subscribe" @if(isset($subscribe_params)) data-filter="{{ json_encode($subscribe_params) }}" @endif data-channel="{{ App\Models\UserSubscribe::CHANNEL_RSS }}">--}}
{{--            <i class="fas fa-square-rss text-warning"></i>{{ __('custom.rss_subscribe') }}--}}
{{--        </button>--}}
{{--    @endif--}}
@auth()
{{--    @if(App\Models\User::isSubscribed(App\Models\UserSubscribe::CHANNEL_EMAIL))--}}
    @if(!isset($no_email_subscribe) || !$no_email_subscribe)
        @if(isset($hasSubscribeEmail) && $hasSubscribeEmail)
            <button class="btn email-sub main-color unsubscribe" @if(isset($subscribe_params)) data-filter="{{ json_encode($subscribe_params) }}" @endif data-channel="{{ App\Models\UserSubscribe::CHANNEL_EMAIL }}">
                <i class="fas fa-envelope"></i><span>{{ __('custom.unsubscribe') }}</span>
            </button>
        @else
            <button data-subscribetitle="{{ __('custom.subscribe_title') }}" data-url="{{ route('subscribe.form') }}"
                class="btn email-sub main-color subscribe" @if(isset($subscribe_params)) data-filter="{{ json_encode($subscribe_params) }}" @endif data-channel="{{ App\Models\UserSubscribe::CHANNEL_EMAIL }}">
                <i class="fas fa-envelope"></i><span>{{ isset($subscribe_list) && $subscribe_list ? __('custom.subscribe_list') : __('custom.subscribe') }}</span>
            </button>
        @endif
    @endif
    <input type="hidden" id="subscribe_text" value="{{ __('custom.subscribe') }}">
    <input type="hidden" id="unsubscribe_text" value="{{ __('custom.unsubscribe') }}">
@endauth
@if(!isset($noShareBtn) || !$noShareBtn)
    @if(isset($requestFilter) && isset($requestFilter['search']))
        @php
            unset($requestFilter['search'])
        @endphp
    @endif
    @php($url = request()->url().(isset($requestFilter) && sizeof($requestFilter) ? '?'.urldecode(http_build_query($requestFilter)) : ''))
    <button type="button" class="btn btn-success share-link" name="copy_link" data-link="{{ $url }}">
        <i class="fas fa-share-alt text-success me-2"></i>{{ __('custom.share') }}
    </button>
@endif


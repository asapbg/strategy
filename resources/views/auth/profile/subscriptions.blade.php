<div class="row">
    @if(sizeof($data))
        @foreach ($data as $s)
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            @php($model = explode("\\", $s->subscribable_type))
                            @php($label = 'custom.'.$model[sizeof($model) - 1])
                            <th colspan="3">{{ trans()->has($label) ? __($label) : '---' }}</th>
                        </tr>
                        <tr>
                            <th>{{ __('custom.view') }}</th>
                            <th>{{ __('custom.status') }}</th>
                            <th>{{ __('custom.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($records = json_decode($s->subscriptions))
                        @if($records && sizeof($records) && is_array($records))
                            @foreach($records as $r)
                                <tr>
                                    <td><a href="{{ $r->subscribable_id ? route('strategy-document.view', $r->subscribable_id) : route('strategy-documents.index').addUrlParams($r->search_filters) }}" target="_blank">{{ $r->subscribable_id ? 'Към Стартегическия документ' : 'Към списъка' }}</a></td>
                                    <td>{{ $r->is_subscribed ? __('custom.active_m') : __('custom.inactive_m') }}</td>
                                    <td>
                                        <a href="{{ route('profile.subscribe.set', ['id' => $r->id, 'status' => (int)!$r->is_subscribed]) }}">{{ $r->is_subscribed ? 'Деактивирай' : 'Активирай' }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <div class="col-12">
            {{ __('site.do_not_have_subscriptions') }}
        </div>
    @endif
</div>

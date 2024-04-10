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
                            <th style="width: 60%;">{{ __('custom.view') }}</th>
                            <th style="width: 20%;">{{ __('custom.status') }}</th>
                            <th style="width: 20%;">{{ __('custom.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($records = json_decode($s->subscriptions))
                        @if($records && sizeof($records) && is_array($records))
                            @foreach($records as $r)
                                @php($url = '')
                                @if($s->subscribable_type == 'App\Models\StrategicDocument')
                                    @php($url = $r->subscribable_id ? route('strategy-document.view', $r->subscribable_id) : route('strategy-documents.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                                    @php($objUrlTitle = $r->subscribable_id ? 'Към Стартегическия документ' : 'Към списъка')
                                @elseif($s->subscribable_type == 'App\Models\LegislativeInitiative')
                                    @php($url = $r->subscribable_id ? route('legislative_initiatives.view', $r->subscribable_id) : route('legislative_initiatives.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                                    @php($objUrlTitle = $r->subscribable_id ? 'Към Законодателната инициатива' : 'Към списъка')
                                @elseif($s->subscribable_type == 'App\Models\Consultations\PublicConsultation')
                                    @php($url = $r->subscribable_id ? route('public_consultation.view', $r->subscribable_id) : route('public_consultation.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                                    @php($objUrlTitle = $r->subscribable_id ? 'Към Обществената консултация' : 'Към списъка')
                                @elseif($s->subscribable_type == 'App\Models\AdvisoryBoard')
                                    @php($url = $r->subscribable_id ? route('advisory-boards.view', $r->subscribable_id) : route('advisory-boards.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                                    @php($objUrlTitle = $r->subscribable_id ? 'Към Консултативния съвет' : 'Към списъка')
                                @elseif($s->subscribable_type == 'App\Models\Publication')
                                    @php($url = $r->subscribable_id ? route('library.details', $r->subscribable->type, $r->subscribable_id) : (!empty($r->route_name) ? route($r->route_name).(!empty($r->search_filters) ? addUrlParams($r->search_filters) : '') : ''))
                                    @php($objUrlTitle = $r->subscribable_id ? $r->subscribable->type == \App\Enums\PublicationTypesEnum::TYPE_LIBRARY->value ? 'Към публикацията' : 'Към новината' : 'Към списъка')
                                @endif
                                <tr>
                                    <td><a href="{{ $url }}" target="_blank">{{ $objUrlTitle }}</a></td>
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

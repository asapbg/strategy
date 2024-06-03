<div class="row">
    @if(sizeof($data))
        @foreach ($data as $s)
            <div class="col-12">
                @php($records = json_decode($s->subscriptions))
                @if($records && sizeof($records) && is_array($records))
                    @php($startHtml = true)
                    @php($endHtml = false)
                    @foreach($records as $r)
                        @php($url = '')
                        @if($s->subscribable_type == 'App\Models\StrategicDocument')
                            @php($url = $r->subscribable_id ? route('strategy-document.view', $r->subscribable_id) : route('strategy-documents.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                            @php($item = \App\Models\StrategicDocument::find($r->subscribable_id))
                            @php($objUrlTitle = $r->subscribable_id ? ($item ? $item->title : 'Към Стартегическия документ') : (!empty($r->title) ? $r->title : __('site.to_list')))
                        @elseif($s->subscribable_type == 'App\Models\LegislativeInitiative')
                            @php($url = $r->subscribable_id ? route('legislative_initiatives.view', $r->subscribable_id) : route('legislative_initiatives.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                            @php($item = \App\Models\LegislativeInitiative::find($r->subscribable_id))
                            @php($objUrlTitle = $r->subscribable_id ? ($item ? __('custom.change_f').' '.__('custom.in').' '.$item->law?->name : 'Към Законодателната инициатива') : (!empty($r->title) ? $r->title : __('site.to_list')))
                        @elseif($s->subscribable_type == 'App\Models\Consultations\PublicConsultation')
                            @php($url = $r->subscribable_id ? route('public_consultation.view', $r->subscribable_id) : route('public_consultation.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                            @php($item = \App\Models\Consultations\PublicConsultation::find($r->subscribable_id))
                            @php($objUrlTitle = $r->subscribable_id ? ($item ? $item->title : 'Към Обществената консултация') : (!empty($r->title) ? $r->title : __('site.to_list')))
                        @elseif($s->subscribable_type == 'App\Models\AdvisoryBoard')
                            @php($url = $r->subscribable_id ? route('advisory-boards.view', $r->subscribable_id) : route('advisory-boards.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                            @php($item = \App\Models\AdvisoryBoard::find($r->subscribable_id))
                            @php($objUrlTitle = $r->subscribable_id ? ($item ? $item->name : 'Към Консултативния съвет') : (!empty($r->title) ? $r->title : __('site.to_list')))
                        @elseif($s->subscribable_type == 'App\Models\Publication')
                            @php($subscribable = $r->subscribable_id ? \App\Models\Publication::find($r->subscribable_id) : null)
                            @php($url = $r->subscribable_id ? route('library.details', $r->subscribable->type, $r->subscribable_id) : (!empty($r->route_name) ? route($r->route_name).(!empty($r->search_filters) ? addUrlParams($r->search_filters) : '') : ''))
                            @php($item = $subscribable)
                            @php($objUrlTitle = $r->subscribable_id ? $r->subscribable->type == \App\Enums\PublicationTypesEnum::TYPE_LIBRARY->value ? ($item ? $item->name : __('site.to_publication')) : ($item ? $item->name : __('site.to_news')) : (!empty($r->title) ? $r->title : __('site.to_list')))
                        @elseif($s->subscribable_type == 'App\Models\Pris')
                            @php($subscribable = $r->subscribable_id ? \App\Models\Pris::find($r->subscribable_id) : null)
                            @php($url = $r->subscribable_id ? route('pris.view', ['category' => \Illuminate\Support\Str::slug($subscribable->actType?->name), 'id' => $r->subscribable_id]) : route('pris.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                            @php($item = \App\Models\Pris::find($r->subscribable_id))
                            @php($objUrlTitle = $r->subscribable_id ? ($item ? $item->mcDisplayName : 'Към Акт на Министерски съвет') : (!empty($r->title) ? $r->title : __('site.to_list')))
                        @elseif($s->subscribable_type == 'App\Models\Poll')
                            @php($url = $r->subscribable_id ? route('poll.show', $r->subscribable_id) : route('poll.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                            @php($item = \App\Models\Poll::find($r->subscribable_id))
                            @php($objUrlTitle = $r->subscribable_id ? ($item ? $item->name : 'Към Анкетата') : (!empty($r->title) ? $r->title : __('site.to_list')))
                        @elseif($s->subscribable_type == 'App\Models\OgpPlan')
                            @php($url = $r->subscribable_id ? route('ogp.national_action_plans.show', $r->subscribable_id) : route('ogp.national_action_plans').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                            @php($item = \App\Models\OgpPlan::find($r->subscribable_id))
                            @php($objUrlTitle = $r->subscribable_id ? ($item ? $item->name : 'Към Плана') : (!empty($r->title) ? $r->title : __('site.to_list')))
                        @elseif($s->subscribable_type == 'App\Models\Consultations\LegislativeProgram')
                            @php($url = $r->subscribable_id ? route('lp.view', $r->subscribable_id) : route('lp.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                            @php($item = \App\Models\Consultations\LegislativeProgram::find($r->subscribable_id))
                            @php($objUrlTitle = $r->subscribable_id ? ($item ? ' '.$item->name : 'Към Законодателната програма') : (!empty($r->title) ? $r->title : __('site.to_list')))
                        @elseif($s->subscribable_type == 'App\Models\Consultations\OperationalProgram')
                            @php($url = $r->subscribable_id ? route('op.view', $r->subscribable_id) : route('op.index').(!empty($r->search_filters) ? addUrlParams($r->search_filters) : ''))
                            @php($item = \App\Models\Consultations\OperationalProgram::find($r->subscribable_id))
                            @php($objUrlTitle = $r->subscribable_id ? ($item ? ' '.$item->name : 'Към Оперативната програма') : (!empty($r->title) ? $r->title : __('site.to_list')))
                        @endif
                        @if(!$r->subscribable_id || $item)
                            @if($startHtml)
{{--                                        <div class="col-12">--}}
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            @php($model = explode("\\", $s->subscribable_type))
                                            @php($label = 'custom.'.$model[sizeof($model) - 1])
                                            <th colspan="3" class="text-white bg-primary">{{ trans()->has($label) ? __($label) : '---' }}</th>
                                        </tr>
                                        <tr>
                                            <th style="width: 60%;">{{ __('custom.view') }}</th>
                                            <th style="width: 20%;">{{ __('custom.status') }}</th>
                                            <th style="width: 20%;">{{ __('custom.actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                    @php($startHtml = false)
                                    @php($endHtml = true)
                            @endif
                            <tr>
                                <td>
                                    <a href="{{ $url }}" target="_blank">{{ $objUrlTitle }}</a>
                                    @if(!$r->subscribable_id)
                                        <br><span class="fw-bold">Филтър:</span> {{ \App\Models\UserSubscribe::filterToTextById($r->id) }}
                                    @endif
                                </td>
                                <td>{{ $r->is_subscribed ? __('custom.active_m') : __('custom.inactive_m') }}</td>
                                <td>
                                    <a href="{{ route('profile.subscribe.set', ['id' => $r->id, 'status' => (int)!$r->is_subscribed]) }}"><i class="fas @if($r->is_subscribed) fa-pause-circle main-color @else fa-play-circle text-success @endif fs-5 me-1" title="{{ $r->is_subscribed ? __('site.pause') : __('site.activate') }}"></i></a>
                                    @if(!$r->subscribable_id)
                                        <i class="fas fa-pencil-square main-color edit_subscribe fs-5 me-1" role="button" data-objname="{{ $objUrlTitle }}" data-objid="{{ $r->id }}" title="{{ __('custom.edit') }}"></i>
                                    @endif
                                    <form class="d-none"
                                          method="POST"
                                          action="{{ route('profile.subscribe.delete', ['id' => $r->id]) }}"
                                          name="DELETE_COMMENT_{{ $r->id }}">
                                        @csrf
                                    </form>
                                    <i class="fas fa-trash text-danger open-delete-modal fs-5 me-1" role="button" data-objname="{{ $objUrlTitle }}" title="{{ __('custom.delete') }}"></i>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if($endHtml)
                        </tbody>
                    </table>
                    @endif
                @endif
            </div>
        @endforeach
            <form id="edit-subscription-name" class="d-none" method="POST" action="{{ route('profile.subscribe.store') }}">
                @csrf
                <input type="text" id="subscription_id" name="subscription_id" value="">
                <input type="text" id="subscription_name" name="subscription_name" value="">
            </form>
    @else
        <div class="col-12">
            {{ __('site.do_not_have_subscriptions') }}
        </div>
    @endif
</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('.open-delete-modal').on('click', function () {
                const form = $(this).parent().find('form').attr('name');
                const objName = $(this).data('objname');

                new MyModal({
                    title: @json(__('custom.deletion') . ' ' . __('custom.of') . ' ' . trans_choice('custom.subscriptions', 1)),
                    footer: '<button class="btn btn-sm btn-success ms-3" onclick="' + form + '.submit()">' + @json(__('custom.continue')) + '</button>' +
                        '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="' + @json(__('custom.cancel')) + '">' + @json(__('custom.cancel')) + '</button>',
                    body: '<div class="alert alert-danger">' + @json(__('custom.are_you_sure_to_delete') . ' ') + objName + @json('?') + '</div>',
                });
            });
        });
    </script>

@endpush

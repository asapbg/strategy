
    @if(isset($pageTopContent) && !empty($pageTopContent->value))
        <div class="col-12 mb-5">
            {!! $pageTopContent->value !!}
        </div>
    @endif
    @include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'subscribe' => false, 'export_excel' => true, 'export_pdf' => true])
    @include('site.partial.sorter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'info' => __('site.sort_info_strategic_documents')])

    <div class="row mb-2">
        <div class="col-md-6 mt-2">
            <div class="info-consul text-start">
                <p class="fw-600">
                    {{ trans_choice('custom.total_pagination_result', $items->count(), ['number' => $items->total()]) }}
                </p>
            </div>
        </div>
        @include('site.partial.paginate_filter', ['ajaxContainer' => '#listContainer'])
    </div>

    @if($items->count())
        <div class="row table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <td>{{ __('custom.name') }}</td>
                        <td>{{ trans_choice('custom.field_of_actions', 1) }}</td>
                        <td>{{ __('custom.type_of_governing') }}</td>
                        <td>{{ __('validation.attributes.act_of_creation') }}</td>
                        <td>{{ __('validation.attributes.advisory_chairman_type_id') }}</td>
                        <td>Представител на НПО</td>
                        <td>Мин. бр. заседания на година</td>
                        @if($searchMeetings)
                            <td>Бр. заседания в периода</td>
                        @endif
                        <td>{{ __('custom.status') }}</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $document)
                    <tr>
                        <td>{{ $document->name }}</td>
                        <td>{{ $document->policyArea?->name }}</td>
                        <td>{{ $document->authority?->name }}</td>
                        <td>{{ $document->advisoryActType?->name }}</td>
                        <td>{{ $document->advisoryChairmanType?->name }}</td>
                        <td>{{ $document->has_npo_presence ? 'Да' : 'Не' }}</td>
                        <td>{{ $document->meetings_per_year }}</td>
                        @if($searchMeetings)
                            <td>{{ $document->meetings }}</td>
                        @endif
                        <td>{{ $document->active ? __('custom.active_m') : __('custom.inactive_m') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif


    <div class="row">
        @if(isset($items) && $items->count() > 0)
            {{ $items->onEachSide(0)->appends(request()->query())->links() }}
        @endif
    </div>

    @push('scripts')

    @endpush

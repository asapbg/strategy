
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
                        <td>{{ __('custom.status') }}</td>
                        <td>{{ trans_choice('custom.institution', 1) }}</td>
                        <td>{{ trans_choice('custom.act_type', 1) }}</td>
                        <td>Срок (дни)</td>
                        <td>Мотиви за кратък срок</td>
{{--                        <td>Липсващи документи</td>--}}
                        <td>{{ trans_choice('custom.comment', 2) }}</td>
                        <td>Справка/съобщение</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td><a href="{{ route('public_consultation.view', $item->id) }}" target="_blank">{{ $item->title }}</a></td>
                        <td>{{ $item->fieldOfAction?->name }}</td>
                        <td>{{ $item->inPeriod }}</td>
                        <td>{{ $item->importerInstitution?->name }}</td>
                        <td>{{ $item->actType?->name }}</td>
                        <td>{{ $item->daysCnt }}</td>
                        <td>{!! $item->short_term_reason !!}</td>
{{--                        <td>????</td>--}}
                        <td>{{ $item->comments->count() }}</td>
                        <td>@if($item->proposalReport->count()){{ 'Да' }}@else{{ 'Не' }}@endif</td>
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

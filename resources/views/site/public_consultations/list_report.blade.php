
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
                        <th>{{ __('custom.name') }}</th>
                        <th>{{ trans_choice('custom.field_of_actions', 1) }}</th>
                        <th>{{ __('custom.status') }}</th>
                        <th>{{ trans_choice('custom.institution', 1) }}</th>
                        <th>{{ trans_choice('custom.act_type', 1) }}</th>
                        <th>Срок (дни)</th>
                        <th>Мотиви за кратък срок</th>
                        <th>Липсващи документи</th>
                        <th>{{ trans_choice('custom.comment', 2) }}</th>
                        <th>Справка/съобщение</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td><a href="{{ route('public_consultation.view', $item->id) }}" target="_blank">{{ $item->title }}</a></td>
                        <td>{{ $item->fieldOfAction?->name }}</td>
                        <td>{{ $item->inPeriod }}</td>
                        <td>@if($item->importer_institution_id == env('DEFAULT_INSTITUTION_ID')){{ '' }}@else{{ $item->importerInstitution?->name }}@endif</td>
                        <td>{{ $item->actType?->name }}</td>
                        <td>{{ $item->daysCnt }}</td>
                        <td>{!! $item->short_term_reason !!}</td>
                        <td>
                            @if(isset($missingFiles) && sizeof($missingFiles) && isset($missingFiles[$item->id]) && $missingFiles[$item->id] > 0)
                                {{ __('custom.yes') }}
                            @else
                                {{ __('custom.no') }}
                            @endif
                        </td>
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

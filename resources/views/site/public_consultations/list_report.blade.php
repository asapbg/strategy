
    @if(isset($pageTopContent) && !empty($pageTopContent->value))
        <div class="col-12 mb-2">
            {!! $pageTopContent->value !!}
        </div>
    @endif
    @include('site.partial.filter', ['ajax' => true, 'ajaxContainer' => '#listContainer', 'subscribe' => false, 'export_excel' => true])
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
                        <th>{{ __('site.public_consultation.short_term_motive_label') }}</th>
                        <th>{{ __('custom.pc_reports.missing_documents') }}</th>
                        <th>{{ trans_choice('custom.comment', 2) }}</th>
                        <th>{{ __('custom.pc_reports.standard.comment_report') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    @php($existDocTypes = json_decode($item->doc_types))
                    <tr>
                        <td><a href="{{ route('public_consultation.view', $item->id) }}" target="_blank">{{ $item->title }}</a></td>
                        <td>{{ $item->fieldOfAction?->name }}</td>
                        <td>{{ $item->inPeriod }}</td>
                        <td>@if($item->importer_institution_id == env('DEFAULT_INSTITUTION_ID')){{ '' }}@else{{ $item->importerInstitution?->name }}@endif</td>
                        <td>{{ $item->actType?->name }}</td>
                        <td>{{ $item->daysCnt }}</td>
                        <td><span>@if(!empty($item->short_term_reason)){{ __('custom.yes') }}<i class="fas fa-info-circle text-primary ms-1" title="{{ $item->short_term_reason }}" data-html="true" data-bs-placement="top" data-bs-toggle="tooltip"></i>@else{{ __('custom.no') }}@endif</span></td>
                        <td>
                            @php($requiredDocs = \App\Enums\DocTypesEnum::pcRequiredDocTypesByActType($item->act_type_id))
                            @if(sizeof($requiredDocs))
                                @foreach($requiredDocs as $rd)
                                    @if(empty($existDocTypes) || !in_array($rd, $existDocTypes))
                                        <span class="d-block">{{ __('custom.public_consultation.doc_type.'.$rd) }}</span>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $item->comments->count() }}</td>
                        <td>@if($item->proposalReport->count()){{ __('custom.yes') }}@else{{ __('custom.no') }}@endif</td>
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
        <script type="text/javascript">
            $(document).ready(function (){
                categoriesControl();
            });
        </script>
    @endpush

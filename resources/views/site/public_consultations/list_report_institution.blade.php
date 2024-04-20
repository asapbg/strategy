
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
                        <th>{{ __('custom.pc_count') }}</th>
                        <th>{{ __('custom.pc_less_then_30_days_count') }}</th>
                        <th>{{ __('custom.pc_no_short_reason') }}</th>
                        <th>{{ __('custom.pc_missing_docs') }}</th>
                        <th>{{ __('custom.pc_no_proposal_report') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td class="custom-left-border">{{ $item->name }}</td>
                        <td>{{ $item->pc_cnt }}</td>
                        <td>{{ $item->less_days_cnt }} @if($item->pc_cnt && $item->less_days_cnt)({{ round((($item->less_days_cnt/$item->pc_cnt) * 100), 2) }}%)@endif</td>
                        <td>{{ $item->no_less_days_reason_cnt }} @if($item->less_days_cnt && $item->no_less_days_reason_cnt)({{ round((($item->no_less_days_reason_cnt/$item->less_days_cnt) * 100), 2) }}%)@endif</td>
                        <td>
                            @if(isset($missingFiles) && sizeof($missingFiles) && isset($missingFiles[$item->id]) && $missingFiles[$item->id] > 0)
                                {{ __('custom.yes') }}
                            @else
                                {{ __('custom.no') }}
                            @endif
                        </td>
                        <td>{{ $item->has_report }}</td>
                    </tr>
                    @if(isset($consultationsByActType) && sizeof($consultationsByActType) && isset($consultationsByActType[$item->id]))
                        @php($byActType = json_decode($consultationsByActType[$item->id]->act_info, true))
                        @if($byActType)
                            <tr>
                                <th colspan="6">{{ trans_choice('custom.act_type', 1) }}</th>
                            </tr>
                            @foreach($byActType as $act)
                                <tr>
                                    <td>{{ $act['act_name'] }}</td>
                                    <td colspan="5">{{ $act['act_cnt'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
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

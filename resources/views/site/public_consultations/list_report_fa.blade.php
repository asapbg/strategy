
    @if(isset($pageTopContent) && !empty($pageTopContent->value))
        <div class="col-12 mb-2">
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
                        <th>{{ trans_choice('custom.public_consultations', 2) }}</th>
                    </tr>
                </thead>
                <tbody>
                @php($fieldOfActionGroup = '')
                @foreach($items as $item)
                    @switch($item->fa_level)
                        @case(\App\Models\FieldOfAction::CATEGORY_NATIONAL)
                            @php($itemFaGroup = trans_choice('custom.nationals_fa', 2))
                        @break
                        @case(\App\Models\FieldOfAction::CATEGORY_AREA)
                            @php($itemFaGroup = trans_choice('custom.areas_fa', 2))
                        @break
                        @case(\App\Models\FieldOfAction::CATEGORY_MUNICIPAL)
                            @php($itemFaGroup = trans_choice('custom.municipal_fa', 2))
                        @break
                        @default
                            @php($itemFaGroup = '')
                        @break
                    @endswitch
                    @if($fieldOfActionGroup != $itemFaGroup)
{{--                        @php($fieldOfActionGroupCnt = 0)--}}
{{--                        @foreach($items as $i)--}}
{{--                            @php($fieldOfActionGroupCnt += ($i->fa_level == $item->fa_level ? $i->pc_cnt : 0))--}}
{{--                        @endforeach--}}
                        @php($fieldOfActionGroup = $itemFaGroup)
                        <tr class="fw-bold">
                            <td class="custom-left-border">{{ $itemFaGroup }}</td>
                            <td>{{ isset($fieldOfActionGroupCnt) && sizeof($fieldOfActionGroupCnt) && isset($fieldOfActionGroupCnt[(int)$item->fa_level]) ? $fieldOfActionGroupCnt[(int)$item->fa_level] : 0 }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->pc_cnt }}</td>
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
